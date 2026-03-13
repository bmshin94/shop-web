<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Services\Admin\EventManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private const DEFAULT_PER_PAGE = 6;

    public function __construct(
        private readonly EventManagementService $eventManagementService
    ) {
    }

    /**
     * 당첨자 등록을 위한 회원 검색 (AJAX)
     */
    public function searchMembers(Request $request): \Illuminate\Http\JsonResponse
    {
        $keyword = $request->query('keyword');

        if (empty($keyword)) {
            return response()->json([]);
        }

        $members = \App\Models\Member::query()
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            })
            ->active()
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($members);
    }

    /**
     * 관리자 이벤트 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'type',
            'start_from',
            'start_to',
        ]);

        $events = $this->eventManagementService->paginateEvents($filters, self::DEFAULT_PER_PAGE);
        $events->appends($request->all()); // 검색 조건 유지! ✨
        $stats = $this->eventManagementService->getSummaryStats();

        return view('admin.events.index', [
            'events' => $events,
            'stats' => $stats,
            'trashedEventsCount' => Event::onlyTrashed()->count(),
            'statusOptions' => ['진행중', '진행예정', '종료'],
            'typeOptions' => Event::TYPES,
        ]);
    }

    /**
     * 관리자 이벤트 등록 폼을 조회한다.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * 관리자 이벤트를 등록한다.
     *
     * @param  StoreEventRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $event = $this->eventManagementService->createEvent($request->validated());

        return redirect()
            ->route('admin.events.edit', $event)
            ->with('success', '이벤트가 등록되었습니다.');
    }

    /**
     * 관리자 이벤트 수정 폼을 조회한다.
     *
     * @param  Event  $event
     * @return View
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', [
            'event' => $event,
        ]);
    }

    /**
     * 관리자 이벤트를 수정한다.
     *
     * @param  UpdateEventRequest  $request
     * @param  Event  $event
     * @return RedirectResponse
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->eventManagementService->updateEvent($event, $request->validated());

        return redirect()
            ->route('admin.events.edit', array_merge(['event' => $event->id], $request->query()))
            ->with('success', '이벤트 정보가 업데이트되었습니다.');
    }

    /**
     * 히어로 영역 노출 여부를 토글한다.
     */
    public function toggleHero(Request $request, Event $event): \Illuminate\Http\JsonResponse
    {
        $isHero = $request->boolean('is_hero');
        $this->eventManagementService->updateEvent($event, ['is_hero' => $isHero]);

        return response()->json(['success' => true]);
    }

    /**
     * 이벤트 응모자 목록을 조회한다.
     */
    public function participants(Event $event): View
    {
        $participants = $event->participations()
            ->with('member')
            ->latest()
            ->paginate(20);

        $participants->appends(request()->all()); // 검색 조건 유지! ✨

        // 현재 당첨자 ID 목록 조회
        $winnerIds = $event->winners()->pluck('members.id')->toArray();

        return view('admin.events.participants', compact('event', 'participants', 'winnerIds'));
    }

    /**
     * 이벤트 응모자 목록을 CSV로 내보낸다.
     */
    public function exportParticipants(Event $event)
    {
        // ... (기존 코드와 동일)
    }

    /**
     * 응모자 중에서 당첨 여부를 토글한다.
     */
    public function toggleParticipantWinner(Request $request, Event $event, \App\Models\Member $member): \Illuminate\Http\JsonResponse
    {
        $isWinner = $request->boolean('is_winner');

        if ($isWinner) {
            $event->winners()->syncWithoutDetaching([$member->id]);
        } else {
            $event->winners()->detach($member->id);
        }

        return response()->json(['success' => true]);
    }

    /**
     * 관리자 이벤트를 soft delete 처리한다.
     *
     * @param  Event  $event
     * @return RedirectResponse
     */
    public function destroy(Event $event): RedirectResponse
    {
        $eventTitle = $event->title;

        $this->eventManagementService->deleteEvent($event);

        return redirect()
            ->route('admin.events.index')
            ->with('success', "이벤트 {$eventTitle} 이(가) 삭제 처리되었습니다.");
    }

    /**
     * 삭제된 이벤트 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function trash(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'type',
            'start_from',
            'start_to',
        ]);

        $events = $this->eventManagementService->paginateTrashedEvents($filters, self::DEFAULT_PER_PAGE);
        $events->appends($request->all()); // 검색 조건 유지! ✨

        return view('admin.events.trash', [
            'events' => $events,
            'statusOptions' => ['진행중', '진행예정', '종료'],
            'typeOptions' => Event::TYPES,
        ]);
    }

    /**
     * soft delete 이벤트를 복구한다.
     *
     * @param  Event  $event
     * @return RedirectResponse
     */
    public function restore(Event $event): RedirectResponse
    {
        if (! $this->eventManagementService->restoreEvent($event)) {
            return redirect()
                ->route('admin.events.trash')
                ->with('error', '복구할 수 없는 이벤트입니다.');
        }

        return redirect()
            ->route('admin.events.trash')
            ->with('success', "이벤트 {$event->title} 이(가) 복구되었습니다.");
    }

    /**
     * soft delete 이벤트를 영구 삭제한다.
     *
     * @param  Event  $event
     * @return RedirectResponse
     */
    public function forceDestroy(Event $event): RedirectResponse
    {
        $eventTitle = $event->title;

        if (! $this->eventManagementService->forceDeleteEvent($event)) {
            return redirect()
                ->route('admin.events.trash')
                ->with('error', '영구 삭제할 수 없는 이벤트입니다.');
        }

        return redirect()
            ->route('admin.events.trash')
            ->with('success', "이벤트 {$eventTitle} 이(가) 영구 삭제되었습니다.");
    }
}

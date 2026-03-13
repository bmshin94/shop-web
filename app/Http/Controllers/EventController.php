<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * 이벤트 목록 페이지
     */
    public function index()
    {
        $now = now();

        // 히어로 영역용 이벤트 (is_hero가 true인 것들) ✨
        $heroEvents = Event::with('winners')
            ->where('is_hero', true)
            ->where(function ($query) use ($now) {
                // 종료되지 않았거나, 종료일이 현재보다 미래인 것만 노출
                $query->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $upcomingQuery = Event::with('winners')
            ->where('start_at', '>', $now)
            ->orderBy('start_at', 'asc')
            ->orderBy('id', 'desc');

        $ongoingQuery = Event::with('winners')
            ->where(function ($query) use ($now) {
                $query->whereNull('start_at')->orWhere('start_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('end_at')->orWhere('end_at', '>=', $now);
            })
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc');

        $endedQuery = Event::with('winners')
            ->whereNotNull('end_at')
            ->where('end_at', '<', $now)
            ->orderBy('end_at', 'desc')
            ->orderBy('id', 'desc');

        $winnerQuery = Event::with('winners')
            ->where(function ($query) {
                $query->whereNotNull('winner_announcement')
                      ->orWhereHas('winners');
            })
            ->orderBy('end_at', 'desc')
            ->orderBy('id', 'desc');

        $upcomingEvents = $upcomingQuery->paginate(6, ['*'], 'upcoming_page', 1);
        $ongoingEvents = $ongoingQuery->paginate(6, ['*'], 'ongoing_page', 1);
        $endedEvents = $endedQuery->paginate(6, ['*'], 'ended_page', 1);
        $winnerEvents = $winnerQuery->paginate(6, ['*'], 'winner_page', 1);

        $hasMore = [
            'upcoming' => $upcomingEvents->hasMorePages(),
            'ongoing' => $ongoingEvents->hasMorePages(),
            'winner' => $winnerEvents->hasMorePages(),
            'ended' => $endedEvents->hasMorePages(),
        ];

        // 프론트엔드 모달용 전체 데이터 병합 ✨
        $eventsData = $upcomingEvents->getCollection()
            ->concat($ongoingEvents->getCollection())
            ->concat($endedEvents->getCollection())
            ->concat($winnerEvents->getCollection())
            ->concat($heroEvents)
            ->keyBy('id');

        // 사용자가 이미 응모한 이벤트 ID 목록 조회
        $participatedEventIds = auth()->check()
            ? auth()->user()->eventParticipations()->pluck('event_id')->toArray()
            : [];

        return view('pages.event', compact(
            'upcomingEvents', 
            'ongoingEvents', 
            'endedEvents', 
            'winnerEvents', 
            'heroEvents', 
            'hasMore',
            'eventsData',
            'participatedEventIds'
        ));
    }

    /**
     * 이벤트 응모 처리
     */
    public function participate(Event $event): \Illuminate\Http\JsonResponse
    {
        if (! auth()->check()) {
            return response()->json(['message' => '로그인이 필요한 서비스입니다.'], 401);
        }

        if ($event->type !== Event::TYPE_PARTICIPATION) {
            return response()->json(['message' => '응모 가능한 이벤트 유형이 아닙니다.'], 400);
        }

        $now = now();
        $isStarted = ! $event->start_at || $event->start_at->isPast();
        $isEnded = $event->end_at && $event->end_at->isPast();

        if (! $isStarted || $isEnded) {
            return response()->json(['message' => '현재 응모 가능한 기간이 아닙니다.'], 400);
        }

        $exists = $event->participations()
            ->where('member_id', auth()->id())
            ->exists();

        if ($exists) {
            return response()->json(['message' => '이벤트 응모 완료'], 400);
        }

        $event->participations()->create([
            'member_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '성공적으로 응모되었습니다.'
        ]);
    }

    /**
     * 이벤트 응모 취소 처리
     */
    public function cancelParticipation(Event $event): \Illuminate\Http\JsonResponse
    {
        if (! auth()->check()) {
            return response()->json(['message' => '로그인이 필요한 서비스입니다.'], 401);
        }

        $now = now();
        $isStarted = ! $event->start_at || $event->start_at->isPast();
        $isEnded = $event->end_at && $event->end_at->isPast();

        if (! $isStarted || $isEnded) {
            return response()->json(['message' => '이벤트 기간이 종료되어 응모를 취소할 수 없습니다.'], 400);
        }

        $participation = $event->participations()
            ->where('member_id', auth()->id())
            ->first();

        if (! $participation) {
            return response()->json(['message' => '응모 내역을 찾을 수 없습니다.'], 404);
        }

        $participation->delete();

        return response()->json([
            'success' => true,
            'message' => '응모가 취소되었습니다.'
        ]);
    }

    /**
     * AJAX 더보기
     */
    public function loadMore(Request $request): \Illuminate\Http\JsonResponse
    {
        $tab = $request->query('tab', 'ongoing');
        $now = now();
        $query = Event::with('winners'); // 모든 쿼리에 기본으로 추가! ✨

        if ($tab === 'upcoming') {
            $query->where('start_at', '>', $now)
                ->orderBy('start_at', 'asc')
                ->orderBy('id', 'desc');
        } elseif ($tab === 'ongoing') {
            $query->where(function ($q) use ($now) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                })
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'desc');
        } elseif ($tab === 'winner') {
            $query->where(function ($q) {
                    $q->whereNotNull('winner_announcement')
                      ->orWhereHas('winners');
                })
                ->orderBy('end_at', 'desc')
                ->orderBy('id', 'desc');
        } else {
            $query->whereNotNull('end_at')
                ->where('end_at', '<', $now)
                ->orderBy('end_at', 'desc')
                ->orderBy('id', 'desc');
        }

        $events = $query->paginate(6);

        $events->getCollection()->transform(function($event) {
            $event->banner_url = $event->banner_image_url ? \Storage::url($event->banner_image_url) : null;
            return $event;
        });

        return response()->json([
            'events' => $events->items(),
            'has_more' => $events->hasMorePages()
        ]);
    }
}

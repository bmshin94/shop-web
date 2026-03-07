<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMemberRequest;
use App\Models\Member;
use App\Services\Admin\MemberManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(
        private readonly MemberManagementService $memberManagementService
    ) {
    }

    /**
     * 관리자 회원 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'joined_from',
            'joined_to',
        ]);

        $members = $this->memberManagementService->paginateMembers($filters);
        $stats = $this->memberManagementService->getSummaryStats();

        return view('admin.members.index', [
            'members' => $members,
            'stats' => $stats,
            'trashedMembersCount' => Member::onlyTrashed()->count(),
            'statusOptions' => Member::STATUSES,
        ]);
    }

    /**
     * 관리자 회원 휴지통 목록을 조회한다.
     *
     * @param  Request  $request
     * @return View
     */
    public function trash(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'joined_from',
            'joined_to',
        ]);

        $members = $this->memberManagementService->paginateTrashedMembers($filters);

        return view('admin.members.trash', [
            'members' => $members,
            'statusOptions' => Member::STATUSES,
        ]);
    }

    /**
     * 관리자 회원 상세를 조회한다.
     *
     * @param  Member  $member
     * @return View
     */
    public function show(Member $member): View
    {
        return view('admin.members.show', [
            'member' => $member,
            'statusOptions' => Member::STATUSES,
        ]);
    }

    /**
     * 관리자 회원 정보를 수정한다.
     *
     * @param  UpdateMemberRequest  $request
     * @param  Member  $member
     * @return RedirectResponse
     */
    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $this->memberManagementService->updateMember($member, $request->validated());

        return redirect()
            ->route('admin.members.show', $member)
            ->with('success', '회원 정보가 업데이트되었습니다.');
    }

    /**
     * 관리자 회원을 soft delete 처리한다.
     *
     * @param  Member  $member
     * @return RedirectResponse
     */
    public function destroy(Member $member): RedirectResponse
    {
        $memberName = $member->name;

        $this->memberManagementService->deleteMember($member);

        return redirect()
            ->route('admin.members.index')
            ->with('success', "회원 {$memberName} 님이 삭제 처리되었습니다.");
    }

    /**
     * soft delete된 회원을 복구한다.
     *
     * @param  Member  $member
     * @return RedirectResponse
     */
    public function restore(Member $member): RedirectResponse
    {
        if (! $this->memberManagementService->restoreMember($member)) {
            return redirect()
                ->route('admin.members.trash')
                ->with('error', '복구할 수 없는 회원입니다.');
        }

        return redirect()
            ->route('admin.members.trash')
            ->with('success', "회원 {$member->name} 님이 복구되었습니다.");
    }

    /**
     * soft delete된 회원을 영구 삭제한다.
     *
     * @param  Member  $member
     * @return RedirectResponse
     */
    public function forceDestroy(Member $member): RedirectResponse
    {
        $memberName = $member->name;

        if (! $this->memberManagementService->forceDeleteMember($member)) {
            return redirect()
                ->route('admin.members.trash')
                ->with('error', '영구 삭제할 수 없는 회원입니다.');
        }

        return redirect()
            ->route('admin.members.trash')
            ->with('success', "회원 {$memberName} 님이 영구 삭제되었습니다.");
    }
}

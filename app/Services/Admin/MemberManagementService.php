<?php

namespace App\Services\Admin;

use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemberManagementService
{
    /**
     * 관리자 회원 목록을 필터링하여 페이징한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateMembers(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Member::query()->latest('created_at');

        $this->applyMemberFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 휴지통 회원 목록을 필터링하여 페이징한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateTrashedMembers(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Member::onlyTrashed()->latest('deleted_at');

        $this->applyMemberFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 관리자 회원 대시보드 요약 통계를 계산한다.
     *
     * @return array<string, int>
     */
    public function getSummaryStats(): array
    {
        return [
            'total_members' => Member::count(),
            'active_members' => Member::where('status', '활성')->count(),
            'dormant_members' => Member::where('status', '휴면')->count(),
            'suspended_members' => Member::where('status', '정지')->count(),
            'new_members_7d' => Member::whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * 회원 정보를 수정한다.
     *
     * @param  Member  $member
     * @param  array<string, mixed>  $payload
     * @return Member
     */
    public function updateMember(Member $member, array $payload): Member
    {
        $member->update($payload);

        return $member->refresh();
    }

    /**
     * 회원을 soft delete 처리해 목록에서 숨긴다.
     *
     * @param  Member  $member
     * @return void
     */
    public function deleteMember(Member $member): void
    {
        $member->delete();
    }

    /**
     * soft delete된 회원을 복구한다.
     *
     * @param  Member  $member
     * @return bool
     */
    public function restoreMember(Member $member): bool
    {
        if (! $member->trashed()) {
            return false;
        }

        return (bool) $member->restore();
    }

    /**
     * soft delete된 회원을 영구 삭제한다.
     *
     * @param  Member  $member
     * @return bool
     */
    public function forceDeleteMember(Member $member): bool
    {
        if (! $member->trashed()) {
            return false;
        }

        return (bool) $member->forceDelete();
    }

    /**
     * 회원 목록 필터를 쿼리에 공통 적용한다.
     *
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     * @return void
     */
    private function applyMemberFilters(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['joined_from'])) {
            $query->whereDate('created_at', '>=', $filters['joined_from']);
        }

        if (! empty($filters['joined_to'])) {
            $query->whereDate('created_at', '<=', $filters['joined_to']);
        }
    }
}

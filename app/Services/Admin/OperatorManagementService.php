<?php

namespace App\Services\Admin;

use App\Models\Operator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class OperatorManagementService
{
    /**
     * 관리자 운영자 목록을 필터링하여 페이징한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateOperators(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Operator::query()->latest('created_at');

        $this->applyOperatorFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 휴지통 운영자 목록을 필터링하여 페이징한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateTrashedOperators(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Operator::onlyTrashed()->latest('deleted_at');

        $this->applyOperatorFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 관리자 운영자 대시보드 요약 통계를 계산한다.
     *
     * @return array<string, int>
     */
    public function getSummaryStats(): array
    {
        return [
            'total_operators' => Operator::count(),
            'active_operators' => Operator::where('status', '활성')->count(),
            'dormant_operators' => Operator::where('status', '휴면')->count(),
            'suspended_operators' => Operator::where('status', '정지')->count(),
            'new_operators_7d' => Operator::whereDate('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * 운영자를 등록한다.
     *
     * @param  array<string, mixed>  $payload
     * @return Operator
     */
    public function createOperator(array $payload): Operator
    {
        return Operator::query()->create($payload);
    }

    /**
     * 운영자 정보를 수정한다.
     *
     * @param  Operator  $operator
     * @param  array<string, mixed>  $payload
     * @return Operator
     */
    public function updateOperator(Operator $operator, array $payload): Operator
    {
        $operator->update($payload);

        return $operator->refresh();
    }

    /**
     * 운영자를 soft delete 처리해 목록에서 숨긴다.
     *
     * @param  Operator  $operator
     * @return void
     */
    public function deleteOperator(Operator $operator): void
    {
        $operator->delete();
    }

    /**
     * soft delete된 운영자를 복구한다.
     *
     * @param  Operator  $operator
     * @return bool
     */
    public function restoreOperator(Operator $operator): bool
    {
        if (! $operator->trashed()) {
            return false;
        }

        return (bool) $operator->restore();
    }

    /**
     * soft delete된 운영자를 영구 삭제한다.
     *
     * @param  Operator  $operator
     * @return bool
     */
    public function forceDeleteOperator(Operator $operator): bool
    {
        if (! $operator->trashed()) {
            return false;
        }

        return (bool) $operator->forceDelete();
    }

    /**
     * 운영자 목록 필터를 쿼리에 공통 적용한다.
     *
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     * @return void
     */
    private function applyOperatorFilters(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
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

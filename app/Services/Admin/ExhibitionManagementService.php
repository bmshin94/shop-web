<?php

namespace App\Services\Admin;

use App\Models\Exhibition;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ExhibitionManagementService
{
    /**
     * 관리자 기획전 목록을 필터링하여 페이지네이션한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateExhibitions(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Exhibition::query()
            ->latest('start_at')
            ->latest('id');

        $this->applyExhibitionFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 삭제된 기획전 목록을 필터링하여 페이지네이션한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateTrashedExhibitions(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Exhibition::onlyTrashed()
            ->latest('deleted_at')
            ->latest('id');

        $this->applyExhibitionFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 기획전 대시보드 요약 통계를 계산한다.
     *
     * @return array<string, int>
     */
    public function getSummaryStats(): array
    {
        return [
            'total_exhibitions' => Exhibition::count(),
            'upcoming_exhibitions' => Exhibition::where('status', '진행예정')->count(),
            'active_exhibitions' => Exhibition::where('status', '진행중')->count(),
            'ended_exhibitions' => Exhibition::where('status', '종료')->count(),
            'hidden_exhibitions' => Exhibition::where('status', '비노출')->count(),
        ];
    }

    /**
     * 기획전을 등록한다.
     *
     * @param  array<string, mixed>  $payload
     * @return Exhibition
     */
    public function createExhibition(array $payload): Exhibition
    {
        return Exhibition::query()->create($payload);
    }

    /**
     * 기획전을 수정한다.
     *
     * @param  Exhibition  $exhibition
     * @param  array<string, mixed>  $payload
     * @return Exhibition
     */
    public function updateExhibition(Exhibition $exhibition, array $payload): Exhibition
    {
        $exhibition->update($payload);

        return $exhibition->refresh();
    }

    /**
     * 기획전을 soft delete 처리한다.
     *
     * @param  Exhibition  $exhibition
     * @return void
     */
    public function deleteExhibition(Exhibition $exhibition): void
    {
        $exhibition->delete();
    }

    /**
     * soft delete 기획전을 복구한다.
     *
     * @param  Exhibition  $exhibition
     * @return bool
     */
    public function restoreExhibition(Exhibition $exhibition): bool
    {
        if (! $exhibition->trashed()) {
            return false;
        }

        return (bool) $exhibition->restore();
    }

    /**
     * soft delete 기획전을 영구 삭제한다.
     *
     * @param  Exhibition  $exhibition
     * @return bool
     */
    public function forceDeleteExhibition(Exhibition $exhibition): bool
    {
        if (! $exhibition->trashed()) {
            return false;
        }

        return (bool) $exhibition->forceDelete();
    }

    /**
     * 기획전 공통 필터를 적용한다.
     *
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     * @return void
     */
    private function applyExhibitionFilters(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('summary', 'like', '%' . $search . '%');
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['start_from'])) {
            $query->whereDate('start_at', '>=', $filters['start_from']);
        }

        if (! empty($filters['start_to'])) {
            $query->whereDate('start_at', '<=', $filters['start_to']);
        }
    }
}

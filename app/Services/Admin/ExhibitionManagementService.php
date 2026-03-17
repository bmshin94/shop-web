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
            ->latest(); // 생성일(created_at) 기준으로 최신순 정렬!  새로 등록한 게 맨 위로 와요! 

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
        // 배너 이미지 업로드 처리! 
        if (isset($payload['banner_image']) && $payload['banner_image'] instanceof \Illuminate\Http\UploadedFile) {
            $payload['banner_image_url'] = $this->uploadBannerImage($payload['banner_image']);
        }

        // 날짜 기반 상태 자동 결정! 
        $payload['status'] = $this->determineStatusByDates($payload['start_at'] ?? null, $payload['end_at'] ?? null);

        $exhibition = Exhibition::query()->create($payload);

        if (isset($payload['product_ids'])) {
            $exhibition->products()->sync($payload['product_ids']);
        }

        return $exhibition;
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
        // 배너 이미지 업로드 처리! 
        if (isset($payload['banner_image']) && $payload['banner_image'] instanceof \Illuminate\Http\UploadedFile) {
            $payload['banner_image_url'] = $this->uploadBannerImage($payload['banner_image']);
        }

        // 날짜 기반 상태 자동 결정! 
        $payload['status'] = $this->determineStatusByDates($payload['start_at'] ?? null, $payload['end_at'] ?? null);

        $exhibition->update($payload);

        if (isset($payload['product_ids'])) {
            $exhibition->products()->sync($payload['product_ids']);
        }

        return $exhibition->refresh();
    }

    /**
     * 날짜를 기준으로 상태를 결정한다. 
     */
    private function determineStatusByDates(?string $startAt, ?string $endAt): string
    {
        $now = now();
        $start = $startAt ? \Illuminate\Support\Carbon::parse($startAt) : null;
        $end = $endAt ? \Illuminate\Support\Carbon::parse($endAt) : null;

        // 1. 종료일이 지났다면 무조건 '종료' 
        if ($end && $end->isPast()) {
            return '종료';
        }

        // 2. 시작일이 아직 안 왔다면 '진행예정' ⏳
        if ($start && $start->isFuture()) {
            return '진행예정';
        }

        // 3. 그 외(시작일 지났고 종료일 안 지났거나 날짜가 없는 경우) '진행중' 
        return '진행중';
    }

    /**
     * 배너 이미지를 저장소에 업로드한다.
     */
    private function uploadBannerImage(\Illuminate\Http\UploadedFile $file): string
    {
        $path = $file->store('exhibitions', 'public');
        return \Illuminate\Support\Facades\Storage::url($path);
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

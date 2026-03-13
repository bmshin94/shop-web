<?php

namespace App\Services\Admin;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EventManagementService
{
    /**
     * 관리자 이벤트 목록을 필터링하여 페이지네이션한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateEvents(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Event::query()
            ->withCount('participations')
            ->latest('start_at')
            ->latest('id');

        $this->applyEventFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 삭제된 이벤트 목록을 필터링하여 페이지네이션한다.
     *
     * @param  array<string, mixed>  $filters
     * @param  int  $perPage
     * @return LengthAwarePaginator
     */
    public function paginateTrashedEvents(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Event::onlyTrashed()
            ->latest('deleted_at')
            ->latest('id');

        $this->applyEventFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * 이벤트 대시보드 요약 통계를 계산한다.
     *
     * @return array<string, int>
     */
    public function getSummaryStats(): array
    {
        $now = now();
        return [
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('start_at', '>', $now)->count(),
            'active_events' => Event::where(function ($query) use ($now) {
                    $query->whereNull('start_at')->orWhere('start_at', '<=', $now);
                })
                ->where(function ($query) use ($now) {
                    $query->whereNull('end_at')->orWhere('end_at', '>=', $now);
                })
                ->count(),
            'ended_events' => Event::whereNotNull('end_at')
                ->where('end_at', '<', $now)
                ->count(),
        ];
    }

    /**
     * 이벤트를 등록한다.
     *
     * @param  array<string, mixed>  $payload
     * @return Event
     */
    public function createEvent(array $payload): Event
    {
        if (isset($payload['banner_image'])) {
            $payload['banner_image_url'] = request()->file('banner_image')->store('events', 'public');
            unset($payload['banner_image']);
        }

        /** @var Event $event */
        $event = Event::query()->create($payload);

        return $event;
    }

    /**
     * 이벤트를 수정한다.
     *
     * @param  Event  $event
     * @param  array<string, mixed>  $payload
     * @return Event
     */
    public function updateEvent(Event $event, array $payload): Event
    {
        $winnerIds = $payload['winner_ids'] ?? [];
        unset($payload['winner_ids']);

        if (isset($payload['banner_image'])) {
            if ($event->banner_image_url) {
                \Storage::disk('public')->delete($event->banner_image_url);
            }
            $payload['banner_image_url'] = request()->file('banner_image')->store('events', 'public');
            unset($payload['banner_image']);
        }

        $event->update($payload);
        $event->winners()->sync($winnerIds);

        return $event->refresh();
    }

    /**
     * 이벤트를 soft delete 처리한다.
     *
     * @param  Event  $event
     * @return void
     */
    public function deleteEvent(Event $event): void
    {
        $event->delete();
    }

    /**
     * soft delete 이벤트를 복구한다.
     *
     * @param  Event  $event
     * @return bool
     */
    public function restoreEvent(Event $event): bool
    {
        if (! $event->trashed()) {
            return false;
        }

        return (bool) $event->restore();
    }

    /**
     * soft delete 이벤트를 영구 삭제한다.
     *
     * @param  Event  $event
     * @return bool
     */
    public function forceDeleteEvent(Event $event): bool
    {
        if (! $event->trashed()) {
            return false;
        }

        return (bool) $event->forceDelete();
    }

    /**
     * 이벤트 공통 필터를 적용한다.
     *
     * @param  Builder  $query
     * @param  array<string, mixed>  $filters
     * @return void
     */
    private function applyEventFilters(Builder $query, array $filters): void
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
            $now = now();
            $status = $filters['status'];

            if ($status === '진행예정') {
                $query->where('start_at', '>', $now);
            } elseif ($status === '진행중') {
                $query->where(function ($q) use ($now) {
                        $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                    });
            } elseif ($status === '종료') {
                $query->whereNotNull('end_at')->where('end_at', '<', $now);
            }
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['start_from'])) {
            $query->whereDate('start_at', '>=', $filters['start_from']);
        }

        if (! empty($filters['start_to'])) {
            $query->whereDate('start_at', '<=', $filters['start_to']);
        }
    }
}

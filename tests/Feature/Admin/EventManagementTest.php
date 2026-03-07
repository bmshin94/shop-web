<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_filter_events_by_search_and_status(): void
    {
        $matchedEvent = Event::factory()->create([
            'title' => 'Spring Coupon Event',
            'slug' => 'spring-coupon-event',
            'status' => '진행중',
        ]);

        $hiddenEvent = Event::factory()->create([
            'title' => 'Winter Event',
            'slug' => 'winter-event',
            'status' => '종료',
        ]);

        $response = $this->get(route('admin.events.index', [
            'search' => 'Spring Coupon',
            'status' => '진행중',
        ]));

        $response->assertOk();
        $response->assertSee($matchedEvent->title);
        $response->assertDontSee($hiddenEvent->title);
    }

    /** @test */
    public function admin_can_create_event_and_slug_is_auto_generated_when_empty(): void
    {
        $response = $this->post(route('admin.events.store'), [
            'title' => 'Spring Sale Event',
            'slug' => '',
            'status' => '진행예정',
            'banner_image_url' => 'https://example.com/banner.jpg',
            'summary' => 'summary',
            'description' => 'description',
            'start_at' => now()->toDateTimeString(),
            'end_at' => now()->addDays(3)->toDateTimeString(),
            'sort_order' => 5,
        ]);

        $event = Event::query()->where('title', 'Spring Sale Event')->first();

        $this->assertNotNull($event);
        $this->assertSame('spring-sale-event', $event->slug);
        $response->assertRedirect(route('admin.events.edit', $event));
    }

    /** @test */
    public function admin_can_update_event(): void
    {
        $event = Event::factory()->create([
            'title' => 'Old Event Title',
            'slug' => 'old-event-title',
            'status' => '진행예정',
        ]);

        $response = $this->put(route('admin.events.update', $event), [
            'title' => 'Updated Event Title',
            'slug' => 'updated-event-title',
            'status' => '진행중',
            'banner_image_url' => 'https://example.com/new-banner.jpg',
            'summary' => 'new summary',
            'description' => 'new description',
            'start_at' => now()->addDay()->toDateTimeString(),
            'end_at' => now()->addDays(10)->toDateTimeString(),
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.events.edit', $event));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Event Title',
            'slug' => 'updated-event-title',
            'status' => '진행중',
            'sort_order' => 1,
        ]);
    }

    /** @test */
    public function admin_can_soft_delete_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->delete(route('admin.events.destroy', $event));

        $response->assertRedirect(route('admin.events.index'));
        $this->assertSoftDeleted('events', [
            'id' => $event->id,
        ]);
        $this->assertNull(Event::query()->find($event->id));
        $this->assertNotNull(Event::query()->withTrashed()->find($event->id));
    }

    /** @test */
    public function trash_page_only_shows_soft_deleted_events(): void
    {
        $activeEvent = Event::factory()->create([
            'title' => 'Active Event',
        ]);

        $trashedEvent = Event::factory()->create([
            'title' => 'Trashed Event',
        ]);
        $trashedEvent->delete();

        $response = $this->get(route('admin.events.trash'));

        $response->assertOk();
        $response->assertSee($trashedEvent->title);
        $response->assertDontSee($activeEvent->title);
    }

    /** @test */
    public function admin_can_restore_soft_deleted_event(): void
    {
        $event = Event::factory()->create();
        $event->delete();

        $response = $this->patch(route('admin.events.restore', $event));

        $response->assertRedirect(route('admin.events.trash'));
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function admin_can_force_delete_soft_deleted_event(): void
    {
        $event = Event::factory()->create();
        $event->delete();

        $response = $this->delete(route('admin.events.force-destroy', $event));

        $response->assertRedirect(route('admin.events.trash'));
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
        $this->assertNull(Event::query()->withTrashed()->find($event->id));
    }
}

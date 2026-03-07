<?php

namespace Tests\Feature\Admin;

use App\Models\Exhibition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExhibitionManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_filter_exhibitions_by_search_and_status(): void
    {
        $matchedExhibition = Exhibition::factory()->create([
            'title' => 'Spring Capsule Exhibition',
            'slug' => 'spring-capsule-exhibition',
            'status' => '진행중',
        ]);

        $hiddenExhibition = Exhibition::factory()->create([
            'title' => 'Winter Archive Exhibition',
            'slug' => 'winter-archive-exhibition',
            'status' => '종료',
        ]);

        $response = $this->get(route('admin.exhibitions.index', [
            'search' => 'Spring Capsule',
            'status' => '진행중',
        ]));

        $response->assertOk();
        $response->assertSee($matchedExhibition->title);
        $response->assertDontSee($hiddenExhibition->title);
    }

    /** @test */
    public function admin_can_create_exhibition_and_slug_is_auto_generated_when_empty(): void
    {
        $response = $this->post(route('admin.exhibitions.store'), [
            'title' => 'Spring Lifestyle Exhibition',
            'slug' => '',
            'status' => '진행예정',
            'banner_image_url' => 'https://example.com/banner.jpg',
            'summary' => 'summary',
            'description' => 'description',
            'start_at' => now()->toDateTimeString(),
            'end_at' => now()->addDays(3)->toDateTimeString(),
            'sort_order' => 5,
        ]);

        $exhibition = Exhibition::query()->where('title', 'Spring Lifestyle Exhibition')->first();

        $this->assertNotNull($exhibition);
        $this->assertSame('spring-lifestyle-exhibition', $exhibition->slug);
        $response->assertRedirect(route('admin.exhibitions.edit', $exhibition));
    }

    /** @test */
    public function admin_can_update_exhibition(): void
    {
        $exhibition = Exhibition::factory()->create([
            'title' => 'Old Exhibition Title',
            'slug' => 'old-exhibition-title',
            'status' => '진행예정',
        ]);

        $response = $this->put(route('admin.exhibitions.update', $exhibition), [
            'title' => 'Updated Exhibition Title',
            'slug' => 'updated-exhibition-title',
            'status' => '진행중',
            'banner_image_url' => 'https://example.com/new-banner.jpg',
            'summary' => 'new summary',
            'description' => 'new description',
            'start_at' => now()->addDay()->toDateTimeString(),
            'end_at' => now()->addDays(10)->toDateTimeString(),
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.exhibitions.edit', $exhibition));
        $this->assertDatabaseHas('exhibitions', [
            'id' => $exhibition->id,
            'title' => 'Updated Exhibition Title',
            'slug' => 'updated-exhibition-title',
            'status' => '진행중',
            'sort_order' => 1,
        ]);
    }

    /** @test */
    public function admin_can_soft_delete_exhibition(): void
    {
        $exhibition = Exhibition::factory()->create();

        $response = $this->delete(route('admin.exhibitions.destroy', $exhibition));

        $response->assertRedirect(route('admin.exhibitions.index'));
        $this->assertSoftDeleted('exhibitions', [
            'id' => $exhibition->id,
        ]);
        $this->assertNull(Exhibition::query()->find($exhibition->id));
        $this->assertNotNull(Exhibition::query()->withTrashed()->find($exhibition->id));
    }

    /** @test */
    public function trash_page_only_shows_soft_deleted_exhibitions(): void
    {
        $activeExhibition = Exhibition::factory()->create([
            'title' => 'Active Exhibition',
        ]);

        $trashedExhibition = Exhibition::factory()->create([
            'title' => 'Trashed Exhibition',
        ]);
        $trashedExhibition->delete();

        $response = $this->get(route('admin.exhibitions.trash'));

        $response->assertOk();
        $response->assertSee($trashedExhibition->title);
        $response->assertDontSee($activeExhibition->title);
    }

    /** @test */
    public function admin_can_restore_soft_deleted_exhibition(): void
    {
        $exhibition = Exhibition::factory()->create();
        $exhibition->delete();

        $response = $this->patch(route('admin.exhibitions.restore', $exhibition));

        $response->assertRedirect(route('admin.exhibitions.trash'));
        $this->assertDatabaseHas('exhibitions', [
            'id' => $exhibition->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function admin_can_force_delete_soft_deleted_exhibition(): void
    {
        $exhibition = Exhibition::factory()->create();
        $exhibition->delete();

        $response = $this->delete(route('admin.exhibitions.force-destroy', $exhibition));

        $response->assertRedirect(route('admin.exhibitions.trash'));
        $this->assertDatabaseMissing('exhibitions', [
            'id' => $exhibition->id,
        ]);
        $this->assertNull(Exhibition::query()->withTrashed()->find($exhibition->id));
    }
}

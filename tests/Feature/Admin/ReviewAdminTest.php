<?php

namespace Tests\Feature\Admin;

use App\Models\Member;
use App\Models\Operator;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Operator::factory()->create();

        // 🌟 레이아웃 렌더링에 필요한 메뉴 데이터 생성! 🧩
        \App\Models\AdminMenu::create([
            'name' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'dashboard',
            'is_active' => true,
            'sort_order' => 1
        ]);
    }

    /** @test */
    public function admin_can_view_reviews_index()
    {
        $member = Member::factory()->create();
        $product = Product::factory()->create();
        Review::factory()->create([
            'member_id' => $member->id,
            'product_id' => $product->id,
            'title' => '너무 좋아요! ✨'
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reviews.index'));

        $response->assertStatus(200);
        $response->assertSee('너무 좋아요!');
        $response->assertSee($product->name);
        $response->assertSee($member->name);
    }

    /** @test */
    public function admin_can_view_review_detail_via_ajax()
    {
        $review = Review::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->getJson(route('admin.reviews.show', $review->id));

        $response->assertStatus(200)
            ->assertJsonPath('id', $review->id)
            ->assertJsonPath('title', $review->title);
    }

    /** @test */
    public function admin_can_delete_a_review()
    {
        $review = Review::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.reviews.destroy', $review->id));

        $response->assertStatus(302);
        
        // 🌟 Soft Delete 되었는지 확인! ✨🧹
        $this->assertSoftDeleted('reviews', ['id' => $review->id]);
    }
}

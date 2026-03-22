<?php

namespace Tests\Feature\Admin;

use App\Models\Size;
use App\Models\SizeGroup;
use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSizeTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Operator::factory()->create();
    }

    /**
     * 사이즈 관리 페이지 접근 테스트
     */
    public function test_can_access_size_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.sizes.index'));

        $response->assertStatus(200);
    }

    /**
     * 사이즈 그룹 등록 테스트
     */
    public function test_can_create_size_group(): void
    {
        $data = ['name' => '테스트 그룹'];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.sizes.groups.store'), $data);

        $response->assertRedirect(route('admin.sizes.index'));
        $this->assertDatabaseHas('size_groups', $data);
    }

    /**
     * 사이즈 등록 테스트
     */
    public function test_can_create_size(): void
    {
        $group = SizeGroup::firstOrCreate(['name' => '의류']);
        $data = [
            'size_group_id' => $group->id,
            'name' => 'XXL-Test',
            'sort_order' => 10
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.sizes.store'), $data);

        $response->assertRedirect(route('admin.sizes.index'));
        $this->assertDatabaseHas('sizes', $data);
    }

    /**
     * 사이즈 삭제 테스트
     */
    public function test_can_delete_size(): void
    {
        $group = SizeGroup::firstOrCreate(['name' => '의류']);
        $size = Size::create([
            'size_group_id' => $group->id,
            'name' => 'DeleteMeNow',
            'sort_order' => 1
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.sizes.destroy', $size));

        $response->assertRedirect(route('admin.sizes.index'));
        $this->assertDatabaseMissing('sizes', ['id' => $size->id]);
    }
}

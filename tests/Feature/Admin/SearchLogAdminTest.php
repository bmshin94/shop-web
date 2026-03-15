<?php

namespace Tests\Feature\Admin;

use App\Models\Operator;
use App\Models\SearchLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchLogAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // 테스트용 관리자 생성 
        $this->admin = Operator::factory()->create();

        //  레이아웃 렌더링에 필요한 메뉴 데이터 생성! 
        \App\Models\AdminMenu::create([
            'name' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'dashboard',
            'is_active' => true,
            'sort_order' => 1
        ]);
    }

    /** @test */
    public function admin_can_view_search_logs_index()
    {
        $this->withoutExceptionHandling(); // 에러 내용 다 보여줘! ️‍️
        
        // 1. 검색 로그 데이터 생성 
        SearchLog::create(['keyword' => '레깅스', 'ip_address' => '127.0.0.1']);
        SearchLog::create(['keyword' => '브라탑', 'ip_address' => '127.0.0.1']);

        // 2. 관리자로 로그인하여 접근! 
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.search-logs.index'));

        $response->assertStatus(200);
        $response->assertSee('레깅스');
        $response->assertSee('브라탑');
        $response->assertSee('인기 검색어 순위');
    }

    /** @test */
    public function admin_can_delete_a_search_log()
    {
        $log = SearchLog::create(['keyword' => '지울거야', 'ip_address' => '127.0.0.1']);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete(route('admin.search-logs.destroy', $log->id));

        $response->assertStatus(302); // back() 리다이렉트
        $this->assertDatabaseMissing('search_logs', ['id' => $log->id]);
    }

    /** @test */
    public function admin_can_clear_all_search_logs()
    {
        SearchLog::create(['keyword' => '로그1', 'ip_address' => '127.0.0.1']);
        SearchLog::create(['keyword' => '로그2', 'ip_address' => '127.0.0.1']);

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.search-logs.clear'));

        $response->assertStatus(302);
        $this->assertEquals(0, SearchLog::count()); // 싹 비워졌는지 확인! 
    }
}

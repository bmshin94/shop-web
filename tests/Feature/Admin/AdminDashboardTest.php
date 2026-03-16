<?php

namespace Tests\Feature\Admin;

use App\Models\Operator;
use App\Models\Order;
use App\Models\Member;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 관리자 대시보드 페이지 로드 테스트
     */
    public function test_admin_dashboard_can_be_rendered(): void
    {
        // 1. 관리자 계정 생성 및 로그인
        $admin = Operator::factory()->create([
            'status' => '활성'
        ]);

        // 2. 테스트용 데이터 생성 (매출 집계용)
        // 기존 데이터를 싹 비우고 시작! (RefreshDatabase가 있으니 괜찮아 ✨)
        Order::query()->delete();
        Member::query()->delete();
        Inquiry::query()->delete();

        Order::factory()->count(3)->create([
            'payment_status' => '결제완료',
            'order_status' => '주문접수',
            'total_amount' => 50000,
            'created_at' => now(),
            'ordered_at' => now(),
        ]);

        Member::factory()->count(5)->create([
            'created_at' => now()
        ]);

        Inquiry::factory()->count(2)->create([
            'answered_at' => null,
            'created_at' => now()
        ]);

        // 3. 대시보드 페이지 접속 
        // 팩토리가 Inquiry 등을 만들면서 추가로 생성한 회원까지 포함해서 카운트! 🔍
        $expectedMemberCount = Member::where('created_at', '>=', now()->startOfDay())->count();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'));

        // 4. 검증 
        $response->assertStatus(200);
        
        $stats = $response->viewData('stats');
        $this->assertEquals(150000, $stats['today_sales']);
        $this->assertEquals(3, $stats['today_orders']);
        $this->assertEquals($expectedMemberCount, $stats['new_members']);
        $this->assertEquals(2, $stats['pending_qna']);

        // 텍스트 확인 
        $response->assertSee('150,000');
        $response->assertSee('3건');
        $response->assertSee($expectedMemberCount . '명');
        $response->assertSee('2건');
    }

    /**
     * 권한 없는 사용자의 대시보드 접근 차단 테스트
     */
    public function test_unauthorized_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        // 비로그인 시 로그인 페이지로 리다이렉트 되는지 확인! 🔒
        $response->assertRedirect(route('admin.login'));
    }
}

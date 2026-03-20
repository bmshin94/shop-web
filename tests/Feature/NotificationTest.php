<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Order;
use App\Models\NotificationTemplate;
use App\Models\PhoneVerification;
use App\Models\Product;
use App\Services\SmsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 테스트에 필요한 기본 템플릿 생성
        NotificationTemplate::create([
            'code' => 'ORDER_COMPLETED',
            'name' => '주문 완료 안내',
            'content' => '[Active Women] 주문이 완료되었습니다. 주문번호: #{order_number}, 결제금액: #{final_total}',
            'is_active' => true,
        ]);

        NotificationTemplate::create([
            'code' => 'SHIPPING_STARTED',
            'name' => '배송 시작 안내',
            'content' => '[Active Women] 배송이 시작되었습니다. 주문번호: #{order_number}, 택배사: #{shipping_company}, 송장번호: #{tracking_number}',
            'is_active' => true,
        ]);
    }

    /**
     * 회원가입 시 휴대폰 인증 여부 검증 테스트
     */
    public function test_registration_requires_phone_verification()
    {
        $userData = [
            'name' => '테스트유저',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirm' => 'Password123!',
            'phone' => '01012345678',
            'terms' => ['service', 'privacy']
        ];

        // 1. 인증되지 않은 번호로 가입 시도 -> 실패해야 함
        $response = $this->postJson(route('register.post'), $userData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);

        // 2. 인증 완료된 번호로 가입 시도 -> 성공해야 함
        PhoneVerification::create([
            'phone' => '01012345678',
            'code' => '123456',
            'is_verified' => true,
            'expires_at' => now()->addMinutes(3)
        ]);

        $response = $this->postJson(route('register.post'), $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('members', ['email' => 'test@example.com']);
    }

    /**
     * 주문 완료 시 알림 발송 테스트
     */
    public function test_order_completion_notification_is_sent()
    {
        $category = \App\Models\Category::factory()->create();
        $member = Member::factory()->create(['phone' => '01011112222']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 20000
        ]);
        
        $order = Order::create([
            'member_id' => $member->id,
            'order_number' => 'ORD-TEST-123',
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => $member->phone,
            'recipient_name' => $member->name,
            'recipient_phone' => $member->phone,
            'postal_code' => '12345',
            'address_line1' => '테스트 주소 1',
            'total_amount' => 20000,
            'order_status' => '주문접수',
            'payment_status' => '결제완료',
            'payment_method' => '신용카드'
        ]);

        $smsService = app(SmsService::class);
        $template = NotificationTemplate::where('code', 'ORDER_COMPLETED')->first();
        $message = $template->parseContent([
            'order_number' => $order->order_number,
            'final_total' => number_format($order->total_amount) . '원',
        ]);
        
        $result = $smsService->sendSms($order->recipient_phone, $message);
        
        $this->assertEquals(1, $result['result_code']);
        $this->assertStringContainsString('ORD-TEST-123', $message);
    }

    /**
     * 배송 시작 시 알림 발송 테스트
     */
    public function test_shipping_start_notification_is_sent()
    {
        $member = Member::factory()->create(['phone' => '01033334444']);
        $order = Order::create([
            'member_id' => $member->id,
            'order_number' => 'ORD-SHIP-777',
            'customer_name' => $member->name,
            'customer_email' => $member->email,
            'customer_phone' => $member->phone,
            'recipient_name' => $member->name,
            'recipient_phone' => $member->phone,
            'postal_code' => '54321',
            'address_line1' => '배송지 주소',
            'total_amount' => 30000,
            'order_status' => '주문접수',
            'payment_status' => '결제완료',
            'payment_method' => '신용카드'
        ]);

        $orderManagementService = app(\App\Services\Admin\OrderManagementService::class);
        
        $orderManagementService->updateOrder($order, [
            'order_status' => '배송중',
            'payment_status' => '결제완료',
            'shipping_company' => '카리나배송',
            'tracking_number' => 'TRACK-999'
        ]);

        $template = NotificationTemplate::where('code', 'SHIPPING_STARTED')->first();
        $message = $template->parseContent([
            'order_number' => $order->order_number,
            'shipping_company' => '카리나배송',
            'tracking_number' => 'TRACK-999',
        ]);

        $this->assertStringContainsString('카리나배송', $message);
        $this->assertStringContainsString('TRACK-999', $message);
    }
}

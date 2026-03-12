<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use DatabaseTransactions;

    protected $member;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::factory()->create();
        $this->product = Product::factory()->create([
            'price' => 100000,
            'sale_price' => 80000,
            'status' => '판매중',
        ]);
    }

    /** @test */
    public function member_can_initiate_buy_now()
    {
        $this->actingAs($this->member);

        $response = $this->postJson(route('buy-now'), [
            'product_id' => $this->product->id,
            'color' => 'Black',
            'size' => 'M',
            'quantity' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('checkout'),
            ]);

        $this->assertEquals($this->product->id, session('buy_now.product_id'));
        $this->assertEquals('Black', session('buy_now.color'));
        $this->assertEquals(2, session('buy_now.quantity'));
    }

    /** @test */
    public function member_can_access_checkout_page_with_buy_now_item()
    {
        $this->actingAs($this->member);

        // 세션에 바로구매 정보 주입
        session(['buy_now' => [
            'product_id' => $this->product->id,
            'color' => 'White',
            'size' => 'S',
            'quantity' => 1,
        ]]);

        $response = $this->get(route('checkout'));

        $response->assertStatus(200)
            ->assertSee($this->product->name)
            ->assertSee('주문/결제');
    }

    /** @test */
    public function guest_cannot_access_checkout_page()
    {
        $response = $this->get(route('checkout'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function member_can_complete_checkout_successfully()
    {
        // 아임포트 연동으로 인해 실제 HTTP 통신이 발생하므로
        // 이 테스트는 Http Facade Mocking을 사용해 타임아웃 없이 성공을 반환하도록 설정합니다.
        \Illuminate\Support\Facades\Http::fake([
            'api.iamport.kr/users/getToken' => \Illuminate\Support\Facades\Http::response(['code' => 0, 'response' => ['access_token' => 'fake_token']], 200),
            'api.iamport.kr/payments/imp_1234567890' => \Illuminate\Support\Facades\Http::response(['code' => 0, 'response' => ['amount' => 80000]], 200),
        ]);

        $this->actingAs($this->member);

        // 세션에 바로구매 정보 주입
        session(['buy_now' => [
            'product_id' => $this->product->id,
            'color' => 'White',
            'size' => 'S',
            'quantity' => 1,
        ]]);

        $payload = [
            'imp_uid' => 'imp_1234567890',
            'merchant_uid' => 'ACT_' . time(),
            'recipient_name' => '홍길동',
            'recipient_phone' => '010-1234-5678',
            'recipient_zipcode' => '12345',
            'recipient_address' => '서울시 강남구',
            'recipient_detail_address' => '101동 101호',
            'shipping_message' => '문 앞에 두고 가세요',
            'payment_method' => 'card',
            'applied_points' => 0,
        ];

        $response = $this->postJson(route('checkout.verify'), $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('mypage.order-list'),
            ]);

        $this->assertDatabaseHas('orders', [
            'member_id' => $this->member->id,
            'recipient_name' => '홍길동',
            'payment_method' => '신용카드',
            'imp_uid' => 'imp_1234567890',
            'total_amount' => 80000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'unit_price' => 80000,
            'quantity' => 1,
        ]);

        $this->assertNull(session('buy_now'));
    }

    /** @test */
    public function checkout_fails_with_invalid_data()
    {
        $this->actingAs($this->member);

        session(['buy_now' => [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]]);

        $payload = [
            // 필수 필드 확인
            'recipient_name' => '',
            'payment_method' => '',
        ];

        $response = $this->postJson(route('checkout.verify'), $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['imp_uid', 'merchant_uid', 'recipient_name', 'recipient_phone', 'recipient_zipcode', 'recipient_address', 'payment_method']);
    }
}

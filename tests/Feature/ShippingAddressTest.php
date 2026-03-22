<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\ShippingAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        // 테스트용 회원 생성
        $this->member = Member::factory()->create();
    }

    /**
     * 배송지 목록 조회 테스트
     */
    public function test_member_can_view_shipping_address_list()
    {
        ShippingAddress::factory()->count(3)->create(['member_id' => $this->member->id]);

        $response = $this->actingAs($this->member)
            ->get(route('mypage.shipping-address'));

        $response->assertStatus(200);
        $response->assertViewHas('addresses');
    }

    /**
     * 배송지 등록 테스트
     */
    public function test_member_can_create_new_shipping_address()
    {
        $addressData = [
            'address_name' => '우리집',
            'recipient_name' => '카리나',
            'phone_number' => '010-1234-5678',
            'zip_code' => '12345',
            'address' => '서울시 강남구',
            'address_detail' => '에스파 빌딩 101호',
            'is_default' => 1
        ];

        $response = $this->actingAs($this->member)
            ->post(route('mypage.shipping-address.store'), $addressData);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('shipping_addresses', [
            'member_id' => $this->member->id,
            'address_name' => '우리집',
            'is_default' => 1
        ]);
    }

    /**
     * 기본 배송지 자동 전환 테스트
     */
    public function test_setting_new_default_address_clears_old_one()
    {
        // 1. 기존 기본 배송지 생성
        $oldDefault = ShippingAddress::factory()->create([
            'member_id' => $this->member->id,
            'is_default' => true,
            'address_name' => '옛날 집'
        ]);

        // 2. 새로운 기본 배송지 등록
        $newAddressData = [
            'address_name' => '새집',
            'recipient_name' => '카리나',
            'phone_number' => '010-9999-9999',
            'zip_code' => '54321',
            'address' => '서울시 성수동',
            'address_detail' => 'SM 사옥',
            'is_default' => 1
        ];

        $this->actingAs($this->member)
            ->post(route('mypage.shipping-address.store'), $newAddressData);

        // 3. 기존 게 해제됐는지 확인! (스마트한 로직 체크 ✅)
        $oldDefault->refresh();
        $this->assertFalse($oldDefault->is_default);

        $this->assertDatabaseHas('shipping_addresses', [
            'member_id' => $this->member->id,
            'address_name' => '새집',
            'is_default' => 1
        ]);
    }

    /**
     * 기본 배송지 삭제 차단 테스트
     */
    public function test_member_cannot_delete_default_address()
    {
        $defaultAddress = ShippingAddress::factory()->create([
            'member_id' => $this->member->id,
            'is_default' => true
        ]);

        $response = $this->actingAs($this->member)
            ->delete(route('mypage.shipping-address.destroy', $defaultAddress->id));

        $response->assertStatus(400); // 400 Bad Request 에러 확인!
        $this->assertDatabaseHas('shipping_addresses', ['id' => $defaultAddress->id]);
    }

    /**
     * 일반 배송지 삭제 테스트
     */
    public function test_member_can_delete_normal_address()
    {
        $normalAddress = ShippingAddress::factory()->create([
            'member_id' => $this->member->id,
            'is_default' => false
        ]);

        $response = $this->actingAs($this->member)
            ->delete(route('mypage.shipping-address.destroy', $normalAddress->id));

        $response->assertStatus(200);
        $this->assertSoftDeleted('shipping_addresses', ['id' => $normalAddress->id]);
    }
}

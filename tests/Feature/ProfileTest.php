<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        // 테스트용 회원 생성 (비밀번호: password) 
        $this->member = Member::factory()->create([
            'password' => Hash::make('password')
        ]);
    }

    /** @test */
    public function member_must_confirm_password_before_editing_profile()
    {
        $this->actingAs($this->member);

        // 확인 없이 바로 수정 페이지 접속 시도 ️‍️
        $response = $this->get(route('mypage.profile-edit'));

        // 비밀번호 확인 페이지로 튕겨야 해! 
        $response->assertRedirect(route('mypage.profile'));
    }

    /** @test */
    public function member_can_confirm_their_password()
    {
        $this->actingAs($this->member);

        $response = $this->postJson(route('mypage.profile.confirm'), [
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        
        $this->assertTrue(session()->has('auth.password_confirmed_at'));
    }

    /** @test */
    public function member_can_update_their_profile_info()
    {
        $this->actingAs($this->member);
        
        // 먼저 비밀번호 확인 통과! 
        session()->put('auth.password_confirmed_at', time());

        $newData = [
            'phone' => '010-9999-8888',
            'postal_code' => '54321',
            'address_line1' => '서울시 강남구 삼성동',
            'address_line2' => '어느 멋진 아파트 101호',
            'marketing_sms' => true,
            'marketing_email' => false,
        ];

        $response = $this->patchJson(route('mypage.profile.update'), $newData);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('members', [
            'id' => $this->member->id,
            'phone' => '010-9999-8888',
            'postal_code' => '54321',
            'address_line1' => '서울시 강남구 삼성동',
        ]);
    }

    /** @test */
    public function member_can_change_their_password()
    {
        $this->actingAs($this->member);
        session()->put('auth.password_confirmed_at', time());

        $response = $this->patchJson(route('mypage.profile.update'), [
            'phone' => $this->member->phone,
            'password' => 'new_password_123',
            'password_confirmation' => 'new_password_123'
        ]);

        $response->assertStatus(200);

        // 새 비밀번호로 로그인이 되는지 확인! 
        $this->assertTrue(Hash::check('new_password_123', $this->member->fresh()->password));
    }

    /** @test */
    public function guest_cannot_access_profile_pages()
    {
        // 1. 확인 페이지
        $this->get(route('mypage.profile'))->assertRedirect(route('login'));
        
        // 2. 수정 페이지
        $this->get(route('mypage.profile-edit'))->assertRedirect(route('login'));
    }
}

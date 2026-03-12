<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    protected $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::factory()->create();
    }

    /** @test */
    public function member_can_view_their_inquiry_list()
    {
        $this->withoutExceptionHandling(); // 에러 내용 상세 출력! ✨
        $this->actingAs($this->member);

        // 문의 3개 생성
        Inquiry::factory()->count(3)->create([
            'member_id' => $this->member->id
        ]);

        $response = $this->get(route('mypage.inquiry'));

        $response->assertStatus(200);
        $this->assertCount(3, $response->viewData('inquiries'));
    }

    /** @test */
    public function member_can_register_new_inquiry()
    {
        $this->actingAs($this->member);

        $data = [
            'title' => '배송 문의드립니다.',
            'content' => '언제쯤 배송이 시작될까요? 궁금해요! ✨'
        ];

        $response = $this->postJson(route('mypage.inquiry.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('inquiries', [
            'member_id' => $this->member->id,
            'title' => '배송 문의드립니다.',
            'status' => '답변대기'
        ]);
    }

    /** @test */
    public function inquiry_list_is_paginated()
    {
        $this->actingAs($this->member);

        // 문의 15개 생성 (페이징 10개 기준)
        Inquiry::factory()->count(15)->create([
            'member_id' => $this->member->id
        ]);

        $response = $this->get(route('mypage.inquiry'));

        $response->assertStatus(200);
        $this->assertEquals(10, $response->viewData('inquiries')->count());
        $this->assertEquals(15, $response->viewData('inquiries')->total());
    }

    /** @test */
    public function guest_cannot_access_inquiry_page()
    {
        $response = $this->get(route('mypage.inquiry'));
        $response->assertRedirect(route('login'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    protected $member;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::factory()->create();
        $this->product = Product::factory()->create();
    }

    /** @test */
    public function other_members_cannot_see_private_inquiry_content()
    {
        // 🌟 타인이 비밀글 내용을 볼 수 없는지 테스트! 🕵️‍♀️🔒
        $owner = Member::factory()->create();
        $inquiry = Inquiry::factory()->create([
            'member_id' => $owner->id,
            'product_id' => $this->product->id,
            'is_private' => true,
            'title' => '나만의 비밀 상담',
            'content' => '이건 아무도 모르게 해줘요! 🤫'
        ]);

        $this->actingAs($this->member); // 다른 사용자로 로그인! 😊

        $response = $this->get(route('product-detail', $this->product->slug));

        $response->assertStatus(200);
        $response->assertSee('비밀글입니다');
        $response->assertDontSee('이건 아무도 모르게 해줘요!'); // 내용이 숨겨졌는지 확인! ✅
    }

    /** @test */
    public function owner_can_see_their_own_private_inquiry_content()
    {
        // 🌟 본인은 본인의 비밀글 내용을 볼 수 있는지 테스트! 😍✨
        $inquiry = Inquiry::factory()->create([
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'is_private' => true,
            'content' => '나만 볼 수 있는 비밀 내용! 💖'
        ]);

        $this->actingAs($this->member);

        $response = $this->get(route('product-detail', $this->product->slug));

        $response->assertStatus(200);
        $response->assertSee('나만 볼 수 있는 비밀 내용!'); // 본인은 내용 확인 가능! ✅
    }

    /** @test */
    public function member_can_view_their_inquiry_list()
    {
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
    public function member_can_register_product_inquiry()
    {
        // 🌟 상품 문의 등록 테스트! ✨💖
        $this->actingAs($this->member);

        $data = [
            'product_id' => $this->product->id, // 상품 ID 포함! 😊
            'title' => '사이즈 문의합니다.',
            'content' => '170cm에 60kg인데 어떤 사이즈가 좋을까요? 🤔'
        ];

        $response = $this->postJson(route('mypage.inquiry.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        // DB에 product_id와 함께 잘 들어갔는지 확인! 🎬🚀 ✅
        $this->assertDatabaseHas('inquiries', [
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'title' => '사이즈 문의합니다.',
        ]);
    }

    /** @test */
    public function product_detail_page_shows_product_inquiries()
    {
        // 🌟 상품 상세 페이지 Q&A 탭 데이터 확인 테스트! ✨🎬
        $this->actingAs($this->member);

        // 1. 이 상품에 달린 문의 2개 생성
        Inquiry::factory()->count(2)->create([
            'member_id' => $this->member->id,
            'product_id' => $this->product->id,
            'title' => '이 상품 질문이요!'
        ]);

        // 2. 다른 상품에 달린 문의 1개 생성 (안 보여야 해! 😉)
        $otherProduct = Product::factory()->create();
        Inquiry::factory()->create([
            'member_id' => $this->member->id,
            'product_id' => $otherProduct->id,
            'title' => '다른 상품 질문!'
        ]);

        $response = $this->get(route('product-detail', $this->product->slug));

        $response->assertStatus(200);
        
        // 해당 상품의 문의만 노출되는지 확인! ✨🎬🚀 ✅
        $response->assertSee('이 상품 질문이요!');
        $response->assertDontSee('다른 상품 질문!');

        // 🌟 탭 메뉴에 갯수가 잘 나오는지 확인! (2개여야 함! 😉)
        $this->assertEquals(2, $response->viewData('product')->inquiries->count());
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
    public function member_can_update_their_own_inquiry()
    {
        // 🌟 본인 문의 수정 테스트! ✨🎬
        $this->actingAs($this->member);

        $inquiry = Inquiry::factory()->create([
            'member_id' => $this->member->id,
            'title' => '수정 전 제목'
        ]);

        $data = [
            'title' => '수정 후 제목',
            'content' => '내용도 예쁘게 고쳐봤어! ✨'
        ];

        $response = $this->postJson(route('qna.update', $inquiry->id), $data);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('inquiries', [
            'id' => $inquiry->id,
            'title' => '수정 후 제목'
        ]);
    }

    /** @test */
    public function member_cannot_update_others_inquiry()
    {
        // 🌟 타인 문의 수정 시도 차단 테스트! 🕵️‍♀️🔒
        $this->actingAs($this->member);

        $otherMember = Member::factory()->create();
        $inquiry = Inquiry::factory()->create([
            'member_id' => $otherMember->id,
            'title' => '다른 사람 글'
        ]);

        $response = $this->postJson(route('qna.update', $inquiry->id), [
            'title' => '해킹 시도?!'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function member_can_delete_their_own_inquiry()
    {
        // 🌟 본인 문의 삭제 테스트! 🧹✨
        $this->actingAs($this->member);

        $inquiry = Inquiry::factory()->create([
            'member_id' => $this->member->id
        ]);

        $response = $this->deleteJson(route('qna.destroy', $inquiry->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('inquiries', ['id' => $inquiry->id]);
    }

    /** @test */
    public function guest_cannot_access_inquiry_page()
    {
        $response = $this->get(route('mypage.inquiry'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_cannot_access_qna_write_page()
    {
        // 🌟 로그인을 안 한 게스트가 Q&A 작성 페이지에 접근할 때! ✨🔒
        $response = $this->get(route('qna.write'));
        
        // 로그인 페이지로 리다이렉트되어야 함! 🚫
        $response->assertRedirect(route('login'));
    }
}

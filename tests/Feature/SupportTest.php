<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Notice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * FAQ 목록 페이지 접근 테스트
     */
    public function test_faq_index_page_display(): void
    {
        // 1. 샘플 FAQ 생성 
        Faq::create([
            'category' => 'member',
            'question' => '회원 탈퇴는 어떻게 하나요?',
            'answer' => '마이페이지에서 가능합니다.',
            'is_visible' => true,
        ]);

        // 2. 접근 및 확인 
        $response = $this->get(route('support'));

        $response->assertStatus(200);
        $response->assertSee('회원 탈퇴는 어떻게 하나요?');
    }

    /**
     * FAQ 카테고리 필터링 테스트 
     */
    public function test_faq_category_filtering(): void
    {
        Faq::create(['category' => 'member', 'question' => '가입 질문', 'answer' => '답변', 'is_visible' => true]);
        Faq::create(['category' => 'order', 'question' => '주문 질문', 'answer' => '답변', 'is_visible' => true]);

        // 가입 카테고리만 요청! 
        $response = $this->get(route('support', ['category' => 'member']));

        $response->assertStatus(200);
        $response->assertSee('가입 질문');
        $response->assertDontSee('주문 질문');
    }

    /**
     * FAQ 검색 기능 테스트 
     */
    public function test_faq_search_functionality(): void
    {
        Faq::create(['category' => 'member', 'question' => '비밀번호 찾기', 'answer' => '답변', 'is_visible' => true]);
        Faq::create(['category' => 'delivery', 'question' => '배송 추적', 'answer' => '답변', 'is_visible' => true]);

        // '비밀번호'로 검색! 
        $response = $this->get(route('support', ['q' => '비밀번호']));

        $response->assertStatus(200);
        $response->assertSee('비밀번호 찾기');
        $response->assertDontSee('배송 추적');
    }

    /**
     * 고객센터 공지사항 목록 테스트 
     */
    public function test_support_notices_display(): void
    {
        Notice::create([
            'type' => '공지',
            'title' => '중요 공지사항입니다.',
            'content' => '내용',
            'is_visible' => true,
        ]);

        $response = $this->get(route('support.notice'));

        $response->assertStatus(200);
        $response->assertSee('중요 공지사항입니다.');
    }
}

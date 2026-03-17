<?php

namespace Tests\Feature\Admin;

use App\Models\Faq;
use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqAdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // 1. 관리자(Operator) 생성 
        $this->admin = Operator::factory()->create();
    }

    /**
     * 관리자 FAQ 등록 테스트 
     */
    public function test_admin_can_create_faq(): void
    {
        // 'admin' 가드를 명시해서 로그인! 
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.faqs.store'), [
            'category' => 'product',
            'question' => '새로운 질문?',
            'answer' => '새로운 답변입니다.',
            'is_visible' => 1,
            'sort_order' => 10,
        ]);

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', [
            'question' => '새로운 질문?',
            'category' => 'product',
        ]);
    }

    /**
     * 관리자 FAQ 수정 테스트 
     */
    public function test_admin_can_update_faq(): void
    {
        $faq = Faq::create([
            'category' => 'delivery',
            'question' => '기존 질문',
            'answer' => '기존 답변',
            'is_visible' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.faqs.update', $faq), [
            'category' => 'delivery',
            'question' => '수정된 질문',
            'answer' => '수정된 답변',
            'is_visible' => 1,
            'sort_order' => 20,
        ]);

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertEquals('수정된 질문', $faq->refresh()->question);
    }

    /**
     * 관리자 FAQ 삭제 테스트 
     */
    public function test_admin_can_delete_faq(): void
    {
        $faq = Faq::create([
            'category' => 'return',
            'question' => '지워질 질문',
            'answer' => '내용',
            'is_visible' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.faqs.destroy', $faq));

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertSoftDeleted($faq); // Soft Delete 확인! 
    }
}

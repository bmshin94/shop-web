<?php

namespace Tests\Feature;

use App\Models\Exhibition;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 기획전 목록 페이지가 정상적으로 노출되는지 테스트한다.
     */
    public function test_exhibition_list_page_is_accessible(): void
    {
        Exhibition::factory()->count(3)->create(['status' => '진행중']);

        $response = $this->get(route('exhibition.index'));

        $response->assertStatus(200);
        $response->assertSee('Season Exhibition');
    }

    /**
     * 페이징 처리가 정상적으로 작동하는지 테스트한다. 
     */
    public function test_exhibition_pagination_works(): void
    {
        Exhibition::factory()->count(15)->create(['status' => '진행중']);

        $response = $this->get(route('exhibition.index'));

        $response->assertStatus(200);
        $response->assertSee('page=2'); // 5개씩 페이징이므로 2페이지 링크가 있어야 함! 
    }

    /**
     * 기획전 상세 페이지가 정상적으로 노출되는지 테스트한다. 
     */
    public function test_exhibition_detail_page_is_accessible(): void
    {
        $exhibition = Exhibition::factory()->create([
            'title' => '스페셜 테스트 기획전',
            'status' => '진행중'
        ]);

        $response = $this->get(route('exhibition.show', $exhibition->slug));

        $response->assertStatus(200);
        $response->assertSee('스페셜 테스트 기획전');
        $response->assertSee('전체 기획전 목록');
    }

    /**
     * 비노출 상태의 기획전은 접근이 불가능해야 한다. 
     */
    public function test_hidden_exhibitions_are_not_displayed(): void
    {
        Exhibition::factory()->create([
            'title' => '비밀 기획전',
            'status' => '비노출'
        ]);

        $response = $this->get(route('exhibition.index'));
        $response->assertDontSee('비밀 기획전');
    }
}

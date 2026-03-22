<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 상품의 평균 별점과 리뷰 수가 정상적으로 계산되는지 테스트합니다.
     */
    public function test_product_review_stats_are_calculated_correctly(): void
    {
        // 1. 테스트 데이터 준비
        $product = Product::factory()->create();
        $members = Member::factory()->count(3)->create();

        // 2. 리뷰 생성 (4점, 5점, 3점 -> 평균 4.0)
        Review::create([
            'product_id' => $product->id,
            'member_id' => $members[0]->id,
            'rating' => 4,
            'title' => '좋아요 1',
            'content' => '정말 마음에 들어요! 품질이 아주 우수합니다.',
        ]);

        Review::create([
            'product_id' => $product->id,
            'member_id' => $members[1]->id,
            'rating' => 5,
            'title' => '최고예요 2',
            'content' => '배송도 빠르고 디자인도 예뻐요!',
        ]);

        Review::create([
            'product_id' => $product->id,
            'member_id' => $members[2]->id,
            'rating' => 3,
            'title' => '보통이에요 3',
            'content' => '그냥 그래요. 평범한 수준입니다.',
        ]);

        // 3. 모델의 Accessor 검증
        $this->assertEquals(3, $product->review_count);
        $this->assertEquals(4.0, $product->average_rating);
    }

    /**
     * 상품 상세 페이지에서 리뷰 정보가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_product_detail_page_shows_reviews_and_stats(): void
    {
        // 1. 테스트 데이터 준비
        $category = Category::factory()->create(['level' => 2]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => '테스트용 티셔츠',
            'slug' => 'test-tshirt-' . time(),
            'status' => '판매중',
        ]);
        $member = Member::factory()->create(['name' => '홍길동']);

        Review::create([
            'product_id' => $product->id,
            'member_id' => $member->id,
            'rating' => 5,
            'title' => '이것은 최고의 티셔츠입니다',
            'content' => '제가 입어본 것 중에 가장 편안해요. 추천합니다!',
        ]);

        // 2. 상품 상세 페이지 접속
        $response = $this->get(route('product-detail', ['slug' => $product->slug]));

        // 3. 검증
        $response->assertStatus(200);
        
        // 별점과 리뷰 수 텍스트 확인 (상단 영역)
        $response->assertSee('5.0');
        $response->assertSee('(리뷰 1건)');
        
        // 리뷰 목록 렌더링 확인
        $response->assertSee('이것은 최고의 티셔츠입니다');
        $response->assertSee('제가 입어본 것 중에 가장 편안해요.');
        $response->assertSee('홍**'); // Str::mask('홍길동', '*', 1) 결과 확인
    }

    /**
     * 리뷰가 5개 이상일 때 '더보기' 버튼이 표시되는지 테스트합니다.
     */
    public function test_load_more_button_shows_when_reviews_exceed_five(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => '판매중',
        ]);
        $member = Member::factory()->create();

        // 6개의 리뷰 생성
        for ($i = 0; $i < 6; $i++) {
            Review::create([
                'product_id' => $product->id,
                'member_id' => $member->id,
                'rating' => 5,
                'title' => "리뷰 $i",
                'content' => "내용 $i",
            ]);
        }

        $response = $this->get(route('product-detail', ['slug' => $product->slug]));

        $response->assertStatus(200);
        $response->assertSee('리뷰 더보기');
        $this->assertStringContainsString('review-item py-8 border-b border-gray-100 last:border-0 hidden', $response->getContent());
    }

    /**
     * 상품 상세 페이지에 '목록으로 돌아가기' 링크가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_product_detail_page_has_back_to_list_link(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => '판매중',
        ]);

        $response = $this->get(route('product-detail', ['slug' => $product->slug]));

        $response->assertStatus(200);
        $response->assertSee('목록으로 돌아가기');
        $response->assertSee(route('product-list', ['category' => $category->slug]));
    }

    /**
     * 상품 상세 페이지에서 사이즈 옵션이 정상적으로 표시되는지 테스트합니다.
     */
    public function test_product_detail_page_shows_sizes(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'status' => '판매중',
        ]);
        
        $size = \App\Models\Size::create(['name' => 'FreeSize', 'sort_order' => 1]);
        $product->sizes()->attach($size->id);

        $response = $this->get(route('product-detail', ['slug' => $product->slug]));

        $response->assertStatus(200);
        $response->assertSee('FreeSize');
    }
}

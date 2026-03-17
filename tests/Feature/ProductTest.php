<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 상품 목록 페이지가 정상적으로 표시되는지 테스트합니다.
     */
    public function test_product_list_page_is_accessible(): void
    {
        $response = $this->get('/product-list');

        $response->assertStatus(200);
        $response->assertViewIs('pages.product-list');
    }

    /**
     * 특정 카테고리로 필터링했을 때 해당 상품만 나오는지 테스트합니다.
     */
    public function test_product_list_can_be_filtered_by_category(): void
    {
        // 1. 카테고리 생성 (중복 방지를 위해 무작위 슬러그 사용)
        $parentCategory = Category::create([
            'name' => '테스트 스포츠웨어',
            'slug' => 'test-activewear-' . Str::random(5),
            'level' => 1,
            'is_active' => true,
        ]);

        $childCategory = Category::create([
            'name' => '테스트 상의',
            'slug' => 'test-tops-' . Str::random(5),
            'parent_id' => $parentCategory->id,
            'level' => 2,
            'is_active' => true,
        ]);

        $otherCategory = Category::create([
            'name' => '테스트 홈트용품',
            'slug' => 'test-home-props-' . Str::random(5),
            'level' => 1,
            'is_active' => true,
        ]);

        // 2. 상품 생성
        $productInTops = Product::factory()->create([
            'category_id' => $childCategory->id,
            'name' => '에어핏 티셔츠',
            'status' => '판매중',
        ]);

        $productInOther = Product::factory()->create([
            'category_id' => $otherCategory->id,
            'name' => '요가 매트',
            'status' => '판매중',
        ]);

        // 3. 테스트 실행: '상의' 카테고리 조회
        $response = $this->get('/product-list?category=' . $childCategory->slug);

        $response->assertStatus(200);
        $response->assertSee('에어핏 티셔츠');
        $response->assertDontSee('요가 매트');
    }

    /**
     * 상품과 함께 연결된 색상 정보가 정상적으로 로드되는지 테스트합니다.
     */
    public function test_product_list_includes_color_hex_codes(): void
    {
        // 1. 색상 생성
        $red = \App\Models\Color::create(['name' => '레드', 'hex_code' => '#FF0000']);
        $blue = \App\Models\Color::create(['name' => '블루', 'hex_code' => '#0000FF']);

        // 2. 상품 생성 및 색상 연결
        $product = Product::factory()->create(['status' => '판매중']);
        $product->colors()->attach([$red->id, $blue->id]);

        // 3. 테스트 실행
        $response = $this->get('/product-list');

        $response->assertStatus(200);
        // HEX 코드가 뷰에 렌더링되는지 확인 (배경색 스타일로 확인)
        $response->assertSee('background-color: #FF0000');
        $response->assertSee('background-color: #0000FF');
    }

    /**
     * 신상품 목록 페이지 테스트
     */
    public function test_new_arrivals_page_shows_new_products(): void
    {
        $newProduct = Product::factory()->create([
            'is_new' => true,
            'status' => '판매중',
            'name' => '따끈따끈한 신상',
        ]);

        $oldProduct = Product::factory()->create([
            'is_new' => false,
            'status' => '판매중',
            'name' => '오래된 상품',
        ]);

        $response = $this->get('/products/new');

        $response->assertStatus(200);
        $response->assertSee('따끈따끈한 신상');
        // '오래된 상품'은 보이지 않아야 함 (신상품 전용 페이지이므로)
        $response->assertDontSee('오래된 상품');
    }

    /**
     * 가격대 필터가 정상적으로 작동하는지 테스트합니다.
     */
    public function test_product_list_can_be_filtered_by_price_range(): void
    {
        // 1. 상품 생성 (다양한 가격대)
        Product::factory()->create(['name' => '저렴이', 'price' => 30000, 'status' => '판매중']);
        Product::factory()->create(['name' => '보통이', 'price' => 70000, 'status' => '판매중']);
        Product::factory()->create(['name' => '비싼이', 'price' => 150000, 'status' => '판매중']);

        // 2. 테스트 실행: 5만원 이하 조회
        $response = $this->get('/product-list?price_range[]=~ 5만원');
        $response->assertStatus(200);
        $response->assertSee('저렴이');
        $response->assertDontSee('보통이');
        $response->assertDontSee('비싼이');

        // 3. 테스트 실행: 5만원 ~ 10만원 조회
        $response = $this->get('/product-list?price_range[]=5만원 ~ 10만원');
        $response->assertStatus(200);
        $response->assertSee('보통이');
        $response->assertDontSee('저렴이');
        $response->assertDontSee('비싼이');
    }

    /**
     * 통합 검색 기능 테스트 
     */
    public function test_products_can_be_searched(): void
    {
        Product::factory()->create(['name' => '관리자 레깅스', 'status' => '판매중']);
        Product::factory()->create(['name' => 'Active Women 크롭탑', 'status' => '판매중']);
        Product::factory()->create(['name' => '평범한 바지', 'status' => '판매중']);

        // 1. 검색 실행! 
        $response = $this->get(route('products.search', ['q' => '관리자']));

        $response->assertStatus(200);
        $response->assertSee('관리자 레깅스');
        $response->assertDontSee('Active Women 크롭탑');
        $response->assertDontSee('평범한 바지');

        // 2. 검색 기록 확인! 
        $this->assertDatabaseHas('search_logs', [
            'keyword' => '관리자'
        ]);
    }

    /**
     * 실시간 검색 제안(Autocomplete) 테스트 
     */
    public function test_autocomplete_returns_suggestions(): void
    {
        Product::factory()->create(['name' => 'Active Women Top', 'status' => '판매중']);
        Product::factory()->create(['name' => 'Active Women Pants', 'status' => '판매중']);

        $response = $this->getJson(route('products.autocomplete', ['q' => 'Active']));

        $response->assertStatus(200)
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'Active Women Top'])
            ->assertJsonFragment(['name' => 'Active Women Pants']);
    }

    /**
     * 검색 결과 내 필터링 기능 테스트 
     */
    public function test_search_results_can_be_filtered_by_color(): void
    {
        // 1. 데이터 준비 
        $black = \App\Models\Color::create(['name' => '블랙', 'hex_code' => '#000000']);
        $white = \App\Models\Color::create(['name' => '화이트', 'hex_code' => '#FFFFFF']);

        $blackProduct = Product::factory()->create(['name' => '매력적인 블랙 레깅스', 'status' => '판매중']);
        $blackProduct->colors()->attach($black->id);

        $whiteProduct = Product::factory()->create(['name' => '매끈한 화이트 탑', 'status' => '판매중']);
        $whiteProduct->colors()->attach($white->id);

        // 2. 검색어 '매'로 검색하되, 색상은 '블랙'만 필터링! 
        $response = $this->get(route('products.search', [
            'q' => '매',
            'colors' => ['블랙']
        ]));

        // 3. 검증!  
        $response->assertStatus(200);
        $response->assertSee('매력적인 블랙 레깅스');
        $response->assertDontSee('매끈한 화이트 탑'); // 화이트는 검색어엔 맞지만 색상이 틀려야 함! 
    }
}

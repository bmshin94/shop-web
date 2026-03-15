<?php

namespace Tests\Feature;

use App\Models\Color;
use App\Models\Member;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiOptionCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 멀티 옵션 파라미터(p, q, c, s)를 통해 결제 페이지가 정상적으로 로드되는지 테스트한다.
     */
    public function test_checkout_page_loads_with_multi_option_parameters(): void
    {
        // 1. 데이터 준비
        $member = Member::factory()->create();
        $product = Product::factory()->create(['price' => 10000]);
        $color = Color::create(['name' => '블랙', 'code' => '#000000']);
        $size = Size::create(['name' => 'L', 'sort_order' => 1]);
        
        $product->colors()->attach($color->id);
        $product->sizes()->attach($size->id);

        // 2. 파라미터를 조합하여 결제 페이지 요청
        $response = $this->actingAs($member)
            ->get(route('checkout', [
                'p' => [$product->id],
                'q' => [2],
                'c' => [$color->id],
                's' => [$size->id],
            ]));

        // 3. 검증
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('블랙');
        $response->assertSee('L');
        $response->assertSee(number_format(20000)); // 10000 * 2
    }

    /**
     * 필수 옵션이 빠진 멀티 파라미터 요청 시, 서버 측에서 차단되는지 테스트한다.
     */
    public function test_checkout_fails_if_required_options_missing_in_multi_params(): void
    {
        $member = Member::factory()->create();
        $product = Product::factory()->create();
        $color = Color::create(['name' => '블루', 'code' => '#0000FF']);
        $product->colors()->attach($color->id); // 옵션 필수 상품

        // 옵션(c[]) 없이 요청
        $response = $this->actingAs($member)
            ->get(route('checkout', [
                'p' => [$product->id],
                'q' => [1],
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}

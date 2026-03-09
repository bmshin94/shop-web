<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Operator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdminProductTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // 관리자 계정 생성 및 로그인 상태로 설정
        $this->admin = Operator::factory()->create();
        Storage::fake('public');
    }

    /**
     * 상품 수정 시 이미지가 정상적으로 교체(Replace)되는지 테스트합니다.
     */
    public function test_can_replace_product_image(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $product = Product::factory()->create(['category_id' => $category->id]);
        
        // 1. 기존 이미지 생성 (sort_order: 0)
        $oldImage = $product->images()->create([
            'image_path' => '/storage/products/old.jpg',
            'sort_order' => 0
        ]);

        // 2. 새로운 이미지로 교체 요청 (images[0] 사용)
        $newFile = UploadedFile::fake()->create('new.jpg', 100, 'image/jpeg');
        $updateData = [
            'category_id' => $category->id,
            'name' => $product->name,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'status' => '판매중',
            'images' => [
                0 => $newFile // 0번 슬롯 교체
            ]
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.update', $product), $updateData);

        $response->assertRedirect(route('admin.products.show', $product));
        
        $product->refresh();
        
        // 3. 검증: 이미지가 추가된 게 아니라 교체되어 1개여야 함
        $this->assertCount(1, $product->images);
        $this->assertEquals(0, $product->images->first()->sort_order);
        $this->assertNotEquals('/storage/products/old.jpg', $product->images->first()->image_path);
    }

    /**
     * 상품 등록 시 사이즈 정보가 정상적으로 저장되는지 테스트합니다.
     */
    public function test_can_create_product_with_sizes(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $color = Color::firstOrCreate(['name' => 'Red'], ['hex_code' => '#FF0000']);
        $size1 = Size::firstOrCreate(['name' => 'S'], ['sort_order' => 1]);
        $size2 = Size::firstOrCreate(['name' => 'M'], ['sort_order' => 2]);

        $productData = [
            'category_id' => $category->id,
            'name' => '사이즈 테스트 상품',
            'price' => 10000,
            'stock_quantity' => 100,
            'status' => '판매중',
            'colors' => [$color->id],
            'sizes' => [$size1->id, $size2->id],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        
        $product = Product::where('name', '사이즈 테스트 상품')->first();
        $this->assertCount(2, $product->sizes);
        $this->assertTrue($product->sizes->contains($size1));
        $this->assertTrue($product->sizes->contains($size2));
    }

    /**
     * 상품 수정 시 사이즈 정보가 정상적으로 업데이트되는지 테스트합니다.
     */
    public function test_can_update_product_sizes(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $size1 = Size::firstOrCreate(['name' => 'L'], ['sort_order' => 3]);
        $size2 = Size::firstOrCreate(['name' => 'XL'], ['sort_order' => 4]);
        
        $product = Product::factory()->create(['category_id' => $category->id]);
        $product->sizes()->attach($size1->id);

        $updateData = [
            'category_id' => $category->id,
            'name' => '사이즈 수정 상품',
            'price' => 20000,
            'stock_quantity' => 50,
            'status' => '판매중',
            'sizes' => [$size2->id], // size1을 빼고 size2만 선택
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.products.update', $product), $updateData);

        $response->assertRedirect(route('admin.products.show', $product));
        
        $product->refresh();
        $this->assertCount(1, $product->sizes);
        $this->assertFalse($product->sizes->contains($size1));
        $this->assertTrue($product->sizes->contains($size2));
    }

    /**
     * 상품 등록 시 연관 상품 정보가 정상적으로 저장되는지 테스트합니다.
     */
    public function test_can_create_product_with_related_products(): void
    {
        $category = Category::factory()->create(['level' => 2]);
        $related1 = Product::factory()->create(['category_id' => $category->id]);
        $related2 = Product::factory()->create(['category_id' => $category->id]);

        $productData = [
            'category_id' => $category->id,
            'name' => '연관 상품 테스트',
            'price' => 15000,
            'stock_quantity' => 10,
            'status' => '판매중',
            'related_products' => [$related1->id, $related2->id],
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        
        $product = Product::where('name', '연관 상품 테스트')->first();
        $this->assertCount(2, $product->relatedProducts);
        $this->assertTrue($product->relatedProducts->contains($related1));
        $this->assertTrue($product->relatedProducts->contains($related2));
    }
}

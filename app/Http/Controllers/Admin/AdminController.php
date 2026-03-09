<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\SizeGroup;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    /**
     * 관리자 대시보드 메인
     */
    public function dashboard()
    {
        $stats = [
            'today_sales' => 1250000,
            'today_orders' => 42,
            'new_members' => 12,
            'pending_qna' => 5,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * 관리자 상품 목록 페이지
     */
    public function productList(Request $request)
    {
        $query = Product::with(['category.parent'])->latest();

        // 1. 상품명 검색
        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 2. 카테고리 필터
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // 3. 상태 필터
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 4. 판매가 필터 (최소 ~ 최대) 
        if ($minPrice = $request->input('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice]);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
        }

        // 5. 재고 수량 필터 (최소 ~ 최대) 
        if ($minStock = $request->input('min_stock')) {
            $query->where('stock_quantity', '>=', $minStock);
        }
        if ($maxStock = $request->input('max_stock')) {
            $query->where('stock_quantity', '<=', $maxStock);
        }

        // 6. 상품 구분 필터 (NEW, BEST) 
        if ($request->input('is_new')) {
            $query->where('is_new', true);
        }
        if ($request->input('is_best')) {
            $query->where('is_best', true);
        }

        $products = $query->paginate(10)->withQueryString(); 

        $categories = Category::onlyParents()->with('children')->orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * 상품 검색 (AJAX용 - 연관 상품 선택 등)
     */
    public function productSearch(Request $request)
    {
        $search = $request->get('q');
        
        $products = Product::where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
                if (is_numeric($search)) {
                    $query->orWhere('id', $search);
                }
            })
            ->selling()
            ->limit(10)
            ->get(['id', 'name', 'image_url', 'price']);

        // 이미지 경로 처리 (URL 보장)
        $products->transform(function($product) {
            if ($product->image_url && !Str::startsWith($product->image_url, 'http')) {
                $product->image_url = asset($product->image_url);
            }
            return $product;
        });

        return response()->json($products);
    }

    /**
     * 상품 등록 화면 
     */
    public function productCreate()
    {
        $categories = Category::with(['children' => function($q) {
                $q->orderBy('sort_order');
            }])
            ->onlyParents()
            ->orderBy('sort_order')
            ->get();

        $colors = Color::orderBy('name')->get();
        $sizeGroups = SizeGroup::with('sizes')->get();

        return view('admin.products.create', compact('categories', 'colors', 'sizeGroups'));
    }

    /**
     * 상품 저장 처리 
     */
    public function productStore(Request $request)
    {
        $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->where('level', 2))
            ],
            'name' => 'required|string|max:255',
            'brief_description' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|integer|min:0',
            'sale_price' => 'nullable|integer|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|string|in:판매중,품절,숨김',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (Product::where('slug', $slug)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }

        $data['is_new'] = $request->has('is_new');
        $data['is_best'] = $request->has('is_best');

        $product = Product::create($data);

        if ($request->has('colors')) {
            $product->colors()->sync($request->input('colors'));
        }
        if ($request->has('sizes')) {
            $product->sizes()->sync($request->input('sizes'));
        }

        // 3. 연관 상품(함께 스타일링) 정보 동기화
        if ($request->has('related_products')) {
            $relatedData = [];
            foreach ($request->input('related_products') as $index => $relatedId) {
                $relatedData[$relatedId] = ['sort_order' => $index];
            }
            $product->relatedProducts()->sync($relatedData);
        }

        // 4. 이미지 저장 (Laravel 정석 방식)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $fullPath = '/storage/' . $path;
                
                $product->images()->create([
                    'image_path' => $fullPath,
                    'sort_order' => $index
                ]);

                if ($index == 0) {
                    $product->update(['image_url' => $fullPath]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', '새로운 상품이 등록되었습니다.');
    }

    /**
     * 관리자 상품 상세 조회 페이지
     */
    public function productShow(Product $product)
    {
        $product->load(['category.parent', 'images', 'colors', 'sizes.group', 'relatedProducts']);
        return view('admin.products.show', compact('product'));
    }
    /**
     * 관리자 상품 수정 화면 
     */
    public function productEdit(Product $product)
    {
        $categories = Category::onlyParents()->with('children')->orderBy('sort_order')->get();
        $product->load(['images', 'colors', 'sizes', 'relatedProducts']);
        
        $colors = Color::orderBy('name')->get();
        $sizeGroups = SizeGroup::with('sizes')->get();

        return view('admin.products.edit', compact('product', 'categories', 'colors', 'sizeGroups'));
    }

    /**
     * 관리자 상품 수정 처리 
     */
    public function productUpdate(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->where('level', 2))
            ],
            'name' => 'required|string|max:255',
            'brief_description' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'required|integer|min:0',
            'sale_price' => 'nullable|integer|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|string|in:판매중,품절,숨김',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
        ]);

        $data = $request->except(['images', 'remove_images', 'colors', 'sizes']);

        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }

        $data['is_new'] = $request->has('is_new');
        $data['is_best'] = $request->has('is_best');

        $product->update($data);

        $product->colors()->sync($request->input('colors', []));
        $product->sizes()->sync($request->input('sizes', []));

        // 3. 연관 상품(함께 스타일링) 정보 동기화
        $relatedProducts = $request->input('related_products', []);
        $relatedData = [];
        foreach ($relatedProducts as $index => $relatedId) {
            $relatedData[$relatedId] = ['sort_order' => $index];
        }
        $product->relatedProducts()->sync($relatedData);

        // 4. 기존 이미지 삭제 처리
        if ($request->has('remove_images')) {
            foreach ($request->input('remove_images') as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    $storagePath = str_replace('/storage/', '', $image->image_path);
                    Storage::disk('public')->delete($storagePath);
                    $image->delete();
                }
            }
        }

        // 새 이미지 업로드 및 교체 (슬롯 기준)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $sortOrder => $file) {
                $existingInSlot = $product->images()->where('sort_order', $sortOrder)->first();
                if ($existingInSlot) {
                    $storagePath = str_replace('/storage/', '', $existingInSlot->image_path);
                    Storage::disk('public')->delete($storagePath);
                    $existingInSlot->delete();
                }

                $path = $file->store('products', 'public');
                $fullPath = '/storage/' . $path;

                $product->images()->create([
                    'image_path' => $fullPath,
                    'sort_order' => $sortOrder
                ]);
            }
        }

        // 대표 이미지 재설정
        $firstImage = $product->images()->orderBy('sort_order')->first();
        $product->update(['image_url' => $firstImage ? $firstImage->image_path : null]);

        return redirect()->route('admin.products.show', $product)->with('success', '상품 정보가 수정되었습니다.');
    }

    /**
     * 관리자 상품 삭제 처리 
     */
    public function productDestroy(Product $product)
    {
        foreach ($product->images as $image) {
            $storagePath = str_replace('/storage/', '', $image->image_path);
            Storage::disk('public')->delete($storagePath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', '상품이 삭제되었습니다.');
    }

    /**
     * 카테고리 관리 부분은 생략 없이 유지
     */
    public function categoryList()
    {
        $categories = Category::withCount('products')
            ->with(['children' => function($q) {
                $q->withCount('products')->orderBy('sort_order');
            }])
            ->onlyParents()
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function categoryCreate()
    {
        $parentCategories = Category::onlyParents()->orderBy('sort_order')->get();
        $nextOrderMap = ['root' => Category::whereNull('parent_id')->max('sort_order') + 1];
        foreach ($parentCategories as $parent) {
            $nextOrderMap[$parent->id] = Category::where('parent_id', $parent->id)->max('sort_order') + 1;
        }
        foreach ($nextOrderMap as $key => $val) { if ($val < 1) $nextOrderMap[$key] = 1; }

        return view('admin.categories.create', compact('parentCategories', 'nextOrderMap'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => [
                'required', 'integer', 'min:0',
                Rule::unique('categories')->where(fn ($q) => $q->where('parent_id', $request->parent_id))
            ],
            'is_active' => 'required|boolean',
        ]);

        $data = $request->all();
        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (Category::where('slug', $slug)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }
        $data['level'] = empty($data['parent_id']) ? 1 : 2;

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', '카테고리가 등록되었습니다.');
    }

    public function categoryEdit(Category $category)
    {
        $parentCategories = Category::onlyParents()->where('id', '!=', $category->id)->orderBy('sort_order')->get();
        $nextOrderMap = ['root' => Category::whereNull('parent_id')->where('id', '!=', $category->id)->max('sort_order') + 1];
        foreach (Category::onlyParents()->get() as $parent) {
            $nextOrderMap[$parent->id] = Category::where('parent_id', $parent->id)->where('id', '!=', $category->id)->max('sort_order') + 1;
        }
        foreach ($nextOrderMap as $key => $val) { if ($val < 1) $nextOrderMap[$key] = 1; }

        return view('admin.categories.edit', compact('category', 'parentCategories', 'nextOrderMap'));
    }

    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => 'required|boolean',
        ]);

        $oldParentId = $category->parent_id;
        $data = $request->all();
        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }
        $data['level'] = empty($data['parent_id']) ? 1 : 2;
        $category->update($data);

        if ($oldParentId != $request->parent_id) {
            foreach (Category::where('parent_id', $oldParentId)->orderBy('sort_order')->get() as $index => $child) {
                $child->update(['sort_order' => $index + 1]);
            }
            foreach (Category::where('parent_id', $request->parent_id)->orderBy('sort_order')->get() as $index => $child) {
                $child->update(['sort_order' => $index + 1]);
            }
        }

        return redirect()->route('admin.categories.index')->with('success', '카테고리가 수정되었습니다.');
    }

    public function categoryDestroy(Category $category)
    {
        $parentId = $category->parent_id;
        $category->delete();
        foreach (Category::where('parent_id', $parentId)->orderBy('sort_order')->get() as $index => $item) {
            $item->update(['sort_order' => $index + 1]);
        }
        return redirect()->route('admin.categories.index')->with('success', '카테고리가 삭제되었습니다.');
    }

    public function categoryReorder(Request $request)
    {
        $order = json_decode($request->input('order'));
        if (is_array($order)) {
            $parentSort = 0; $childSort = 0;
            foreach ($order as $id) {
                $category = Category::find($id);
                if ($category) {
                    if ($category->level == 1) {
                        $parentSort++; $category->update(['sort_order' => $parentSort]); $childSort = 0;
                    } else {
                        $childSort++; $category->update(['sort_order' => $childSort]);
                    }
                }
            }
            return redirect()->route('admin.categories.index')->with('success', '순서가 저장되었습니다.');
        }
        return redirect()->route('admin.categories.index')->with('error', '순서 정보가 올바르지 않습니다.');
    }
}

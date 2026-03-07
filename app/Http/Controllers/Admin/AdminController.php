<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        $query = \App\Models\Product::with(['category.parent'])->latest();

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

        // 4. 판매가 필터 (최소 ~ 최대) ✨
        if ($minPrice = $request->input('min_price')) {
            // 할인 판매가가 있으면 할인가 기준, 없으면 정가 기준으로 최소값 비교
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice]);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
        }

        // 5. 재고 수량 필터 (최소 ~ 최대) ✨
        if ($minStock = $request->input('min_stock')) {
            $query->where('stock_quantity', '>=', $minStock);
        }
        if ($maxStock = $request->input('max_stock')) {
            $query->where('stock_quantity', '<=', $maxStock);
        }

        // 6. 상품 구분 필터 (NEW, BEST) ✨
        if ($isNew = $request->input('is_new')) {
            $query->where('is_new', true);
        }
        if ($isBest = $request->input('is_best')) {
            $query->where('is_best', true);
        }

        $products = $query->paginate(10)->withQueryString(); 

        // 필터용 카테고리 목록
        $categories = Category::onlyParents()->with('children')->orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * 상품 등록 화면 ✨
     */
    public function productCreate()
    {
        // 카테고리 목록을 계층 구조로 가져오기 ✨
        $categories = Category::with(['children' => function($q) {
                $q->orderBy('sort_order');
            }])
            ->onlyParents()
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * 상품 저장 처리 🚀
     */
    public function productStore(Request $request)
    {
        $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->where('level', 2))
            ],
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'price' => 'required|integer|min:0',
            'sale_price' => 'nullable|integer|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|string|in:판매중,품절,숨김',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:4', // 이미지 배열 검증 ✨
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 개별 파일 검증 🚀
        ], [
            'sale_price.lt' => '할인 판매가는 정가보다 낮아야 합니다.',
            'images.*.image' => '이미지 파일만 업로드 가능합니다.',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (\App\Models\Product::where('slug', $slug)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }

        $data['is_new'] = $request->has('is_new');
        $data['is_best'] = $request->has('is_best');

        // 1. 상품 기본 정보 저장
        $product = \App\Models\Product::create($data);

        // 2. 이미지 파일 진짜 저장 및 DB 연결 (심볼릭 링크 사용! 🚀)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                // storage/app/public/products 폴더에 저장! 😊
                $path = $file->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => '/storage/' . $path,
                    'sort_order' => $index // 0번이 대표 이미지가 됩니다
                ]);

                // 첫 번째 이미지를 상품의 대표 이미지(image_url)로 설정!
                if ($index === 0) {
                    $product->update(['image_url' => '/storage/' . $path]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', '새로운 상품이 등록되었습니다.');
        }

        /**
        * 관리자 상품 상세 조회 페이지 ✨
        */
        public function productShow(\App\Models\Product $product)
        {
        // 뷰에서 관계 데이터를 편리하게 쓰기 위해 Eager Loading 처리 💖
        $product->load(['category.parent', 'images']);
        return view('admin.products.show', compact('product'));
        }

        /**
        * 관리자 상품 수정 화면 ✨
        */
        public function productEdit(\App\Models\Product $product)
        {
        $categories = Category::onlyParents()->with('children')->orderBy('sort_order')->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
        }

        /**
        * 관리자 상품 수정 처리 (DB 업데이트) 🚀
        */
        public function productUpdate(Request $request, \App\Models\Product $product)
        {
        $request->validate([
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->where('level', 2))
            ],
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id, // 자기 자신은 예외! 😊
            'price' => 'required|integer|min:0',
            'sale_price' => 'nullable|integer|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'status' => 'required|string|in:판매중,품절,숨김',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array', // 삭제할 기존 이미지 ID 배열 ✨
        ], [
            'sale_price.lt' => '할인 판매가는 정가보다 낮아야 합니다.',
            'images.*.image' => '이미지 파일만 업로드 가능합니다.',
        ]);

        $data = $request->except(['images', 'remove_images']);

        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (\App\Models\Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }

        $data['is_new'] = $request->has('is_new');
        $data['is_best'] = $request->has('is_best');

        // 1. 상품 기본 정보 수정
        $product->update($data);

        // 2. 기존 이미지 삭제 처리 🗑️
        if ($request->has('remove_images')) {
            foreach ($request->input('remove_images') as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    // 실제 파일 삭제 (public/uploads 경로 기준)
                    $filePath = str_replace('/uploads/', '', $image->image_path);
                    if (\Illuminate\Support\Facades\File::exists(public_path('uploads/' . $filePath))) {
                        \Illuminate\Support\Facades\File::delete(public_path('uploads/' . $filePath));
                    }
                    $image->delete();
                }
            }
        }

        // 3. 새 이미지 파일 업로드 및 DB 연결 🚀
        if ($request->hasFile('images')) {
            // 새로 들어온 이미지의 시작 sort_order 계산 (기존 마지막 이미지 다음부터)
            $startOrder = $product->images()->max('sort_order') !== null ? $product->images()->max('sort_order') + 1 : 0;

            foreach ($request->file('images') as $index => $file) {
                $filename = \Illuminate\Support\Str::random(20) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $path = '/uploads/products/' . $filename;

                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $startOrder + $index
                ]);
            }
        }

        // 4. 대표 이미지(image_url) 재설정 로직 💖
        // 남아있는 이미지 중 sort_order가 가장 낮은 것을 대표 이미지로 설정
        $firstImage = $product->images()->orderBy('sort_order')->first();
        if ($firstImage) {
            $product->update(['image_url' => $firstImage->image_path]);
        } else {
            // 이미지가 하나도 없다면 null
            $product->update(['image_url' => null]);
        }

        return redirect()->route('admin.products.show', $product)->with('success', '상품 정보가 성공적으로 수정되었습니다.');
        }

        /**
        * 관리자 상품 삭제 처리 🗑️
        */
        public function productDestroy(\App\Models\Product $product)
        {
        // 1. 연결된 이미지 파일 실제 삭제
        foreach ($product->images as $image) {
            $filePath = str_replace('/uploads/', '', $image->image_path);
            if (\Illuminate\Support\Facades\File::exists(public_path('uploads/' . $filePath))) {
                \Illuminate\Support\Facades\File::delete(public_path('uploads/' . $filePath));
            }
        }

        // 2. 상품 삭제 (Cascade 옵션이 있다면 이미지 DB 레코드도 함께 삭제됨)
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', '상품이 완전히 삭제되었습니다.');
        }

        /**
        * 관리자 카테고리 목록 페이지 (DB 연동!)
        */public function categoryList()
{
    // 상위 카테고리별로 자식들을 포함하고, 각각의 상품 수(products_count)를 실시간으로 집계! ✨
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
    // 대분류만 가져와서 선택지로 제공 💖
    $parentCategories = Category::onlyParents()->orderBy('sort_order')->get();

    // 1. 대분류(Root) 기준 다음 정렬 순서 기본값 ✨
    $nextOrderMap = [
        'root' => Category::whereNull('parent_id')->max('sort_order') + 1
    ];

    // 2. 각 대분류별 자식들의 다음 순서 미리 계산 😊
    foreach ($parentCategories as $parent) {
        $nextOrderMap[$parent->id] = Category::where('parent_id', $parent->id)->max('sort_order') + 1;
    }

    // 혹시 데이터가 하나도 없을 때를 위해 최소값 1 보장! ✨
    foreach ($nextOrderMap as $key => $val) {
        if ($val < 1) $nextOrderMap[$key] = 1;
    }

    return view('admin.categories.create', compact('parentCategories', 'nextOrderMap'));
}

    /**
     * 카테고리 저장 처리 (Level 자동 계산 & 중복 방지!) ✨
     */
    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('categories')->where(fn ($q) => $q->where('parent_id', $request->parent_id))
            ],
            'is_active' => 'required|boolean',
        ], [
            'sort_order.unique' => '이미 사용 중인 정렬 순서예요! 같은 그룹 내에서는 서로 다른 숫자를 써주세요! 💖',
        ]);

        $data = $request->all();
        
        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) {
                $slug = str_replace(' ', '-', $data['name']);
            }
            if (Category::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . time();
            }
            $data['slug'] = $slug;
        }

        $data['level'] = empty($data['parent_id']) ? 1 : 2;

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', '새로운 카테고리가 등록되었습니다.');
    }

    /**
     * 카테고리 수정 화면 (DB 연동!)
     */
    public function categoryEdit(Category $category)
    {
        // 대분류만 가져와서 선택지로 제공 (본인은 제외!)
        $parentCategories = Category::onlyParents()
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->get();

        // 실시간 순서 추천을 위한 데이터 준비! ✨
        $nextOrderMap = [
            'root' => Category::whereNull('parent_id')->where('id', '!=', $category->id)->max('sort_order') + 1
        ];
        foreach (Category::onlyParents()->get() as $parent) {
            $nextOrderMap[$parent->id] = Category::where('parent_id', $parent->id)
                ->where('id', '!=', $category->id)
                ->max('sort_order') + 1;
        }
        foreach ($nextOrderMap as $key => $val) { if ($val < 1) $nextOrderMap[$key] = 1; }

        return view('admin.categories.edit', compact('category', 'parentCategories', 'nextOrderMap'));
    }

    /**
     * 카테고리 수정 처리 (진짜 업데이트!) ✨
     */
    public function categoryUpdate(Request $request, Category $category)
    {
        $request->validate([
            // ... (기존 validation 로직 동일)
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => 'required|boolean',
        ]);

        $oldParentId = $category->parent_id;
        $newParentId = $request->parent_id;
        $data = $request->all();
        
        if (empty($data['slug'])) {
            $slug = Str::slug($data['name']);
            if (empty($slug)) $slug = str_replace(' ', '-', $data['name']);
            if (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) $slug = $slug . '-' . time();
            $data['slug'] = $slug;
        }

        $data['level'] = empty($data['parent_id']) ? 1 : 2;
        $category->update($data);

        // 부모가 바뀌었을 때만 재정렬 수행! 🚀
        if ($oldParentId != $newParentId) {
            // 1. 예전 집(Old Parent) 식구들 정렬
            $oldChildren = Category::where('parent_id', $oldParentId)->orderBy('sort_order')->get();
            foreach ($oldChildren as $index => $child) {
                $child->update(['sort_order' => $index + 1]);
            }

            // 2. 새 집(New Parent) 식구들 정렬
            $newChildren = Category::where('parent_id', $newParentId)->orderBy('sort_order')->get();
            foreach ($newChildren as $index => $child) {
                $child->update(['sort_order' => $index + 1]);
            }
        }

        return redirect()->route('admin.categories.index')->with('success', '카테고리 정보가 수정되었습니다.');
    }

    /**
     * 카테고리 삭제 처리
     */
    public function categoryDestroy(Category $category)
    {
        $parentId = $category->parent_id;
        
        // 1. 카테고리 삭제 (하위 자식들도 자동 삭제됩니다)
        $category->delete();

        // 2. 남은 카테고리들 순서 재정렬
        $remaining = Category::where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->get();

        foreach ($remaining as $index => $item) {
            $item->update(['sort_order' => $index + 1]);
        }

        return redirect()->route('admin.categories.index')->with('success', '카테고리가 삭제되었습니다.');
    }

    /**
     * 카테고리 순서 재정렬 처리 (드래그 앤 드롭 저장!)
     */
    public function categoryReorder(Request $request)
    {
        $order = json_decode($request->input('order'));

        if (is_array($order)) {
            $parentSort = 0;
            $childSort = 0;

            foreach ($order as $id) {
                $category = Category::find($id);
                if ($category) {
                    if ($category->level == 1) {
                        // 대분류인 경우: 대분류 번호 증가, 소분류 번호 초기화! 😊
                        $parentSort++;
                        $category->update(['sort_order' => $parentSort]);
                        $childSort = 0; 
                    } else {
                        // 소분류인 경우: 해당 부모 안에서 번호 증가! 💖
                        $childSort++;
                        $category->update(['sort_order' => $childSort]);
                    }
                }
            }
            return redirect()->route('admin.categories.index')->with('success', '카테고리 순서가 새롭게 저장되었습니다.');
        }

        return redirect()->route('admin.categories.index')->with('error', '순서 정보가 올바르지 않습니다.');
    }
}

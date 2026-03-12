<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brief_description',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock_quantity',
        'status',
        'shipping_type',
        'shipping_fee',
        'image_url',
        'is_new',
        'is_best',
    ];

    /**
     * 연관 상품 관계 정의 (함께 스타일링하기 좋은 아이템)
     */
    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_related', 'product_id', 'related_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /**
     * 카테고리 관계 정의
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 색상 관계 정의 (다대다) 
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    /**
     * 사이즈 관계 정의 (다대다)
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class)->orderBy('sort_order');
    }

    /**
     * 상품이 속한 첫 번째 사이즈 그룹 (가이드용)
     */
    public function getSizeGroupAttribute()
    {
        return $this->sizes->first()?->group;
    }

    /**
     * 상품 이미지 관계 정의 
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * 상품의 리뷰 목록
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * 상품을 찜한 정보
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * 현재 로그인한 사용자가 이 상품을 찜했는지 여부
     */
    public function getIsWishlistedAttribute()
    {
        if (!auth()->check()) return false;
        return $this->wishlists()->where('member_id', auth()->id())->exists();
    }

    /**
     * 배송비 정보 텍스트 반환
     */
    public function getShippingInfoAttribute()
    {
        if ($this->shipping_type === '무료') {
            return '무료배송';
        }

        if ($this->shipping_type === '고정') {
            return '배송비 ₩' . number_format($this->shipping_fee);
        }

        // 기본 설정 (예: 3,000원, 5만원 이상 무료)
        $price = $this->sale_price ?? $this->price;
        if ($price >= 50000) {
            return '무료배송';
        }

        return '배송비 ₩3,000';
    }

    /**
     * 평균 별점 (Accessor) 
     */
    public function getAverageRatingAttribute()
    {
        // 1. 이미 reviews_avg_rating 속성이 있다면 그걸 사용! (withAvg 사용 시) 
        if (isset($this->attributes['reviews_avg_rating'])) {
            return round($this->attributes['reviews_avg_rating'] ?? 0, 1);
        }

        // 2. 없다면 쿼리 실행 (Lazy Loading 방지용)
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    /**
     * 리뷰 수 (Accessor) 
     */
    public function getReviewCountAttribute()
    {
        // 1. 이미 reviews_count 속성이 있다면 그걸 사용! (withCount 사용 시) 
        if (isset($this->attributes['reviews_count'])) {
            return $this->attributes['reviews_count'];
        }

        // 2. 없다면 쿼리 실행
        return $this->reviews()->count();
    }

    /**
     * 사용자 페이지에 노출할 상품 스코프 (판매중 또는 품절)
     */
    public function scopeSelling($query)
    {
        return $query->whereIn('status', ['판매중', '품절']);
    }

    /**
     * 할인율 계산 (Accessors) 
     */
    public function getDiscountRateAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
}

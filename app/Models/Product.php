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
        'slug',
        'description',
        'price',
        'sale_price',
        'stock_quantity',
        'status',
        'image_url',
        'is_new',
        'is_best',
    ];

    /**
     * 카테고리 관계 정의
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 상품 이미지 관계 정의 ✨
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * 판매 중인 상품 스코프
     */
    public function scopeSelling($query)
    {
        return $query->where('status', '판매중');
    }

    /**
     * 할인율 계산 (Accessors) ✨
     */
    public function getDiscountRateAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }
}

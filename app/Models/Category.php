<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'level', // 새로운 level 컬럼 허용! 
        'sort_order',
        'is_active',
    ];

    /**
     * 상위 카테고리 관계 정의
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * 하위 카테고리 관계 정의
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * 상품 관계 정의 
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * 활성화된 카테고리만 조회하는 스코프
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 대분류(1차)만 조회하는 스코프 (level 컬럼 사용! )
     */
    public function scopeOnlyParents($query)
    {
        return $query->where('level', 1);
    }

    /**
     * 소분류(2차)만 조회하는 스코프
     */
    public function scopeOnlyChildren($query)
    {
        return $query->where('level', 2);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exhibition extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUSES = [
        '진행예정',
        '진행중',
        '종료',
        '비노출',
    ];

    protected $fillable = [
        'title',
        'slug',
        'status',
        'banner_image_url',
        'summary',
        'description',
        'start_at',
        'end_at',
        'sort_order',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * 진행 중인 기획전만 조회하는 스코프 
     */
    public function scopeActive($query)
    {
        return $query->where('status', '진행중')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now());
    }

    /**
     * 기획전에 포함된 상품 목록
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'exhibition_product')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order', 'asc');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'product_id',
        'title',
        'content',
        'is_private',
        'images',
        'answer',
        'status',
        'answered_at',
    ];

    protected $casts = [
        'images' => 'array',
        'is_private' => 'boolean',
        'answered_at' => 'datetime',
    ];

    /**
     * 회원 관계 정의 ✨
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * 상품 관계 정의 (상품 문의인 경우) ✨💖
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

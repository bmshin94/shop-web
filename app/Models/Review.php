<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'member_id',
        'rating',
        'title',
        'content',
        'images',
    ];

    protected $casts = [
        'images' => 'json',
    ];

    /**
     * 상품 관계 정의
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 회원 관계 정의
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

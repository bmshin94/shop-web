<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentView extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'product_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * 회원 관계 정의 ✨
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * 상품 관계 정의 ✨
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

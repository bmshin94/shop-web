<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_group_id',
        'name',
        'sort_order',
    ];

    /**
     * 이 사이즈가 속한 그룹
     */
    public function group()
    {
        return $this->belongsTo(SizeGroup::class, 'size_group_id');
    }

    /**
     * 이 사이즈를 가진 상품들
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

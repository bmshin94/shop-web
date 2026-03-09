<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex_code',
    ];

    /**
     * 상품 관계 정의 (다대다) 
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

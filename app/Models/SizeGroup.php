<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size_guide'];

    protected $casts = [
        'size_guide' => 'array',
    ];

    /**
     * 이 그룹에 속한 사이즈들
     */
    public function sizes()
    {
        return $this->hasMany(Size::class)->orderBy('sort_order');
    }
}

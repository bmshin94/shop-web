<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'content',
        'is_important',
        'is_visible',
        'published_at',
    ];

    protected $casts = [
        'is_important' => 'boolean',
        'is_visible' => 'boolean',
        'published_at' => 'datetime',
    ];
}

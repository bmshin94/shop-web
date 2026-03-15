<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Magazine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category',
        'title',
        'author',
        'image_url',
        'content',
        'is_visible',
        'published_at',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'published_at' => 'datetime',
    ];
}

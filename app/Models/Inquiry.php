<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'title',
        'content',
        'answer',
        'status',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    /**
     * 회원 관계 정의 ✨
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

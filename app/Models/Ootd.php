<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ootd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'image_url',
        'content',
        'instagram_url',
        'likes_count',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'likes_count' => 'integer',
    ];

    /**
     * 작성 회원 관계 정의
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * 좋아요를 누른 회원들 관계 (다대다) ✨💖❤️
     */
    public function likers()
    {
        return $this->belongsToMany(Member::class, 'ootd_likes')
            ->withTimestamps();
    }

    /**
     * 특정 회원이 이 OOTD에 좋아요를 눌렀는지 확인 ✨🤔❤️
     */
    public function isLikedBy(?Member $member)
    {
        if (!$member) return false;
        return $this->likers()->where('member_id', $member->id)->exists();
    }
}

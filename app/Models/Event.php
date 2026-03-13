<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_GENERAL = '일반';
    public const TYPE_PARTICIPATION = '응모형';

    public const TYPES = [
        self::TYPE_GENERAL,
        self::TYPE_PARTICIPATION,
    ];

    protected $fillable = [
        'title',
        'slug',
        'type',
        'banner_image_url',
        'summary',
        'description',
        'winner_announcement', // 당첨자 발표 내용 추가! ✨
        'start_at',
        'end_at',
        'sort_order',
        'is_hero',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'sort_order' => 'integer',
        'is_hero' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * 현재 날짜 기반 동적 상태 속성 ✨
     */
    public function getStatusAttribute(): string
    {
        $now = now();

        if ($this->start_at && $this->start_at->isFuture()) {
            return '진행예정';
        }

        if ($this->end_at && $this->end_at->isPast()) {
            return '종료';
        }

        return '진행중';
    }

    /**
     * 이벤트 당첨자들 ✨
     */
    public function winners(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'event_winners')->withTimestamps();
    }

    /**
     * 이벤트 참여 기록들
     */
    public function participations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    /**
     * 이벤트 참여자들
     */
    public function participants(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'event_participations')->withTimestamps();
    }
}

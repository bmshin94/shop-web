<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'notification_type',
        'channel',
        'recipient',
        'message',
        'status',
        'error_message',
        'api_response',
        'sent_at',
    ];

    protected $casts = [
        'api_response' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * 알림을 받은 회원 정보
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

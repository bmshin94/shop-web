<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'order_id',
        'reason',
        'amount',
        'status',
        'remaining_amount',
        'balance_after',
        'expired_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'remaining_amount' => 'integer',
        'balance_after' => 'integer',
        'expired_at' => 'datetime',
    ];

    /**
     * 해당 포인트 이력의 회원 정보
     *
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

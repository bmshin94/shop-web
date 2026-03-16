<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderClaim extends Model
{
    use HasFactory, SoftDeletes;
    // 유형 상수
    public const TYPE_EXCHANGE = 'exchange';
    public const TYPE_RETURN = 'return';
    public const TYPE_CANCEL = 'cancel';

    // 상태 상수
    public const STATUS_RECEIVED = '접수';
    public const STATUS_PROCESSING = '처리중';
    public const STATUS_COMPLETED = '완료';
    public const STATUS_REJECTED = '거부';

    public const ALL_STATUSES = [
        self::STATUS_RECEIVED,
        self::STATUS_PROCESSING,
        self::STATUS_COMPLETED,
        self::STATUS_REJECTED,
    ];

    public const ALL_TYPES = [
        self::TYPE_EXCHANGE,
        self::TYPE_RETURN,
        self::TYPE_CANCEL,
    ];

    protected $fillable = [
        'member_id',
        'order_id',
        'claim_number',
        'type',
        'reason_type',
        'reason_detail',
        'status',
        'admin_memo',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(OrderClaimItem::class);
    }
}

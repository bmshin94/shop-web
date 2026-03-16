<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderClaim extends Model
{
    use HasFactory;

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

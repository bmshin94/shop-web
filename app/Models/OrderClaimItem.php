<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderClaimItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_claim_id',
        'order_item_id',
        'quantity',
    ];

    public function claim()
    {
        return $this->belongsTo(OrderClaim::class, 'order_claim_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}

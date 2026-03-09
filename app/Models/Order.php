<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const PAYMENT_METHODS = [
        '신용카드',
        '무통장입금',
        '간편결제',
        '휴대폰결제',
    ];

    public const PAYMENT_STATUSES = [
        '결제대기',
        '결제완료',
        '환불완료',
        '취소완료',
    ];

    public const ORDER_STATUSES = [
        '주문접수',
        '상품준비중',
        '배송중',
        '배송완료',
        '취소완료',
    ];

    public const SHIPPING_STATUSES = [
        '배송대기',
        '출고완료',
        '배송중',
        '배송완료',
    ];

    public const SHIPPING_STARTED_STATUSES = [
        '출고완료',
        '배송중',
        '배송완료',
    ];

    public const PAYMENT_CANCELLED_STATUSES = [
        '환불완료',
        '취소완료',
    ];

    protected $fillable = [
        'member_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'recipient_name',
        'recipient_phone',
        'postal_code',
        'address_line1',
        'address_line2',
        'shipping_message',
        'payment_method',
        'payment_status',
        'order_status',
        'shipping_status',
        'courier',
        'tracking_number',
        'admin_memo',
        'subtotal_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'ordered_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'subtotal_amount' => 'integer',
        'shipping_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'ordered_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * 주문한 회원
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

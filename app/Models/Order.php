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
        '구매확정',
        '취소완료',
        '환불완료',
    ];

    /**
     * 사용자가 직접 취소 가능한 주문 상태 목록
     */
    public const CANCELLABLE_STATUSES = [
        '주문접수',
        '상품준비중',
    ];

    public const SHIPPING_STARTED_STATUSES = [
        '배송중',
        '배송완료',
        '구매확정',
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
        'imp_uid',
        'merchant_uid',
        'payment_status',
        'order_status',
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

    /**
     * 주문과 관련된 교환/반품 신청 이력
     */
    public function claims(): HasMany
    {
        return $this->hasMany(OrderClaim::class);
    }

    /**
     * 현재 진행 중이거나 완료된 교환/반품 신청이 있는지 여부 확인
     */
    public function getHasActiveClaimAttribute(): bool
    {
        // 취소된 클레임을 제외하고 현재 유효한 클레임이 있는지 확인! 
        return $this->claims()
            ->whereNotIn('status', ['취소완료', '반려'])
            ->exists();
    }
}

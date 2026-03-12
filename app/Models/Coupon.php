<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'description',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * 쿠폰을 보유한 회원들
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'coupon_member')
            ->withPivot('used_at', 'assigned_at')
            ->withTimestamps();
    }

    /**
     * 유효한 쿠폰인지 확인 (시간 기준)
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        $now = now();
        $startValid = $this->starts_at ? $now->greaterThanOrEqualTo($this->starts_at) : true;
        $endValid = $this->ends_at ? $now->lessThanOrEqualTo($this->ends_at) : true;
        
        return $startValid && $endValid;
    }
}

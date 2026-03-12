<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const STATUSES = [
        '활성',
        '휴면',
        '정지',
    ];

    protected $fillable = [
        'name',
        'level',
        'email',
        'phone',
        'points',
        'password',
        'status',
        'provider',
        'provider_id',
        'avatar',
        'last_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
        'points' => 'integer',
    ];

    /**
     * 활성 상태인 회원만 조회하는 스코프
     */
    public function scopeActive($query)
    {
        return $query->where('status', '활성');
    }

    /**
     * 회원의 주문 목록
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 회원의 보유 쿠폰 목록 (다대다)
     */
    public function coupons(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'coupon_member')
            ->withPivot('id', 'used_at', 'assigned_at') // id 추가 완료
            ->withTimestamps();
    }

    /**
     * 현재 사용 가능한 쿠폰 목록
     */
    public function activeCoupons()
    {
        return $this->coupons()
            ->whereNull('coupon_member.used_at')
            ->where(function($query) {
                $query->whereNull('ends_at')
                      ->orWhere('ends_at', '>=', now());
            });
    }

    /**
     * 회원의 찜 목록
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * 회원의 적립금 변동 이력
     */
    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    /**
     * 회원의 장바구니 목록
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * 회원의 최근 본 상품 목록 ✨
     */
    public function recentViews()
    {
        return $this->hasMany(RecentView::class);
    }

    /**
     * 회원의 1:1 문의 목록 ✨
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }
}

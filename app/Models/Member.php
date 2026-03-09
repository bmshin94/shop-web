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
     * 회원의 주문 목록
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

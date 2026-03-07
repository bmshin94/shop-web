<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUSES = [
        '활성',
        '휴면',
        '정지',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'last_login_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}

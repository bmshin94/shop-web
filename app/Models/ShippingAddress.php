<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'address_name',
        'recipient_name',
        'phone_number',
        'zip_code',
        'address',
        'address_detail',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * 회원 관계 정의
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

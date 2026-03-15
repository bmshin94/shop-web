<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    public $timestamps = false; // created_at만 써요! 

    protected $fillable = [
        'keyword',
        'member_id',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * 검색한 회원 관계 
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

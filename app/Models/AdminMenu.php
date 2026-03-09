<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'group_name',
        'name',
        'description',
        'icon',
        'route',
        'permission_key',
        'sort_order',
        'is_active',
    ];

    /**
     * 활성화된 메뉴만 가져오는 스코프
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 자식 메뉴 관계
     */
    public function children()
    {
        return $this->hasMany(AdminMenu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * 부모 메뉴 관계
     */
    public function parent()
    {
        return $this->belongsTo(AdminMenu::class, 'parent_id');
    }
}

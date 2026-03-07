<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Authenticatable
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
        'menu_permissions',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'menu_permissions' => 'array',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * 운영자 권한으로 관리 가능한 메뉴 정의를 반환한다.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function menuDefinitions(): array
    {
        /** @var array<string, array<string, mixed>> $menus */
        $menus = config('admin_permissions.menus', []);

        return $menus;
    }

    /**
     * 운영자 권한이 설정된 메뉴 키 목록을 반환한다.
     *
     * Null(legacy)은 전체 메뉴 접근으로 해석한다.
     *
     * @return array<int, string>
     */
    public function resolvedMenuPermissions(): array
    {
        $menuKeys = array_keys(self::menuDefinitions());

        if ($this->menu_permissions === null) {
            return $menuKeys;
        }

        if (! is_array($this->menu_permissions)) {
            return [];
        }

        return array_values(array_intersect($menuKeys, $this->menu_permissions));
    }

    /**
     * 특정 메뉴 접근 권한 여부를 반환한다.
     *
     * @param  string  $menuKey
     * @return bool
     */
    public function hasMenuAccess(string $menuKey): bool
    {
        return in_array($menuKey, $this->resolvedMenuPermissions(), true);
    }
}

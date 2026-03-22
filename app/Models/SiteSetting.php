<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
    ];

    /**
     * 특정 설정 키의 값을 조회한다.
     *
     * @param  string  $key
     * @param  mixed|null  $default
     * @return mixed
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::where('setting_key', $key)->first();

        return $setting ? $setting->setting_value : $default;
    }
}

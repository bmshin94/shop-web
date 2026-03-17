<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'template_id',
        'content',
        'buttons',
        'is_active',
    ];

    protected $casts = [
        'buttons' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * 문구의 플레이스홀더(#{key})를 실제 데이터로 치환합니다.
     * 
     * @param array $data 치환할 키-값 데이터 (예: ['name' => '홍길동'])
     * @return string 치환된 메시지 본문
     */
    public function parseContent(array $data): string
    {
        $content = $this->content;
        foreach ($data as $key => $value) {
            $content = str_replace("#{{$key}}", $value, $content);
        }
        return $content;
    }

    /**
     * 버튼 정보의 URL 플레이스홀더를 치환합니다.
     * 
     * @param array $data 치환할 키-값 데이터
     * @return array 치환된 버튼 배열
     */
    public function parseButtons(array $data): array
    {
        if (empty($this->buttons)) return [];

        $buttons = $this->buttons;
        foreach ($buttons as &$button) {
            if (isset($button['url_mobile'])) {
                foreach ($data as $key => $value) {
                    $button['url_mobile'] = str_replace("#{{$key}}", $value, $button['url_mobile']);
                }
            }
            if (isset($button['url_pc'])) {
                foreach ($data as $key => $value) {
                    $button['url_pc'] = str_replace("#{{$key}}", $value, $button['url_pc']);
                }
            }
        }
        return $buttons;
    }
}

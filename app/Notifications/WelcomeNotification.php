<?php

namespace App\Notifications;

use App\Channels\AlimtalkChannel;
use App\Models\NotificationTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * 알림 전송 채널 설정
     */
    public function via(object $notifiable): array
    {
        return [AlimtalkChannel::class];
    }

    /**
     * 카카오톡 알림톡 전송 데이터 정의 (DB 템플릿 기반)
     */
    public function toAlimtalk(object $notifiable): array
    {
        // 1. DB에서 'WELCOME_JOIN' 템플릿 조회
        $template = NotificationTemplate::where('code', 'WELCOME_JOIN')->where('is_active', true)->first();

        // 2. 만약 템플릿이 없으면 기본 문구 반환 (Fallback)
        if (!$template) {
            return [
                'template_id' => 'TMP_WELCOME_DEFAULT',
                'message' => "안녕하세요, {$notifiable->name}님! 가입을 환영합니다! "
            ];
        }

        // 3. 문구 내 플레이스홀더 치환 (회원이름 등)
        $data = [
            'name' => $notifiable->name,
            'email' => $notifiable->email
        ];

        return [
            'template_id' => $template->template_id,
            'message' => $template->parseContent($data),
            'buttons' => $template->parseButtons($data)
        ];
    }
}

<?php

namespace App\Channels;

use App\Models\NotificationLog;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AlimtalkChannel
{
    /**
     * 알림을 전송합니다.
     * 
     * @param mixed $notifiable 알림을 받을 회원 모델
     * @param Notification $notification 알림 객체
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toAlimtalk')) {
            return;
        }

        $data = $notification->toAlimtalk($notifiable);
        $phone = str_replace('-', '', $notifiable->phone);
        $config = config('services.solapi');
        
        // 1. 발송 이력 데이터베이스 생성 (대기 상태)
        $log = NotificationLog::create([
            'member_id' => $notifiable->id ?? null,
            'notification_type' => class_basename($notification),
            'channel' => 'alimtalk',
            'recipient' => $phone,
            'message' => $data['message'],
            'status' => '대기',
        ]);

        if (empty($config['api_key']) || empty($config['api_secret'])) {
            $msg = '솔라피 API 설정 누락(API 키 없음)';
            Log::warning("[AlimtalkChannel] {$msg}");
            $log->update(['status' => '실패', 'error_message' => $msg]);
            return;
        }

        // 솔라피 v4 인증 헤더 생성
        $date = now()->format('Y-m-d\TH:i:s.v\Z');
        $salt = Str::random(16);
        $signature = hash_hmac('sha256', $date . $salt, $config['api_secret']);
        $authHeader = "HMAC-SHA256 apiKey={$config['api_key']}, date={$date}, salt={$salt}, signature={$signature}";

        try {
            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type' => 'application/json'
            ])->post('https://api.solapi.com/messages/v4/send-many', [
                'messages' => [
                    [
                        'to' => $phone,
                        'from' => $config['sender_number'],
                        'text' => $data['message'],
                        'kakaoOptions' => [
                            'pfId' => $config['pfid'],
                            'templateId' => $data['template_id'],
                            'buttons' => $data['buttons'] ?? [],
                            'disableSms' => false
                        ]
                    ]
                ]
            ]);

            $apiResult = $response->json();

            if ($response->successful()) {
                // 발송 요청 성공
                $log->update([
                    'status' => '성공',
                    'api_response' => $apiResult,
                    'sent_at' => now()
                ]);
                Log::info("[AlimtalkChannel] 발송 요청 성공: {$phone}");
            } else {
                // 발송 실패
                $errorMsg = $response->body();
                $log->update([
                    'status' => '실패',
                    'error_message' => $errorMsg,
                    'api_response' => $apiResult
                ]);
                Log::error("[AlimtalkChannel] 발송 실패 응답: " . $errorMsg);
                throw new \Exception("솔라피 API 응답 에러: " . $errorMsg);
            }

        } catch (\Exception $e) {
            // 통신 예외 발생
            $log->update([
                'status' => '실패',
                'error_message' => $e->getMessage()
            ]);
            Log::error("[AlimtalkChannel] 발송 중 예외 발생: " . $e->getMessage());
            throw $e; 
        }
    }
}

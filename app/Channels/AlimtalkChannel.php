<?php

namespace App\Channels;

use App\Models\NotificationLog;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlimtalkChannel
{
    /**
     * 알림톡 전송 (멀티 드라이버 지원: Solapi, Aligo)
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $data = $notification->toAlimtalk($notifiable);
        $driver = config('alimtalk.default', 'solapi');
        
        // DB 설정 우선 조회 (없으면 config/env 값 사용) 🚀
        $testModeSetting = \App\Models\SiteSetting::where('setting_key', 'alimtalk_test_mode')->first();
        $isTestMode = $testModeSetting ? filter_var($testModeSetting->setting_value, FILTER_VALIDATE_BOOLEAN) : config('alimtalk.test_mode', true);

        // 테스트 모드가 활성화되어 있으면 실제로 발송하지 않고 로그만 남기고 이력 기록! 🚀
        if ($isTestMode) {
            Log::info("[Alimtalk TestMode] To: " . ($data['recipient'] ?? $notifiable->phone) . ", Msg: " . $data['message']);
            $this->logToDatabase($notifiable, $notification, $data, '성공(테스트모드)', null, ['testMode' => true]);
            return;
        }

        if ($driver === 'aligo') {
            $this->sendByAligo($notifiable, $notification, $data);
        } else {
            $this->sendBySolapi($notifiable, $notification, $data);
        }
    }

    /**
     * 솔라피(Solapi) 발송 엔진 (자동 Fallback 지원)
     */
    protected function sendBySolapi($notifiable, $notification, $data): void
    {
        $config = config('services.solapi');
        if (empty($config['api_key']) || empty($config['api_secret'])) {
            $this->logToDatabase($notifiable, $notification, $data, '실패', 'Solapi API 설정 누락');
            return;
        }

        try {
            $date = date('Y-m-d\TH:i:s.v\Z');
            $salt = uniqid();
            $signature = hash_hmac('sha256', $date . $salt, $config['api_secret']);
            $authHeader = "HMAC-SHA256 apiKey={$config['api_key']}, date={$date}, salt={$salt}, signature={$signature}";

            $response = Http::withHeaders(['Authorization' => $authHeader])
                ->post('https://api.solapi.com/messages/v4/send-many', [
                    'messages' => [
                        [
                            'to' => str_replace('-', '', $data['recipient'] ?? $notifiable->phone),
                            'from' => $config['sender_number'],
                            'text' => $data['message'], // 알림톡 실패 시 문자로 나갈 본문
                            'kakaoOptions' => [
                                'pfId' => $config['pfid'],
                                'templateId' => $data['template_id'],
                                'buttons' => $data['buttons'] ?? [],
                                'disableSms' => false // 카톡 실패 시 문자 발송 활성화 (기본값)
                            ]
                        ]
                    ]
                ]);

            $result = $response->json();
            if ($response->successful()) {
                $this->logToDatabase($notifiable, $notification, $data, '성공', null, $result);
            } else {
                $this->logToDatabase($notifiable, $notification, $data, '실패', $result['errorMessage'] ?? 'Solapi 에러', $result);
            }
        } catch (\Exception $e) {
            $this->logToDatabase($notifiable, $notification, $data, '실패', $e->getMessage());
        }
    }

    /**
     * 알리고(Aligo) 발송 엔진 (자동 Fallback 지원)
     */
    protected function sendByAligo($notifiable, $notification, $data): void
    {
        $config = config('services.aligo');
        if (empty($config['api_key']) || empty($config['user_id'])) {
            $this->logToDatabase($notifiable, $notification, $data, '실패', 'Aligo API 설정 누락');
            return;
        }

        try {
            $buttonData = [];
            if (!empty($data['buttons'])) {
                foreach ($data['buttons'] as $index => $button) {
                    $buttonData['button_' . ($index + 1)] = json_encode([
                        'name' => $button['name'],
                        'linkType' => $button['type'],
                        'linkMo' => $button['url_mobile'],
                        'linkPc' => $button['url_pc']
                    ]);
                }
            }

            // 알리고 알림톡 전송 + 대체발송(failover) 설정
            $response = Http::asForm()->post('https://kakaoapi.aligo.in/akv10/alimtalk/send/', array_merge([
                'apikey' => $config['api_key'],
                'userid' => $config['user_id'],
                'senderkey' => $config['sender_key'],
                'tpl_code' => $data['template_id'],
                'sender' => $config['sender_number'],
                'receiver_1' => str_replace('-', '', $data['recipient'] ?? $notifiable->phone),
                'message_1' => $data['message'],
                'testMode' => app()->environment('production') ? 'N' : 'Y',
                'failover' => 'Y', // 카톡 실패 시 문자 대체 발송 활성화
                'fsubject_1' => '알림톡 대체발송', // 대체발송 제목
                'fmessage_1' => $data['message'], // 대체발송 내용
            ], $buttonData));

            $result = $response->json();
            if ($response->successful() && isset($result['result_code']) && $result['result_code'] == '1') {
                $this->logToDatabase($notifiable, $notification, $data, '성공', null, $result);
            } else {
                $this->logToDatabase($notifiable, $notification, $data, '실패', $result['message'] ?? 'Aligo 에러', $result);
            }
        } catch (\Exception $e) {
            $this->logToDatabase($notifiable, $notification, $data, '실패', $e->getMessage());
        }
    }

    /**
     * 발송 이력 DB 기록
     */
    protected function logToDatabase($notifiable, $notification, $data, $status, $error = null, $apiResponse = null): void
    {
        NotificationLog::create([
            'member_id' => $notifiable->id,
            'notification_type' => class_basename($notification),
            'channel' => 'alimtalk',
            'recipient' => $data['recipient'] ?? $notifiable->phone,
            'message' => $data['message'],
            'status' => $status,
            'error_message' => $error,
            'api_response' => $apiResponse,
            'sent_at' => $status === '성공' ? now() : null,
        ]);
    }
}

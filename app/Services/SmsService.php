<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * 문자 발송 (멀티 드라이버 지원: Solapi, Aligo)
     */
    public function sendSms($receiver, $message)
    {
        $driver = config('alimtalk.default', 'solapi');
        
        // DB 설정 우선 조회 (없으면 config/env 값 사용) 🚀
        $testModeSetting = \App\Models\SiteSetting::where('setting_key', 'alimtalk_test_mode')->first();
        $isTestMode = $testModeSetting ? filter_var($testModeSetting->setting_value, FILTER_VALIDATE_BOOLEAN) : config('alimtalk.test_mode', true);

        // 테스트 모드가 활성화되어 있으면 실제로 발송하지 않고 로그만 남김 🚀
        if ($isTestMode) {
            Log::info("[SmsService TestMode] To: {$receiver}, Msg: {$message}");
            return ['result_code' => 1, 'message' => 'success (test mode)'];
        }

        if ($driver === 'aligo') {
            return $this->sendByAligo($receiver, $message);
        } else {
            return $this->sendBySolapi($receiver, $message);
        }
    }

    /**
     * 솔라피(Solapi)를 통한 문자 발송
     */
    protected function sendBySolapi($receiver, $message)
    {
        $config = config('services.solapi');
        if (empty($config['api_key']) || empty($config['api_secret'])) {
            return ['result_code' => -1, 'message' => 'Solapi API 설정 누락'];
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
                            'to' => str_replace('-', '', $receiver),
                            'from' => $config['sender_number'],
                            'text' => $message,
                            'type' => 'SMS'
                        ]
                    ]
                ]);

            if ($response->successful()) {
                return ['result_code' => 1, 'message' => 'success'];
            }
            
            return ['result_code' => -1, 'message' => $response->json()['errorMessage'] ?? 'Solapi Error'];
        } catch (\Exception $e) {
            Log::error("Solapi SMS Exception: " . $e->getMessage());
            return ['result_code' => -1, 'message' => $e->getMessage()];
        }
    }

    /**
     * 알리고(Aligo)를 통한 문자 발송
     */
    protected function sendByAligo($receiver, $message)
    {
        $config = config('services.aligo');
        if (empty($config['api_key']) || empty($config['user_id'])) {
            return ['result_code' => -1, 'message' => 'Aligo API 설정 누락'];
        }

        try {
            $response = Http::asForm()->post('https://apis.aligo.in/send/', [
                'key' => $config['api_key'],
                'user_id' => $config['user_id'],
                'sender' => $config['sender_number'],
                'receiver' => str_replace('-', '', $receiver),
                'msg' => $message,
                'test_mode_yn' => app()->environment('production') ? 'N' : 'Y',
            ]);

            $result = $response->json();
            if ($response->successful() && isset($result['result_code']) && $result['result_code'] == '1') {
                return ['result_code' => 1, 'message' => 'success'];
            }

            return ['result_code' => -1, 'message' => $result['message'] ?? 'Aligo Error'];
        } catch (\Exception $e) {
            Log::error("Aligo SMS Exception: " . $e->getMessage());
            return ['result_code' => -1, 'message' => $e->getMessage()];
        }
    }
}

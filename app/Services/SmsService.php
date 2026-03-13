<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $client;
    protected $apiKey;
    protected $userId;
    protected $sender;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://apis.aligo.in/',
            'timeout'  => 5.0,
        ]);
        $this->apiKey = env('ALIGO_API_KEY');
        $this->userId = env('ALIGO_USER_ID');
        $this->sender = env('ALIGO_SENDER'); // 등록된 발신번호
    }

    /**
     * 알리고 API를 통한 문자 발송
     */
    public function sendSms($receiver, $message)
    {
        // 로컬 환경이나 테스트 환경에서는 실제로 보내지 않고 로그만 남김 ✨
        if ((app()->environment('local', 'testing') || empty($this->apiKey))) {
            Log::info("SMS Mock Send [To: {$receiver}] [Msg: {$message}]");
            return ['result_code' => 1, 'message' => 'success (mock)'];
        }

        try {
            Log::info("SMS Attempting to send via Aligo API to {$receiver}");
            $response = $this->client->post('send/', [
                'form_params' => [
                    'key' => $this->apiKey,
                    'user_id' => $this->userId,
                    'sender' => $this->sender,
                    'receiver' => $receiver,
                    'msg' => $message,
                    'test_mode_yn' => app()->environment('production') ? 'N' : 'Y',
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            if ($result['result_code'] < 0) {
                Log::error("Aligo SMS Error: Code[" . $result['result_code'] . "] Msg[" . ($result['message'] ?? 'Unknown') . "]");
            }

            return $result;
        } catch (\Exception $e) {
            Log::error("SmsService Exception: " . $e->getMessage());
            return ['result_code' => -1, 'message' => $e->getMessage()];
        }
    }
}

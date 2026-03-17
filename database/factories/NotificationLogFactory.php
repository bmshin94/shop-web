<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\NotificationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationLog>
 */
class NotificationLogFactory extends Factory
{
    protected $model = NotificationLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement(['성공', '실패', '대기']);
        $channel = $this->faker->randomElement(['alimtalk', 'sms']);
        
        return [
            'member_id' => Member::inRandomOrder()->first()?->id,
            'notification_type' => $this->faker->randomElement(['WelcomeNotification', 'OrderNotification', 'PointNotification']),
            'channel' => $channel,
            'recipient' => '010' . $this->faker->numerify('########'),
            'message' => $this->faker->sentence(10),
            'status' => $status,
            'error_message' => $status === '실패' ? '발송 실패: 수신 거부 또는 번호 오류' : null,
            'api_response' => [
                'requestId' => 'REQ-' . $this->faker->uuid,
                'status' => $status === '성공' ? 'SEND_SUCCESS' : ($status === '실패' ? 'FAILED' : 'PENDING'),
            ],
            'sent_at' => $status === '성공' ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }
}

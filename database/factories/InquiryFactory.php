<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement(['답변대기', '답변완료']),
            'answer' => function (array $attributes) {
                return $attributes['status'] === '답변완료' ? $this->faker->paragraph(2) : null;
            },
            'answered_at' => function (array $attributes) {
                return $attributes['status'] === '답변완료' ? now() : null;
            },
        ];
    }
}

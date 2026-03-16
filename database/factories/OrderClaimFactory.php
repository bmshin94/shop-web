<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Order;
use App\Models\OrderClaim;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderClaim>
 */
class OrderClaimFactory extends Factory
{
    protected $model = OrderClaim::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'order_id' => Order::factory(),
            'claim_number' => 'C' . now()->format('Ymd') . strtoupper($this->faker->unique()->bothify('??###')),
            'type' => $this->faker->randomElement([OrderClaim::TYPE_EXCHANGE, OrderClaim::TYPE_RETURN]),
            'reason_type' => $this->faker->randomElement(['단순 변심', '상품 불량', '오배송']),
            'reason_detail' => $this->faker->sentence(),
            'status' => OrderClaim::STATUS_RECEIVED,
            'admin_memo' => null,
            'processed_at' => null,
        ];
    }
}

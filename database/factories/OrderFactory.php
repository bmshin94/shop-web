<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * 기본 주문 데이터를 생성한다.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotalAmount = fake()->numberBetween(30000, 180000);
        $shippingAmount = $subtotalAmount >= 50000 ? 0 : 3000;
        $discountAmount = fake()->randomElement([0, 2000, 5000, 10000]);
        $orderedAt = now()->subDays(fake()->numberBetween(0, 14))->subHours(fake()->numberBetween(0, 23));

        return [
            'order_number' => 'HF' . $orderedAt->format('Ymd') . fake()->unique()->numerify('####'),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => fake()->numerify('010-####-####'),
            'recipient_name' => fake()->name(),
            'recipient_phone' => fake()->numerify('010-####-####'),
            'postal_code' => fake()->numerify('#####'),
            'address_line1' => fake()->streetAddress(),
            'address_line2' => fake()->secondaryAddress(),
            'shipping_message' => fake()->randomElement([
                '문 앞에 놓아주세요.',
                '배송 전에 연락 부탁드립니다.',
                null,
            ]),
            'payment_method' => fake()->randomElement(Order::PAYMENT_METHODS),
            'payment_status' => '결제완료',
            'order_status' => '주문접수',
            'courier' => null,
            'tracking_number' => null,
            'admin_memo' => null,
            'subtotal_amount' => $subtotalAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => min($discountAmount, $subtotalAmount),
            'total_amount' => $subtotalAmount + $shippingAmount - min($discountAmount, $subtotalAmount),
            'ordered_at' => $orderedAt,
            'shipped_at' => null,
            'delivered_at' => null,
        ];
    }

    /**
     * 배송중 주문 상태를 생성한다.
     */
    public function shippingInProgress(): static
    {
        return $this->state(function (): array {
            $shippedAt = now()->subDays(1);

            return [
                'order_status' => '배송중',
                'courier' => 'CJ대한통운',
                'tracking_number' => fake()->numerify('###########'),
                'shipped_at' => $shippedAt,
                'delivered_at' => null,
            ];
        });
    }

    /**
     * 배송완료 주문 상태를 생성한다.
     */
    public function delivered(): static
    {
        return $this->state(function (): array {
            $deliveredAt = now()->subHours(6);

            return [
                'order_status' => '배송완료',
                'courier' => 'CJ대한통운',
                'tracking_number' => fake()->numerify('###########'),
                'shipped_at' => $deliveredAt->copy()->subDays(2),
                'delivered_at' => $deliveredAt,
            ];
        });
    }

    /**
     * 취소완료 주문 상태를 생성한다.
     */
    public function cancelled(): static
    {
        return $this->state(fn (): array => [
            'payment_status' => '환불완료',
            'order_status' => '취소완료',
            'courier' => null,
            'tracking_number' => null,
            'shipped_at' => null,
            'delivered_at' => null,
        ]);
    }
}

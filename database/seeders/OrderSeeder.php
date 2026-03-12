<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Member;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * 관리자 주문/배송 관리 화면용 샘플 주문을 생성한다.
     */
    public function run(): void
    {
        // 기존 데이터 삭제 (중복 방지)
        Order::query()->delete();
        
        $products = Product::all();
        $members = Member::all();

        if ($products->isEmpty()) {
            $this->command->warn('Product data not found. Please run ProductSeeder first.');
            return;
        }

        // 20개의 주문 생성
        for ($i = 0; $i < 20; $i++) {
            $member = $members->isNotEmpty() ? $members->random() : null;
            
            // 다양한 주문 상태를 골고루 생성
            $status = fake()->randomElement(['주문접수', '상품준비중', '배송중', '배송완료', '구매확정', '취소완료']);
            
            $order = Order::factory()->create([
                'member_id' => $member?->id,
                'customer_name' => $member?->name ?? fake()->name(),
                'customer_email' => $member?->email ?? fake()->safeEmail(),
                'customer_phone' => $member?->phone ?? '010-' . fake()->numerify('####-####'),
                'order_status' => $status,
                'payment_status' => ($status === '취소완료') ? '환불완료' : '결제완료',
                'shipping_status' => in_array($status, ['배송중', '배송완료', '구매확정']) ? $status : '배송대기',
            ]);

            // 주문당 1~3개의 상품 추가
            $itemCount = fake()->numberBetween(1, 3);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = fake()->numberBetween(1, 2);
                $unitPrice = $product->sale_price ?: $product->price;
                $lineTotal = $unitPrice * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'option_summary' => '색상: 기본 / 사이즈: FREE',
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ]);

                $subtotal += $lineTotal;
            }

            // 배송비 및 최종 금액 업데이트
            $shippingAmount = $subtotal >= 50000 ? 0 : 3000;
            $discountAmount = fake()->randomElement([0, 0, 0, 5000]); // 25% 확률로 5000원 할인
            
            $order->update([
                'subtotal_amount' => $subtotal,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => max(0, $subtotal + $shippingAmount - $discountAmount),
            ]);
        }
    }
}

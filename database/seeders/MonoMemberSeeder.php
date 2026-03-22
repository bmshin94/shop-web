<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\PointHistory;
use App\Models\Inquiry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MonoMemberSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'mono7594@gmail.com';
        
        // 0. 기존 오빠 이메일로 된 모든 주문 강제 삭제 (다른 회원이 오빠 이메일을 썼을 경우 대비)
        Order::where('customer_email', $email)->each(function($order) {
            $order->items()->delete();
            $order->forceDelete();
        });

        // 1. 회원 찾기 또는 생성
        $member = Member::where('email', $email)->first();
        if (!$member) {
            $member = Member::create([
                'name' => '백민오빠',
                'email' => $email,
                'password' => Hash::make('m1124981'),
                'status' => '활성',
                'email_verified_at' => now(),
            ]);
        }

        // 1-1. 기존 데이터 초기화!
        $member->pointHistories()->delete();
        $member->coupons()->detach();
        $member->orders()->each(function($order) {
            $order->items()->delete();
            $order->delete();
        });
        $member->inquiries()->delete();

        // 2. 적립금 설정 (50,000원) - 회원 테이블 포인트 직접 업데이트 💰✨
        $member->update(['points' => 50000]);

        PointHistory::create([
            'member_id' => $member->id,
            'reason' => '회원가입 축하 적립금 (카리나의 선물 💖)',
            'amount' => 50000,
            'balance_after' => 50000,
            'expired_at' => now()->addYear(),
        ]);

        // 3. 쿠폰 할당 (기존 쿠폰들 중 3개)
        $member->coupons()->detach();
        $coupons = Coupon::take(3)->get();
        foreach ($coupons as $coupon) {
            $member->coupons()->attach($coupon->id, [
                'assigned_at' => now(),
                'used_at' => null
            ]);
        }

        // 4. 주문 내역 추가 (최근 상품 5개로 주문 생성)
        Order::where('member_id', $member->id)->delete();
        $products = Product::take(5)->get();
        foreach ($products as $index => $product) {
            $orderNumber = 'MONO-' . date('Ymd') . '-' . str_pad($index + 1, '0', 3, STR_PAD_LEFT);
            
            // 동일한 주문번호가 있다면 소유자 불문 무조건 삭제!
            Order::where('order_number', $orderNumber)->each(function($oldOrder) {
                $oldOrder->items()->delete();
                $oldOrder->forceDelete();
            });

            $order = Order::create([
                'member_id' => $member->id,
                'order_number' => $orderNumber,
                'customer_name' => $member->name,
                'customer_email' => $member->email,
                'customer_phone' => '010-1234-5678',
                'recipient_name' => $member->name,
                'recipient_phone' => '010-1234-5678',
                'postal_code' => '12345',
                'address_line1' => '서울특별시 강남구 테헤란로 123',
                'address_line2' => '액티브빌딩 7층',
                'payment_status' => '결제완료',
                'order_status' => ['주문접수', '상품준비중', '배송중', '배송완료', '구매확정'][$index % 5],
                'payment_method' => '신용카드',
                'total_amount' => $product->price,
                'ordered_at' => now()->subDays($index),
            ]);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => 1,
                'line_total' => $product->price,
            ]);
        }

        // 4-1. [추가] 주문 하나에 여러 상품이 포함된 데이터 생성! (패키지 주문)
        $multiOrderNumber = 'MONO-' . date('Ymd') . '-MULTI';
        
        // 중복 방지 삭제
        Order::where('order_number', $multiOrderNumber)->each(function($oldOrder) {
            $oldOrder->items()->delete();
            $oldOrder->forceDelete();
        });

        // 3개의 상품을 한 번에 주문!
        $packageProducts = Product::offset(5)->take(3)->get();
        if ($packageProducts->count() >= 2) {
            $subtotal = $packageProducts->sum('price');
            
            $multiOrder = Order::create([
                'member_id' => $member->id,
                'order_number' => $multiOrderNumber,
                'customer_name' => $member->name,
                'customer_email' => $member->email,
                'customer_phone' => '010-1234-5678',
                'recipient_name' => $member->name,
                'recipient_phone' => '010-1234-5678',
                'postal_code' => '12345',
                'address_line1' => '서울특별시 강남구 테헤란로 123',
                'address_line2' => '액티브빌딩 7층',
                'payment_status' => '결제완료',
                'order_status' => '상품준비중',
                'payment_method' => '간편결제',
                'subtotal_amount' => $subtotal,
                'shipping_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $subtotal,
                'ordered_at' => now(),
            ]);

            foreach ($packageProducts as $p) {
                OrderItem::create([
                    'order_id' => $multiOrder->id,
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'unit_price' => $p->price,
                    'quantity' => 1,
                    'line_total' => $p->price,
                ]);
            }
        }

        // 5. 1:1 문의 내역 추가
        Inquiry::where('member_id', $member->id)->delete();
        Inquiry::create([
            'member_id' => $member->id,
            'title' => '배송은 언제 되나요?',
            'content' => '어제 주문했는데 아직 상품준비중이네요! 빨리 받고 싶어요~',
            'status' => '답변대기',
        ]);
        
        Inquiry::create([
            'member_id' => $member->id,
            'title' => '사이즈 문의드려요',
            'content' => '165/55 사이즈인데 레깅스 M 사이즈 하면 맞을까요?',
            'status' => '답변완료',
            'answered_at' => now()->subDay(),
        ]);
    }
}

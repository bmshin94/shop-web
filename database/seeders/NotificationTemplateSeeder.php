<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * 알림 템플릿 시더 실행
     * 
     * @return void
     */
    public function run(): void
    {
        // 1. 휴대폰 본인 인증 번호 발송 템플릿 (SMS 전용)
        NotificationTemplate::updateOrCreate(
            ['code' => 'VERIFICATION_CODE'],
            [
                'send_type' => 'sms',
                'name' => '본인 인증 번호 발송',
                'template_id' => null,
                'content' => "[Active Women] 인증번호 [#{code}]를 입력해주세요. 타인에게 노출되지 않도록 주의하세요.",
                'buttons' => null,
                'is_active' => true,
            ]
        );

        // 2. 회원가입 축하 알림 템플릿
        NotificationTemplate::updateOrCreate(
            ['code' => 'WELCOME_JOIN'],
            [
                'send_type' => 'alimtalk',
                'name' => '회원가입 환영 인사',
                'template_id' => 'TMP_WELCOME_001',
                'content' => "[Active Women] 가입을 환영합니다! \n\n" .
                             "안녕하세요, #{name}님! \n" .
                             "저희 쇼핑몰의 소중한 회원이 되신 것을 진심으로 축하드립니다. \n\n" .
                             "지금 바로 웰컴 쿠폰을 확인하고 즐거운 쇼핑을 시작해보세요!",
                'buttons' => [
                    [
                        'name' => '쇼핑몰 바로가기',
                        'type' => 'WL',
                        'url_mobile' => 'https://shop.example.com/',
                        'url_pc' => 'https://shop.example.com/'
                    ],
                    [
                        'name' => '쿠폰함 확인하기',
                        'type' => 'WL',
                        'url_mobile' => 'https://shop.example.com/mypage/coupon',
                        'url_pc' => 'https://shop.example.com/mypage/coupon'
                    ]
                ],
                'is_active' => true,
            ]
        );

        // 3. 주문 완료 알림 템플릿
        NotificationTemplate::updateOrCreate(
            ['code' => 'ORDER_COMPLETED'],
            [
                'send_type' => 'alimtalk',
                'name' => '주문 완료 안내',
                'template_id' => 'TMP_ORDER_001',
                'content' => "[Active Women] 주문이 정상적으로 완료되었습니다. \n\n" .
                             "주문번호: #{order_number} \n" .
                             "주문상품: #{product_name} \n" .
                             "결제금액: #{amount}원 \n\n" .
                             "정성껏 준비하여 빠르게 배송해드리겠습니다.",
                'buttons' => [
                    [
                        'name' => '주문내역 확인',
                        'type' => 'WL',
                        'url_mobile' => 'https://shop.example.com/mypage/order-list',
                        'url_pc' => 'https://shop.example.com/mypage/order-list'
                    ]
                ],
                'is_active' => true,
            ]
        );

        // 4. 배송 시작 알림 템플릿
        NotificationTemplate::updateOrCreate(
            ['code' => 'SHIPPING_STARTED'],
            [
                'send_type' => 'alimtalk',
                'name' => '배송 시작 안내',
                'template_id' => 'TMP_SHIPPING_001',
                'content' => "[Active Women] 상품 배송이 시작되었습니다. \n\n" .
                             "운송장번호: #{tracking_number} (#{carrier}) \n" .
                             "조금만 기다려주시면 곧 상품이 도착합니다!",
                'buttons' => [
                    [
                        'name' => '배송 추적하기',
                        'type' => 'WL',
                        'url_mobile' => 'https://shop.example.com/mypage/order-list',
                        'url_pc' => 'https://shop.example.com/mypage/order-list'
                    ]
                ],
                'is_active' => true,
            ]
        );
    }
}

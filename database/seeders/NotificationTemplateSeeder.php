<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationTemplate::truncate();

        // 1. 회원가입 축하 알림 템플릿
        NotificationTemplate::create([
            'code' => 'WELCOME_JOIN',
            'name' => '회원가입 환영 인사',
            'template_id' => 'TMP_WELCOME_001',
            'content' => "[Premium Store] 가입을 환영합니다! \n\n" .
                         "안녕하세요, #{{name}}님! \n" .
                         "저희 쇼핑몰의 소중한 회원이 되신 것을 진심으로 축하드립니다. \n\n" .
                         "지금 바로 웰컴 쿠폰을 확인하고 즐거운 쇼핑을 시작해보세요! ",
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
        ]);
        
        // 추가로 '주문 완료' 같은 템플릿도 미리 만들어둘 수 있어! ✨
    }
}

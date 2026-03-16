<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * 관리자 이벤트 관리 화면용 샘플 데이터를 생성한다.
     *
     * @return void
     */
    public function run(): void
    {
        $events = [
            [
                'title' => '3월 봄맞이 전 상품 10% 쿠폰',
                'slug' => 'march-spring-coupon',
                'banner_image_url' => 'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=1200&h=628&fit=crop',
                'summary' => '봄 시즌 한정 쿠폰 이벤트',
                'description' => '회원 등급 상관없이 전 상품 10% 쿠폰을 지급하는 이벤트입니다.',
                'start_at' => now()->subDays(2),
                'end_at' => now()->addDays(5),
                'sort_order' => 1,
            ],
            [
                'title' => '신규 회원 가입 웰컴 포인트 지급',
                'slug' => 'new-member-welcome-point',
                'banner_image_url' => 'https://images.unsplash.com/photo-1511988617509-a57c8a288659?w=1200&h=628&fit=crop',
                'summary' => '신규 가입 즉시 5,000P 지급',
                'description' => '기간 내 신규 회원에게 웰컴 포인트를 제공합니다.',
                'start_at' => now()->addDays(3),
                'end_at' => now()->addDays(15),
                'sort_order' => 2,
            ],
            [
                'title' => '가을 기획 특가전 종료 이벤트',
                'slug' => 'fall-special-closing',
                'banner_image_url' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?w=1200&h=628&fit=crop',
                'summary' => '종료된 프로모션 샘플',
                'description' => '이벤트 관리 화면에서 종료 상태 확인용 데이터입니다.',
                'start_at' => now()->subDays(20),
                'end_at' => now()->subDays(5),
                'sort_order' => 3,
            ],
            [
                'title' => '앱 전용 비공개 사전 예약 이벤트',
                'slug' => 'app-private-preorder-event',
                'banner_image_url' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200&h=628&fit=crop',
                'summary' => '관리자 전용 비노출 샘플',
                'description' => '비노출 상태 관리 확인을 위한 샘플 데이터입니다.',
                'start_at' => now()->addDays(7),
                'end_at' => now()->addDays(20),
                'sort_order' => 4,
            ],
        ];

        foreach ($events as $event) {
            $model = Event::withTrashed()->firstOrNew([
                'slug' => $event['slug'],
            ]);

            $model->fill($event);
            $model->save();

            if ($model->trashed()) {
                $model->restore();
            }
        }
    }
}

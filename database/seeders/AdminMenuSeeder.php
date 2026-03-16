<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminMenu::query()->delete();

        // 1. 대시보드
        AdminMenu::create([
            'name' => '대시보드',
            'description' => '운영 지표와 요약 현황 조회',
            'icon' => 'dashboard',
            'route' => 'admin.dashboard',
            'permission_key' => 'dashboard',
            'sort_order' => 1,
        ]);

        // 2. 쇼핑몰 관리 그룹
        $mallGroup = '쇼핑몰 관리';
        $mallMenus = [
            ['name' => '카테고리 관리', 'description' => '카테고리 등록/수정/정렬', 'icon' => 'category', 'route' => 'admin.categories.index', 'permission_key' => 'categories', 'sort_order' => 10],
            ['name' => '색상 관리', 'description' => '상품 색상 등록/수정/삭제', 'icon' => 'palette', 'route' => 'admin.colors.index', 'permission_key' => 'colors', 'sort_order' => 11],
            ['name' => '사이즈 관리', 'description' => '상품 사이즈 및 그룹 등록/삭제', 'icon' => 'straighten', 'route' => 'admin.sizes.index', 'permission_key' => 'sizes', 'sort_order' => 12],
            ['name' => '상품 관리', 'description' => '상품 등록/수정/삭제', 'icon' => 'inventory_2', 'route' => 'admin.products.index', 'permission_key' => 'products', 'sort_order' => 13],
            ['name' => '리뷰 관리', 'description' => '고객 상품평 조회 및 관리', 'icon' => 'reviews', 'route' => 'admin.reviews.index', 'permission_key' => 'reviews', 'sort_order' => 14],
            ['name' => '주문/배송 관리', 'description' => '주문 상태 및 배송 상태 관리', 'icon' => 'shopping_cart', 'route' => 'admin.orders.index', 'permission_key' => 'orders', 'sort_order' => 15],
            ['name' => '회원 관리', 'description' => '회원 조회 및 상태 변경', 'icon' => 'group', 'route' => 'admin.members.index', 'permission_key' => 'members', 'sort_order' => 16],
            ['name' => '쿠폰 관리', 'description' => '쿠폰 생성 및 발급 내역 관리', 'icon' => 'confirmation_number', 'route' => 'admin.coupons.index', 'permission_key' => 'coupons', 'sort_order' => 17],
            ['name' => '적립금 관리', 'description' => '회원 적립금 변동 내역 및 지급/차감', 'icon' => 'monetization_on', 'route' => 'admin.points.index', 'permission_key' => 'points', 'sort_order' => 18],
            ['name' => '검색 로그 관리', 'description' => '사용자 인기 검색어 및 로그 분석', 'icon' => 'search_insights', 'route' => 'admin.search-logs.index', 'permission_key' => 'search_logs', 'sort_order' => 19],
        ];

        foreach ($mallMenus as $menu) {
            AdminMenu::create(array_merge($menu, ['group_name' => $mallGroup]));
        }

        // 3. 운영 관리 그룹
        $opGroup = '운영 관리';
        $opMenus = [
            ['name' => '메뉴 관리', 'description' => '관리자 사이드바 메뉴 관리', 'icon' => 'menu_open', 'route' => 'admin.menus.index', 'permission_key' => 'menus', 'sort_order' => 20],
            ['name' => '운영자 관리', 'description' => '운영자 계정/권한 관리', 'icon' => 'badge', 'route' => 'admin.operators.index', 'permission_key' => 'operators', 'sort_order' => 21],
            ['name' => '이벤트 관리', 'description' => '이벤트 등록/노출 상태 관리', 'icon' => 'campaign', 'route' => 'admin.events.index', 'permission_key' => 'events', 'sort_order' => 22],
            ['name' => '기획전 관리', 'description' => '기획전 등록/노출 상태 관리', 'icon' => 'storefront', 'route' => 'admin.exhibitions.index', 'permission_key' => 'exhibitions', 'sort_order' => 23],
            ['name' => '고객센터 문의', 'description' => '고객 문의 내역 관리', 'icon' => 'support_agent', 'route' => 'admin.inquiries.index', 'permission_key' => 'qna', 'sort_order' => 24],
            ['name' => '공지사항 관리', 'description' => '쇼핑몰 주요 공지사항 관리', 'icon' => 'notifications', 'route' => 'admin.notices.index', 'permission_key' => 'notices', 'sort_order' => 25],
            ['name' => 'FAQ 관리', 'description' => '자주 묻는 질문 관리', 'icon' => 'quiz', 'route' => 'admin.faqs.index', 'permission_key' => 'faqs', 'sort_order' => 26],
            ['name' => '매거진 관리', 'description' => '쇼핑몰 매거진/트렌드 아티클 관리', 'icon' => 'auto_stories', 'route' => 'admin.magazines.index', 'permission_key' => 'magazines', 'sort_order' => 27],
            ['name' => 'OOTD 관리', 'description' => '사용자 OOTD 게시물 관리', 'icon' => 'checkroom', 'route' => 'admin.ootds.index', 'permission_key' => 'ootds', 'sort_order' => 28],
            ['name' => '기본 설정', 'description' => '사이트 기본 운영 설정', 'icon' => 'settings', 'route' => 'admin.settings.index', 'permission_key' => 'settings', 'sort_order' => 30],
        ];

        foreach ($opMenus as $menu) {
            AdminMenu::create(array_merge($menu, ['group_name' => $opGroup]));
        }
    }
}

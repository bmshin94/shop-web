<?php

return [
    'session_key' => 'admin_operator_id',

    'menus' => [
        'dashboard' => [
            'label' => '대시보드',
            'description' => '운영 지표와 요약 현황 조회',
            'route' => 'admin.dashboard',
            'patterns' => ['admin.dashboard'],
        ],
        'categories' => [
            'label' => '카테고리 관리',
            'description' => '카테고리 등록/수정/정렬',
            'route' => 'admin.categories.index',
            'patterns' => ['admin.categories.*'],
        ],
        'products' => [
            'label' => '상품 관리',
            'description' => '상품 등록/수정/삭제',
            'route' => 'admin.products.index',
            'patterns' => ['admin.products.*'],
        ],
        'orders' => [
            'label' => '주문/배송 관리',
            'description' => '주문 상태 및 배송 상태 관리',
            'route' => 'admin.orders.index',
            'patterns' => ['admin.orders.*'],
        ],
        'members' => [
            'label' => '회원 관리',
            'description' => '회원 조회 및 상태 변경',
            'route' => 'admin.members.index',
            'patterns' => ['admin.members.*'],
        ],
        'operators' => [
            'label' => '운영자 관리',
            'description' => '운영자 계정/권한 관리',
            'route' => 'admin.operators.index',
            'patterns' => ['admin.operators.*'],
        ],
        'events' => [
            'label' => '이벤트 관리',
            'description' => '이벤트 등록/노출 상태 관리',
            'route' => 'admin.events.index',
            'patterns' => ['admin.events.*'],
        ],
        'exhibitions' => [
            'label' => '기획전 관리',
            'description' => '기획전 등록/노출 상태 관리',
            'route' => 'admin.exhibitions.index',
            'patterns' => ['admin.exhibitions.*'],
        ],
        'settings' => [
            'label' => '기본 설정',
            'description' => '사이트 기본 운영 설정',
            'route' => 'admin.settings.index',
            'patterns' => ['admin.settings.*'],
        ],
    ],
];


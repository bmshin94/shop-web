<?php

return [
    'required' => ':attribute 항목은 필수입니다.',
    'string' => ':attribute 항목은 문자열이어야 합니다.',
    'max' => [
        'string' => ':attribute 항목은 :max자 이하로 입력해주세요.',
    ],
    'unique' => '이미 사용 중인 :attribute입니다.',
    'exists' => '선택한 :attribute 항목이 유효하지 않습니다.',
    'integer' => ':attribute 항목은 정수여야 합니다.',
    'min' => [
        'numeric' => ':attribute 항목은 최소 :min 이상이어야 합니다.',
    ],
    'in' => '선택한 :attribute 항목이 유효하지 않습니다.',
    'url' => ':attribute 항목의 형식이 올바르지 않습니다.',
    'boolean' => ':attribute 항목은 참/거짓이어야 합니다.',
    'lt' => [
        'numeric' => ':attribute 항목은 :value보다 작아야 합니다.',
    ],

    'attributes' => [
        'name' => '이름',
        'slug' => '슬러그',
        'category_id' => '카테고리',
        'parent_id' => '상위 카테고리',
        'price' => '판매가',
        'sale_price' => '할인 판매가',
        'stock_quantity' => '재고 수량',
        'status' => '판매 상태',
        'description' => '상품 설명',
        'image_url' => '이미지 URL',
        'is_new' => '신상품 여부',
        'is_best' => '베스트 상품 여부',
        'is_active' => '노출 상태',
        'sort_order' => '정렬 순서',
    ],
];

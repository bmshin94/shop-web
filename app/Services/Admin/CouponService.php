<?php

namespace App\Services\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CouponService
{
    /**
     * 쿠폰 목록 조회 및 필터링
     */
    public function getCouponList(Request $request): LengthAwarePaginator
    {
        $query = Coupon::query()->latest();

        // 1. 키워드 검색
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // 2. 유형 필터
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 3. 상태 필터
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        return $query->paginate(10)->withQueryString();
    }

    /**
     * 쿠폰 상세 정보
     */
    public function getCoupon(int $id): Coupon
    {
        return Coupon::findOrFail($id);
    }

    /**
     * 쿠폰 생성
     */
    public function createCoupon(array $data): Coupon
    {
        return Coupon::create($this->prepareData($data));
    }

    /**
     * 쿠폰 수정
     */
    public function updateCoupon(int $id, array $data): Coupon
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update($this->prepareData($data));
        return $coupon;
    }

    /**
     * 쿠폰 삭제
     */
    public function deleteCoupon(int $id): bool
    {
        $coupon = Coupon::findOrFail($id);
        return $coupon->delete();
    }

    /**
     * 입력 데이터 가공
     */
    private function prepareData(array $data): array
    {
        // 불필요한 필드 제거 및 형식 변환
        return [
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'type' => $data['type'],
            'discount_type' => $data['discount_type'],
            'discount_value' => (int) $data['discount_value'],
            'min_order_amount' => (int) ($data['min_order_amount'] ?? 0),
            'max_discount_amount' => $data['max_discount_amount'] ? (int) $data['max_discount_amount'] : null,
            'description' => $data['description'] ?? null,
            'is_active' => isset($data['is_active']),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ];
    }
}

<?php

namespace App\Services;

use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShippingAddressService
{
    /**
     * 회원의 배송지 목록 조회 (기본 배송지 우선 정렬)
     */
    public function getMemberAddresses()
    {
        return ShippingAddress::where('member_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * 배송지 등록
     */
    public function createAddress(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. 만약 기본 배송지로 설정한다면 기존 기본 배송지 해제
            if (!empty($data['is_default'])) {
                $this->clearDefaultAddress();
            }

            // 2. 만약 첫 번째 배송지라면 자동으로 기본 배송지 설정
            if (ShippingAddress::where('member_id', Auth::id())->count() === 0) {
                $data['is_default'] = true;
            }

            $data['member_id'] = Auth::id();
            return ShippingAddress::create($data);
        });
    }

    /**
     * 배송지 수정
     */
    public function updateAddress($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $address = ShippingAddress::where('member_id', Auth::id())->findOrFail($id);

            // 1. 만약 기본 배송지로 설정한다면 기존 기본 배송지 해제
            if (!empty($data['is_default']) && !$address->is_default) {
                $this->clearDefaultAddress();
            }

            $address->update($data);
            return $address;
        });
    }

    /**
     * 배송지 삭제
     */
    public function deleteAddress($id)
    {
        $address = ShippingAddress::where('member_id', Auth::id())->findOrFail($id);
        
        // 기본 배송지는 삭제 불가하거나 다른 배송지를 기본으로 지정해야 함 (여기서는 간단히 삭제만 처리)
        if ($address->is_default) {
            throw new \Exception('기본 배송지는 삭제할 수 없습니다. 다른 배송지를 기본으로 설정한 후 삭제해주세요.');
        }

        return $address->delete();
    }

    /**
     * 기본 배송지 설정 (단독)
     */
    public function setDefault($id)
    {
        return DB::transaction(function () use ($id) {
            $this->clearDefaultAddress();
            
            $address = ShippingAddress::where('member_id', Auth::id())->findOrFail($id);
            $address->update(['is_default' => true]);
            
            return $address;
        });
    }

    /**
     * 기존 기본 배송지 해제 (내부용)
     */
    protected function clearDefaultAddress()
    {
        ShippingAddress::where('member_id', Auth::id())
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }
}

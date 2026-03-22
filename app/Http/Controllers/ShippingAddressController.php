<?php

namespace App\Http\Controllers;

use App\Services\ShippingAddressService;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    protected $addressService;

    public function __construct(ShippingAddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * 배송지 목록 페이지
     */
    public function index()
    {
        $addresses = $this->addressService->getMemberAddresses();
        return view('pages.mypage-shipping-address', compact('addresses'));
    }

    /**
     * 배송지 등록 처리
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_name' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:20',
            'zip_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'address_detail' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            $this->addressService->createAddress($validated);
            return response()->json(['success' => true, 'message' => '배송지가 등록되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 배송지 수정 처리
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'address_name' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:20',
            'zip_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'address_detail' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        try {
            $this->addressService->updateAddress($id, $validated);
            return response()->json(['success' => true, 'message' => '배송지가 수정되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * 배송지 삭제 처리
     */
    public function destroy($id)
    {
        try {
            $this->addressService->deleteAddress($id);
            return response()->json(['success' => true, 'message' => '배송지가 삭제되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * 기본 배송지 설정 처리
     */
    public function setDefault($id)
    {
        try {
            $this->addressService->setDefault($id);
            return response()->json(['success' => true, 'message' => '기본 배송지로 설정되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

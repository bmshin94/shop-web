<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Services\Admin\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * 쿠폰 목록
     */
    public function index(Request $request)
    {
        $coupons = $this->couponService->getCouponList($request);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * 쿠폰 생성 폼
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * 쿠폰 저장
     */
    public function store(CouponRequest $request)
    {
        $this->couponService->createCoupon($request->validated());

        return redirect()->route('admin.coupons.index')->with('success', '쿠폰이 성공적으로 생성되었습니다.');
    }

    /**
     * 쿠폰 수정 폼
     */
    public function edit($id)
    {
        $coupon = $this->couponService->getCoupon($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * 쿠폰 업데이트
     */
    public function update(CouponRequest $request, $id)
    {
        $this->couponService->updateCoupon($id, $request->validated());

        return redirect()->route('admin.coupons.index')->with('success', '쿠폰 정보가 수정되었습니다.');
    }

    /**
     * 쿠폰 삭제
     */
    public function destroy($id)
    {
        $this->couponService->deleteCoupon($id);
        return redirect()->route('admin.coupons.index')->with('success', '쿠폰이 삭제되었습니다.');
    }
}

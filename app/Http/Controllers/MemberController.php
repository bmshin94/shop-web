<?php

namespace App\Http\Controllers;

use App\Services\MemberService;
use App\Services\CheckoutService;
use App\Http\Requests\Mypage\CouponRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Inquiry;
use App\Models\Order;

class MemberController extends Controller
{
    protected $memberService;
    protected $checkoutService;

    public function __construct(MemberService $memberService, CheckoutService $checkoutService)
    {
        $this->middleware('auth');
        $this->memberService = $memberService;
        $this->checkoutService = $checkoutService;
    }

    /**
     * 마이페이지 메인 대시보드
     */
    public function index(): View
    {
        $member = Auth::user();
        $data = $this->memberService->getDashboardData($member);

        return view('pages.mypage', $data);
    }

    /**
     * 주문 목록 조회 페이지
     */
    public function orderList(Request $request): View
    {
        $member = Auth::user();
        $data = $this->memberService->getOrderListData($member, $request);

        return view('pages.mypage-order-list', $data);
    }

    /**
     * 취소/반품 내역 페이지
     */
    public function cancelList(Request $request): View
    {
        $member = Auth::user();
        $data = $this->memberService->getCancelListData($member, $request);

        return view('pages.mypage-cancel-list', $data);
    }

    /**
     * 환불/입금 내역 페이지
     */
    public function refundList(Request $request): View
    {
        $member = Auth::user();
        $data = $this->memberService->getRefundListData($member, $request);

        return view('pages.mypage-refund-list', $data);
    }

    /**
     * 영수증/계산서 발급 조회 페이지
     */
    public function receiptList(Request $request): View
    {
        $member = Auth::user();
        $data = $this->memberService->getReceiptListData($member, $request);

        return view('pages.mypage-receipt', $data);
    }

    /**
     * 찜 목록 조회 페이지
     */
    public function wishlist(): View
    {
        $member = Auth::user();
        $data = $this->memberService->getWishlistData($member);

        return view('pages.mypage-wishlist', $data);
    }

    /**
     * 적립금 내역 페이지
     */
    public function pointList(): View
    {
        $member = Auth::user();
        $data = $this->memberService->getPointListData($member);

        return view('pages.mypage-point', $data);
    }

    /**
     * 쿠폰함 페이지
     */
    public function couponList(Request $request): View
    {
        $member = Auth::user();
        $data = $this->memberService->getCouponListData($member, $request);

        return view('pages.mypage-coupon', $data);
    }

    /**
     * 쿠폰 등록 처리
     */
    public function registerCoupon(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        try {
            $member = Auth::user();
            $this->memberService->registerCoupon($member, $request->code);
            
            return response()->json([
                'message' => '쿠폰이 성공적으로 등록되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * 최근 본 상품 목록 조회  (비로그인 지원!)
     */
    public function recentViewList(): View
    {
        $member = Auth::user();
        $data = $this->memberService->getRecentViewData($member);

        return view('pages.mypage-recent', $data);
    }

    /**
     * 최근 본 상품 전체 삭제  (비로그인 지원!)
     */
    public function clearRecentViews(): JsonResponse
    {
        try {
            $member = Auth::user();
            $this->memberService->clearRecentViews($member);

            return response()->json([
                'status' => 'success',
                'message' => '최근 본 상품 내역이 모두 삭제되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '삭제 처리 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    /**
     * 최근 본 상품 선택 삭제 
     */
    public function deleteSelectedRecentViews(Request $request): JsonResponse
    {
        try {
            $member = Auth::user();
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'status' => 'error',
                    'message' => '삭제할 상품을 선택해주세요.'
                ], 422);
            }

            $this->memberService->deleteSelectedRecentViews($member, $ids);

            return response()->json([
                'status' => 'success',
                'message' => '선택한 상품이 삭제되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '삭제 처리 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    /**
     * 비밀번호 재확인 폼 
     */
    public function confirmPasswordForm(): View|RedirectResponse
    {
        // 이미 확인했다면 바로 수정 페이지로! 
        if (session()->get('auth.password_confirmed_at')) {
            return redirect()->route('mypage.profile-edit');
        }

        return view('pages.mypage-profile-confirm');
    }

    /**
     * 비밀번호 재확인 처리 
     */
    public function confirmPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'success' => false,
                'message' => '비밀번호가 일치하지 않습니다.'
            ], 422);
        }

        // 확인 시간 세션 저장 ⏰
        session()->put('auth.password_confirmed_at', time());

        return response()->json([
            'success' => true,
            'redirect' => route('mypage.profile-edit')
        ]);
    }

    /**
     * 회원정보 수정 폼 
     */
    public function profileEditForm(): View|RedirectResponse
    {
        // 비밀번호 확인 안 됐으면 쫓아내기! ️‍️
        if (!session()->has('auth.password_confirmed_at')) {
            return redirect()->route('mypage.profile');
        }

        $member = Auth::user();
        return view('pages.mypage-profile-edit', compact('member'));
    }

    /**
     * 회원정보 수정 처리 
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $member = Auth::user();

        $request->validate([
            'phone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'postal_code' => 'nullable|string|max:10',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'marketing_sms' => 'nullable|boolean',
            'marketing_email' => 'nullable|boolean',
        ]);

        try {
            $data = $request->only(['phone', 'postal_code', 'address_line1', 'address_line2']);
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $member->update(array_merge($data, [
                'marketing_sms' => $request->boolean('marketing_sms'),
                'marketing_email' => $request->boolean('marketing_email'),
            ]));

            return response()->json([
                'success' => true,
                'message' => '회원정보가 성공적으로 수정되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '수정 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    /**
     * 1:1 문의 내역 조회 
     */
    public function inquiryList(): View
    {
        $member = Auth::user();
        $data = $this->memberService->getInquiryData($member);

        return view('pages.mypage-inquiry', $data);
    }

    /**
     * 1:1 문의 등록 처리 
     */
    public function storeInquiry(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_private' => 'nullable|boolean', // 비밀글 여부 
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $member = Auth::user();
            $data = $request->only('product_id', 'title', 'content');
            $data['is_private'] = $request->boolean('is_private'); // 불리언으로 변환! 

            // 사진 업로드 처리 
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('inquiries', 'public');
                    $imagePaths[] = \Illuminate\Support\Facades\Storage::url($path);
                }
                $data['images'] = $imagePaths;
            }

            $this->memberService->createInquiry($member, $data);

            return response()->json([
                'status' => 'success',
                'message' => '문의가 등록되었습니다! '
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '문의 등록 중 오류가 발생했습니다.'
            ], 500);
        }
    }

    /**
     * 문의 수정 페이지 
     */
    public function editInquiry(Inquiry $inquiry): View
    {
        // 본인 글인지 확인! ️‍️
        if ($inquiry->member_id !== Auth::id()) {
            abort(403, '본인의 문의만 수정할 수 있어요! ');
        }

        return view('pages.qna-edit', compact('inquiry'));
    }

    /**
     * 문의 수정 처리 
     */
    public function updateInquiry(Request $request, Inquiry $inquiry): JsonResponse
    {
        // 본인 글인지 확인! ️‍️
        if ($inquiry->member_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_private' => 'nullable|boolean', // 비밀글 여부 
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->only('title', 'content');
            $data['is_private'] = $request->boolean('is_private'); // 불리언으로 변환! 

            // 1. 기존 이미지 삭제 처리 
            $currentImages = $inquiry->images ?? [];
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $deletePath) {
                    // 서버 스토리지에서 파일 삭제! ️
                    $storagePath = str_replace('/storage/', '', $deletePath);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($storagePath);
                    
                    // 현재 이미지 배열에서 제외! 
                    $currentImages = array_filter($currentImages, fn($img) => $img !== $deletePath);
                }
            }

            // 2. 새 사진 업로드 
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('inquiries', 'public');
                    $currentImages[] = \Illuminate\Support\Facades\Storage::url($path);
                }
            }
            
            // 최종 이미지 배열 업데이트 (인덱스 재정렬 필수! )
            $data['images'] = array_values($currentImages);
            
            $inquiry->update($data);

            return response()->json([
                'status' => 'success',
                'message' => '문의가 성공적으로 수정되었습니다! '
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => '수정 중 오류가 발생했습니다.'], 500);
        }
    }

    /**
     * 문의 삭제 처리 
     */
    public function destroyInquiry(Inquiry $inquiry): JsonResponse
    {
        // 본인 글인지 확인! ️‍️
        if ($inquiry->member_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => '권한이 없습니다.'], 403);
        }

        try {
            // 서버에 저장된 사진들도 지워주면 좋겠지? 
            if ($inquiry->images) {
                foreach ($inquiry->images as $imageUrl) {
                    $path = str_replace('/storage/', '', $imageUrl);
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
                }
            }

            $inquiry->delete();

            return response()->json([
                'status' => 'success',
                'message' => '문의가 삭제되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => '삭제 중 오류가 발생했습니다.'], 500);
        }
    }

    /**
     * 상품 리뷰 관리 페이지 
     */
    public function reviewList(): View
    {
        $member = Auth::user();
        $data = $this->memberService->getReviewListData($member);
        return view('pages.mypage-review', $data);
    }

    /**
     * 주문 상세 조회
     */
    public function orderDetail($orderNumber): View
    {
        $member = Auth::user();
        $order = $member->orders()
            ->with(['items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('pages.mypage-order-detail', compact('member', 'order'));
    }

    /**
     * 교환/반품 신청 폼
     */
    public function exchangeReturnForm($orderNumber): View|RedirectResponse
    {
        $member = Auth::user();
        $order = $member->orders()
            ->with(['items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // 배송완료 상태일 때만 신청 가능하도록 방어 로직
        if ($order->order_status !== '배송완료') {
            return redirect()->route('mypage.order-detail', $order->order_number)
                ->with('error', '교환/반품 신청은 배송완료 상태에서만 가능합니다.');
        }

        return view('pages.mypage-exchange-return', compact('member', 'order'));
    }

    /**
     * 주문 취소 처리
     */
    public function cancelOrder(Request $request, $orderNumber): JsonResponse
    {
        $member = Auth::user();
        $order = $member->orders()->where('order_number', $orderNumber)->firstOrFail();

        if (!in_array($order->order_status, Order::CANCELLABLE_STATUSES)) {
            return response()->json(['message' => '이미 배송이 시작되어 취소할 수 없습니다.'], 422);
        }

        try {
            $this->checkoutService->cancelOrder($order, $request->input('reason', '사용자 직접 취소'));
            return response()->json(['message' => '주문이 정상적으로 취소되었습니다.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * 교환/반품 신청 저장
     */
    public function storeExchangeReturn(Request $request, $orderNumber): JsonResponse
    {
        $member = Auth::user();
        $order = $member->orders()->where('order_number', $orderNumber)->firstOrFail();

        // 1. 상태 검증
        if ($order->order_status !== '배송완료') {
            return response()->json(['message' => '교환/반품 신청은 배송완료 상태에서만 가능합니다.'], 422);
        }

        // 2. 유효성 검사
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'type' => 'required|in:exchange,return',
            'reason' => 'required|string',
            'content' => 'nullable|string',
        ]);

        try {
            // 3. 서비스 호출하여 신청 처리
            $this->checkoutService->processOrderClaim($member, $order, $validated);

            return response()->json([
                'status' => 'success',
                'message' => '교환/반품 신청이 정상적으로 접수되었습니다.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

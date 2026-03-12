<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PointService;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PointController extends Controller
{
    protected $pointService;

    /**
     * PointController 생성자
     * 
     * @param PointService $pointService
     */
    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * 적립금 내역 목록
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $histories = $this->pointService->getHistoryList($request);
        // 모든 회원을 가져오던 로직을 제거하여 성능 최적화!
        return view('admin.points.index', compact('histories'));
    }

    /**
     * 회원 실시간 검색 (AJAX)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function searchMembers(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = $request->get('q');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $members = Member::active()
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'points']);

        return response()->json($members);
    }

    /**
     * 적립금 수동 지급/차감 처리
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|integer|not_in:0',
            'reason' => 'required|string|max:255',
        ]);

        $this->pointService->manualAdjust(
            $request->member_id,
            $request->amount,
            $request->reason
        );

        return redirect()->back()->with('success', '적립금이 성공적으로 반영되었습니다.');
    }
}

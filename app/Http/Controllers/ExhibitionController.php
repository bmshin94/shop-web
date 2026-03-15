<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    /**
     * 기획전 목록 페이지 (페이징 적용)
     */
    public function index()
    {
        // 1. 진행 중이거나 진행 예정인 기획전 조회 (페이지당 5개)
        $exhibitions = Exhibition::whereIn('status', ['진행중', '진행예정'])
            ->with(['products.images']) // 연결된 모든 상품을 안전하게 가져오도록 최적화
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->paginate(5);

        return view('pages.exhibition', compact('exhibitions'));
    }

    /**
     * 특정 기획전 상세 페이지
     */
    public function show($slug)
    {
        $exhibition = Exhibition::where('slug', $slug)
            ->with(['products.images', 'products.category']) // 이미지와 카테고리를 함께 로드하여 N+1 문제 방지
            ->firstOrFail();

        return view('pages.exhibition-detail', compact('exhibition'));
    }
}

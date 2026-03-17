<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchLogController extends Controller
{
    /**
     * 검색 로그 목록 및 통계 페이지 
     */
    public function index(Request $request)
    {
        // 1. 실시간 인기 검색어 Top 10 
        $popularKeywords = SearchLog::select('keyword', DB::raw('count(*) as count'))
            ->groupBy('keyword')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // 2. 전체 검색 로그 리스트 (최신순) 
        $query = SearchLog::with('member')->latest();

        // 검색어 필터링 
        if ($request->filled('keyword')) {
            $query->where('keyword', 'like', "%{$request->keyword}%");
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.search-logs.index', compact('popularKeywords', 'logs'));
    }

    /**
     * 특정 로그 삭제 (필요시) 
     */
    public function destroy(SearchLog $searchLog)
    {
        $searchLog->delete();
        return back()->with('success', '검색 로그가 삭제되었습니다.');
    }

    /**
     * 검색 기록 전체 초기화 (주의! )
     */
    public function clearAll()
    {
        SearchLog::truncate();
        return back()->with('success', '모든 검색 기록이 초기화되었습니다.');
    }
}

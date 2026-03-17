<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * 알림 발송 이력 목록 조회
     */
    public function index(Request $request)
    {
        $query = NotificationLog::with('member')->latest();

        // 1. 검색어 필터 (수신번호, 메시지)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('recipient', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // 2. 상태 필터 (성공, 실패, 대기)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. 채널 필터 (alimtalk, sms 등)
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.notification.index', [
            'logs' => $logs,
            'status' => $request->status,
            'channel' => $request->channel,
            'search' => $request->search,
        ]);
    }

    /**
     * 알림 발송 상세 정보 (API 응답 확인용)
     */
    public function show(NotificationLog $log)
    {
        return response()->json($log);
    }
}

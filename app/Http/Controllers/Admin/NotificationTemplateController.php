<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    /**
     * 알림 템플릿 목록 조회
     */
    public function index()
    {
        $templates = NotificationTemplate::orderBy('code')->get();
        return view('admin.notification-template.index', compact('templates'));
    }

    /**
     * 알림 템플릿 수정 폼
     */
    public function edit(NotificationTemplate $template)
    {
        return view('admin.notification-template.edit', compact('template'));
    }

    /**
     * 알림 템플릿 업데이트
     */
    public function update(Request $request, NotificationTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'send_type' => 'required|in:alimtalk,sms',
            'template_id' => 'nullable|string|max:100',
            'content' => 'required|string',
            'buttons' => 'nullable|array',
        ]);

        $template->update([
            'name' => $request->name,
            'send_type' => $request->send_type,
            'template_id' => $request->template_id,
            'content' => $request->content,
            'buttons' => $request->buttons,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.notification-templates.index')
            ->with('success', '알림 템플릿이 성공적으로 수정되었습니다.');
    }
}

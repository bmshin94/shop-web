<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Services\Admin\SettingManagementService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function __construct(
        private readonly SettingManagementService $settingManagementService
    ) {
    }

    /**
     * 관리자 기본 설정 화면을 조회한다.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.settings.index', [
            'settings' => $this->settingManagementService->getSettings(),
        ]);
    }

    /**
     * 관리자 기본 설정을 저장한다.
     *
     * @param  UpdateSiteSettingRequest  $request
     * @return RedirectResponse
     */
    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        $this->settingManagementService->updateSettings($request->validated());

        return redirect()
            ->route('admin.settings.index')
            ->with('success', '기본 설정이 저장되었습니다.');
    }
}

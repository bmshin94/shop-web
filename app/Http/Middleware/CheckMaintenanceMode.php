<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Admin\SettingManagementService;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    public function __construct(
        private readonly SettingManagementService $settingService
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = $this->settingService->getSettings();
        $isMaintenance = $settings['maintenance_mode'] ?? false;

        if ($isMaintenance) {
            // 관리자 세션이 있거나 관리자 관련 경로는 허용
            if (Auth::guard('admin')->check() || $request->is('admin*')) {
                return $next($request);
            }

            // 점검 페이지 노출 (503 Service Unavailable)
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}

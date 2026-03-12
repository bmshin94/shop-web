<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // 관리자 가드인 경우 관리자 대시보드로 이동
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                // 그 외(일반 회원 등)는 설정된 HOME 경로로 이동
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}

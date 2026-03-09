<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * 관리자 로그인 페이지를 보여줍니다.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * 관리자 로그인을 처리합니다.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 마지막 로그인 시간 업데이트 및 세션 키 설정 
            $operator = Auth::guard('admin')->user();
            if ($operator) {
                $operator->last_login_at = now();
                $operator->save();

                // 기존 권한 미들웨어가 사용하는 세션 키 동기화
                $request->session()->put('admin_operator_id', $operator->id);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => '이메일 또는 비밀번호가 올바르지 않습니다.',
        ])->onlyInput('email');
    }

    /**
     * 관리자 로그아웃을 처리합니다.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

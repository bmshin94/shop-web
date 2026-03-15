<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Member;
use App\Models\PhoneVerification;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * 로그인 페이지 표시
     */
    public function showLoginForm()
    {
        // 이전 페이지가 로그인 페이지가 아니고, 우리 사이트 내부 페이지라면 intended로 설정
        $previousUrl = url()->previous();
        if (!Auth::check() && !session()->has('url.intended')) {
            if ($previousUrl !== route('login') && Str::contains($previousUrl, url('/'))) {
                session(['url.intended' => $previousUrl]);
            }
        }
        return view('pages.login');
    }

    /**
     * 로그인 처리
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember-me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // 마지막 로그인 시간 업데이트
            $member = Auth::user();
            $member->update(['last_login_at' => now()]);

            // 최근 본 상품 쿠키 -> DB 동기화 
            $this->syncRecentViews($member);

            return response()->json([
                'success' => true,
                'message' => '반가워요! 로그인이 완료되었습니다.',
                'redirect' => redirect()->intended(route('home'))->getTargetUrl(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '이메일 주소 또는 비밀번호가 일치하지 않습니다.',
            'errors' => ['email' => ['인증 정보가 올바르지 않습니다.']]
        ], 422);
    }

    /**
     * 로그아웃 처리
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * 소셜 로그인 리다이렉트
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * 소셜 로그인 콜백 처리
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', '소셜 로그인에 실패했습니다.');
        }

        // 해당 소셜 계정으로 가입된 회원이 있는지 확인
        $member = Member::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (!$member) {
            // 이메일로 기존 회원 확인
            $member = Member::where('email', $socialUser->getEmail())->first();

            if ($member) {
                // 기존 회원이 있다면 소셜 정보 업데이트
                $member->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                ]);
            } else {
                // 신규 회원이라면 생성
                $member = Member::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'status' => '활성',
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($member);
        $member->update(['last_login_at' => now()]);

        // 최근 본 상품 쿠키 -> DB 동기화 
        $this->syncRecentViews($member);

        return redirect()->intended(route('home'));
    }

    /**
     * 최근 본 상품 쿠키 데이터를 DB로 동기화 
     */
    private function syncRecentViews($member)
    {
        $recentCookie = request()->cookie('recent_views', '[]');
        $viewedIds = json_decode($recentCookie, true) ?: [];

        if (!empty($viewedIds)) {
            foreach ($viewedIds as $productId) {
                \App\Models\RecentView::updateOrCreate(
                    ['member_id' => $member->id, 'product_id' => $productId],
                    ['viewed_at' => now()]
                );
            }
            // 동기화 후 쿠키 비우기 
            \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget('recent_views'));
        }
    }

    /**
     * 비밀번호 찾기 페이지 표시
     */
    public function showFindPasswordForm()
    {
        return view('pages.find-password');
    }

    /**
     * 아이디(이메일) 찾기 페이지 표시
     */
    public function showFindEmailForm()
    {
        return view('pages.find-email');
    }

    /**
     * 아이디(이메일) 찾기 처리
     */
    public function findEmail(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
        ]);

        $phone = str_replace('-', '', $request->phone);

        // 휴대폰 인증 여부 확인
        $isVerified = PhoneVerification::where('phone', $phone)
            ->where('is_verified', true)
            ->exists();

        if (!$isVerified) {
            return response()->json([
                'success' => false,
                'message' => '휴대폰 인증이 완료되지 않았습니다.',
            ], 422);
        }

        $member = Member::where('phone', $request->phone)
            ->orWhere('phone', $phone)
            ->first();

        if ($member) {
            // 인증 정보 사용 완료 처리 (삭제)
            PhoneVerification::where('phone', $phone)->delete();

            // 이메일 마스킹 처리 (예: ab****@example.com)
            $emailParts = explode('@', $member->email);
            $name = $emailParts[0];
            $domain = $emailParts[1];
            $length = strlen($name);
            $visibleCount = $length > 4 ? 3 : 2;
            $maskedEmail = substr($name, 0, $visibleCount) . str_repeat('*', $length - $visibleCount) . '@' . $domain;

            return response()->json([
                'success' => true,
                'email' => $maskedEmail,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '입력하신 번호로 가입된 정보가 없습니다.',
        ], 404);
    }

    /**
     * 비밀번호 재설정 처리
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&^#_-])[A-Za-z\d@$!%*?&^#_-]{8,}$/'],
            'password_confirm' => ['required', 'same:password'],
        ], [
            'password.regex' => '비밀번호는 영문, 숫자, 특수문자를 모두 포함해야 합니다.',
        ]);

        // 이메일 인증 여부 최종 확인
        $isVerified = EmailVerification::where('email', $request->email)
            ->where('is_verified', true)
            ->exists();

        if (!$isVerified) {
            return response()->json([
                'success' => false,
                'message' => '이메일 인증이 완료되지 않았습니다.',
            ], 422);
        }

        $member = Member::where('email', $request->email)->first();
        if ($member) {
            $member->update([
                'password' => Hash::make($request->password)
            ]);

            // 인증 정보 삭제
            EmailVerification::where('email', $request->email)->delete();

            return response()->json([
                'success' => true,
                'message' => '비밀번호가 성공적으로 변경되었습니다.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '회원 정보를 찾을 수 없습니다.',
        ], 404);
    }

    /**
     * 회원가입 페이지 표시
     */
    public function showRegisterForm()
    {
        return view('pages.register');
    }

    /**
     * 회원가입 처리
     */
    public function register(RegisterRequest $request)
    {
        // 휴대폰 인증 최종 확인
        $phone = str_replace('-', '', $request->phone);
        $isVerified = PhoneVerification::where('phone', $phone)
            ->where('is_verified', true)
            ->exists();

        if (!$isVerified) {
            return response()->json([
                'success' => false,
                'message' => '휴대폰 인증이 완료되지 않았습니다.',
                'errors' => ['phone' => ['휴대폰 인증을 완료해주세요.']]
            ], 422);
        }

        $member = Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => '활성',
        ]);

        // 가입 즉시 로그인 처리
        Auth::login($member);

        // 최근 본 상품 쿠키 -> DB 동기화 
        $this->syncRecentViews($member);

        return response()->json([
            'success' => true,
            'message' => '가입이 완료되었습니다! 프리미엄 혜택을 환영합니다.',
            'redirect' => session()->pull('url.intended', route('home')),
        ]);
    }

    /**
     * 이메일 중복 확인
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => '이메일을 입력해주세요.',
            'email.email' => '올바른 이메일 형식이 아닙니다.',
        ]);

        $exists = Member::where('email', $request->email)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => '이미 가입된 이메일입니다.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => '사용 가능한 이메일입니다.',
        ]);
    }
}

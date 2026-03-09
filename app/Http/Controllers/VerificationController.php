<?php

namespace App\Http\Controllers;

use App\Models\PhoneVerification;
use App\Models\EmailVerification;
use App\Models\Member;
use App\Services\SmsService;
use App\Mail\PasswordResetCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class VerificationController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * 인증번호 발송
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^010-?\d{3,4}-?\d{4}$/'],
        ]);

        $phone = str_replace('-', '', $request->phone);
        $code = sprintf('%06d', mt_rand(0, 999999));
        $expiresAt = Carbon::now()->addMinutes(3);

        // 이전 인증 정보 삭제
        PhoneVerification::where('phone', $phone)->delete();

        PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        $message = "[Active Women] 인증번호 [{$code}]를 입력해주세요.";
        $result = $this->smsService->sendSms($phone, $message);

        if ($result['result_code'] > 0) {
            return response()->json([
                'success' => true,
                'message' => '인증번호가 발송되었습니다.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '인증번호 발송에 실패했습니다. 관리자에게 문의하세요.',
        ], 500);
    }

    /**
     * 인증번호 확인
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $phone = str_replace('-', '', $request->phone);
        
        $verification = PhoneVerification::where('phone', $phone)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($verification) {
            $verification->update(['is_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => '인증이 완료되었습니다.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '인증번호가 일치하지 않거나 만료되었습니다.',
        ]);
    }

    /**
     * 이메일 인증번호 발송 (비밀번호 찾기용)
     */
    public function sendEmailCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // 가입된 회원인지 확인
        $exists = Member::where('email', $request->email)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => '가입되지 않은 이메일 주소입니다.',
            ], 422);
        }

        $email = $request->email;
        $code = sprintf('%06d', mt_rand(0, 999999));
        $expiresAt = Carbon::now()->addMinutes(3);

        EmailVerification::where('email', $email)->delete();

        EmailVerification::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        try {
            Mail::to($email)->send(new PasswordResetCode($code));
            return response()->json([
                'success' => true,
                'message' => '인증번호가 이메일로 발송되었습니다.',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Email Send Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '메일 발송에 실패했습니다. 잠시 후 다시 시도해주세요.',
            ], 500);
        }
    }

    /**
     * 이메일 인증번호 확인
     */
    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $verification = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($verification) {
            $verification->update(['is_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => '인증이 완료되었습니다.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '인증번호가 일치하지 않거나 만료되었습니다.',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\PhoneVerificationRequest;
use App\Http\Requests\Auth\PhoneVerifyRequest;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Http\Requests\Auth\EmailVerifyRequest;
use App\Models\PhoneVerification;
use App\Models\EmailVerification;
use App\Models\Member;
use App\Services\SmsService;
use App\Mail\PasswordResetCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificationController extends Controller
{
    protected $smsService;

    /**
     * VerificationController 생성자
     * 
     * @param SmsService $smsService
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * 휴대폰 인증번호 발송
     * 
     * @param PhoneVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCode(PhoneVerificationRequest $request)
    {
        // 1. 휴대폰 번호 전처리 (하이픈 제거)
        $phone = str_replace('-', '', $request->phone);
        $code = sprintf('%06d', mt_rand(0, 999999));
        $expiresAt = Carbon::now()->addMinutes(3);

        // 2. 이전 인증 정보 삭제 및 신규 생성
        PhoneVerification::where('phone', $phone)->delete();

        PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        // 3. DB 템플릿 기반 메시지 생성 (없으면 기본 메시지 사용)
        $template = \App\Models\NotificationTemplate::where('code', 'VERIFICATION_CODE')->where('is_active', true)->first();
        $message = $template 
            ? $template->parseContent(['code' => $code])
            : "[Active Women] 인증번호 [{$code}]를 입력해주세요.";

        // 4. SMS 발송 처리
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
     * 휴대폰 인증번호 확인
     * 
     * @param PhoneVerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(PhoneVerifyRequest $request)
    {
        // 1. 휴대폰 번호 하이픈 제거 및 일치하는 인증 정보 조회
        $phone = str_replace('-', '', $request->phone);
        
        $verification = PhoneVerification::where('phone', $phone)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        // 2. 인증 완료 상태 업데이트
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
     * 
     * @param EmailVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmailCode(EmailVerificationRequest $request)
    {
        // 1. 가입된 회원인지 확인
        $exists = Member::where('email', $request->email)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => '가입되지 않은 이메일 주소입니다.',
            ], 422);
        }

        // 2. 인증 코드 생성 및 저장 (3분 만료)
        $email = $request->email;
        $code = sprintf('%06d', mt_rand(0, 999999));
        $expiresAt = Carbon::now()->addMinutes(3);

        EmailVerification::where('email', $email)->delete();

        EmailVerification::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
        ]);

        // 3. 인증 메일 발송
        try {
            Mail::to($email)->send(new PasswordResetCode($code));
            return response()->json([
                'success' => true,
                'message' => '인증번호가 이메일로 발송되었습니다.',
            ]);
        } catch (\Exception $e) {
            Log::error("Email Send Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => '메일 발송에 실패했습니다. 잠시 후 다시 시도해주세요.',
            ], 500);
        }
    }

    /**
     * 이메일 인증번호 확인
     * 
     * @param EmailVerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmailCode(EmailVerifyRequest $request)
    {
        // 1. 인증 정보 조회 및 유효성 검사
        $verification = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        // 2. 인증 완료 처리
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

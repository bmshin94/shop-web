<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'kakao' => [
        'client_id' => env('KAKAO_CLIENT_ID'),
        'client_secret' => env('KAKAO_CLIENT_SECRET'),
        'redirect' => env('KAKAO_REDIRECT_URI'),
    ],

    'naver' => [
        'client_id' => env('NAVER_CLIENT_ID'),
        'client_secret' => env('NAVER_CLIENT_SECRET'),
        'redirect' => env('NAVER_REDIRECT_URI'),
    ],

    'alimtalk' => [
        'default' => env('ALIMTALK_DRIVER', 'solapi'), // solapi 또는 aligo 선택 가능! ✨
        'test_mode' => env('ALIMTALK_TEST_MODE', true), // true면 실제 발송 안 함! 🚀
    ],

    'solapi' => [
        'api_key' => env('SOLAPI_API_KEY'),
        'api_secret' => env('SOLAPI_API_SECRET'),
        'sender_number' => env('SOLAPI_SENDER_NUMBER'), // 등록된 발신번호
        'pfid' => env('SOLAPI_PFID'), // 카카오 비즈니스 채널 PFID
    ],

    'aligo' => [
        'api_key' => env('ALIGO_API_KEY'),
        'user_id' => env('ALIGO_USER_ID'),
        'sender_key' => env('ALIGO_SENDER_KEY'), // 알림톡 발신 프로필 키
        'sender_number' => env('ALIGO_SENDER_NUMBER'), // 발신 번호 (SMS용)
    ],

];

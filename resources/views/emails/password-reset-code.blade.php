<x-mail::message>
# 비밀번호 재설정 인증번호 안내

안녕하세요, Active Women입니다.
비밀번호를 재설정하기 위한 인증번호를 안내해 드립니다.

아래의 인증번호 6자리를 입력 화면에 입력해 주세요.

<x-mail::panel>
## {{ $code }}
</x-mail::panel>

* 본 인증번호는 발송 후 **3분 동안**만 유효합니다.
* 만약 인증번호를 요청하지 않으셨다면 이 메일을 무시해 주세요.

감사합니다,<br>
{{ config('app.name') }}
</x-mail::message>

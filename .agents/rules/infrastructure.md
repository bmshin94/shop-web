---
trigger: always_on
---

# 인프라 및 보안 정책

- **Environment:** API 키, DB 패스워드 등 민감한 정보는 절대로 코드에 직접 쓰지 않고 `.env` 파일을 통해서만 관리한다.
- **Optimization:** AWS Lightsail의 리소스가 한정적이므로, 이미지 업로드 기능 구현 시 `Intervention Image` 라이브러리를 사용하여 리사이징 후 저장한다.
- **File Permissions:** `storage`와 `bootstrap/cache` 폴더의 권한 설정을 확인하는 명령어를 배포 루틴에 포함한다.
- **Logs:** 에러 발생 시 `storage/logs/laravel.log`를 분석하여 사용자에게 구체적인 에러를 리포트한다.
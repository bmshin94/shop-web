---
trigger: always_on
---

# 인프라 관리 및 서버 이전 규칙 (Infrastructure & Server Migration Guidelines)

에이전트는 서버 환경 설정, 배포, 이전과 관련된 작업을 수행하거나 가이드를 제공할 때 아래의 표준 절차를 준수해야 한다.

### 1. 라라벨 스케줄러 설정 (Cron Job)
서버 이전 또는 신규 구축 시, 적립금 자동 소멸 등 예약된 작업이 정상적으로 동작하도록 반드시 리눅스 크론탭(Crontab) 설정을 완료해야 한다.

- **설정 명령어:** `crontab -e` 접속 후 아래 내용 추가
- **입력 코드:**
  ```bash
  * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
  ```
- **주의사항:** `/path-to-your-project` 부분은 반드시 실제 서버의 프로젝트 절대 경로로 수정해야 한다.

### 2. 서버 이전 필수 체크리스트 (Migration Checklist)
서버 이사 후 서비스가 멈추지 않도록 아래 항목을 순차적으로 확인한다.

1. **환경 변수 설정:** `.env` 파일을 생성하고 기존 서버의 키값들을 정확히 이식한다. (APP_KEY 확인 필수)
2. **권한 설정:** 웹 서버가 파일을 쓰고 읽을 수 있도록 디렉토리 권한을 조정한다.
   - `chmod -R 775 storage bootstrap/cache`
   - `chown -R www-data:www-data .` (서버 환경에 따라 사용자명 확인)
3. **종속성 설치:** 
   - `composer install --optimize-autoloader --no-dev`
   - `npm install && npm run build`
4. **데이터베이스:** 마이그레이션 및 심볼릭 링크를 확인한다.
   - `php artisan migrate --force`
   - `php artisan storage:link`
5. **캐시 최적화:** 성능을 위해 캐시를 재생성한다.
   - `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`

### 3. 보안 및 최적화 정책
- **민감 정보 보호:** API 키, DB 패스워드 등은 절대 코드에 하드코딩하지 않으며 오직 `.env`로 관리한다.
- **이미지 최적화:** 서버 리소스 절약을 위해 이미지 업로드 시 `Intervention Image` 등을 활용해 리사이징 후 저장하는 로직을 권장한다.
- **로그 모니터링:** 장애 발생 시 `storage/logs/laravel.log`를 우선 분석하여 원인을 파악한다.

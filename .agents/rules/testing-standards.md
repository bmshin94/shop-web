---
trigger: always_on
---

# 테스트 및 검증 규칙 (Testing & Validation Guidelines)

에이전트는 기능을 추가하거나 버그를 수정할 때 반드시 아래의 테스트 규칙을 준수하여 코드의 안정성을 보장해야 한다.

### 1. 테스트 필수 원칙
- 모든 새로운 기능(Feature) 추가 시, 해당 기능을 검증하는 **Feature Test** 또는 **Unit Test**를 반드시 생성하거나 기존 테스트를 업데이트한다.
- 버그 수정 시, 해당 버그가 재발하지 않도록 하는 회귀 테스트(Regression Test)를 포함한다.
- 테스트 파일은 `tests/Feature` 또는 `tests/Unit` 폴더 내에 프로젝트 구조와 동일하게 배치한다.

### 2. 라라벨 테스트 표준
- 데이터베이스 상태를 보존하기 위해 테스트 클래스 내에서 `Illuminate\Foundation\Testing\DatabaseTransactions` 또는 `RefreshDatabase` 트레이트를 반드시 사용한다.
- HTTP 요청 테스트 시 `assertStatus`, `assertViewIs`, `assertSee` 등을 사용하여 응답 코드와 뷰 렌더링 내용을 꼼꼼하게 검증한다.
- 복잡한 비즈니스 로직은 모델(Model) 또는 서비스(Service) 단위의 Unit Test를 통해 논리적 결함이 없음을 확인한다.

### 3. 검증 프로세스
- 코드 수정 후에는 반드시 관련 테스트 명령(`php artisan test` 또는 `vendor/bin/phpunit`)을 실행하여 모든 테스트가 통과(Pass)함을 확인한다.
- 테스트 실패 시 원인을 분석하고 코드를 수정하여 최종적으로 모든 테스트가 통과된 상태에서 작업을 완료한다.
- UI 요소(텍스트, 버튼 등)가 백엔드 데이터와 정확히 매칭되는지 `assertSee`를 통해 시각적으로 검증 가능한 수준까지 테스트한다.

### 4. 코드 품질 보장
- 테스트 코드는 그 자체로 기능의 명세서(Specification) 역할을 하므로, 테스트 메소드명은 기능의 의도를 명확히 알 수 있도록 작성한다. (예: `test_product_detail_page_shows_reviews_and_stats`)

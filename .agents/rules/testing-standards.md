---
trigger: always_on
---

# 테스트 및 검증 규칙 (Testing & Validation Guidelines)

에이전트는 기능을 추가하거나 버그를 수정할 때 반드시 아래의 테스트 규칙을 준수하여 코드의 안정성을 보장해야 한다.

### 1. 테스트 필수 원칙
- 모든 새로운 기능(Feature) 추가 시, 해당 기능을 검증하는 **Feature Test** 또는 **Unit Test**를 반드시 생성하거나 기존 테스트를 업데이트한다.
- 버그 수정 시, 해당 버그가 재발하지 않도록 하는 회귀 테스트(Regression Test)를 포함한다.
- 테스트 파일은 `tests/Feature` 또는 `tests/Unit` 폴더 내에 프로젝트 구조와 동일하게 배치한다.

### 2. 라라벨 테스트 표준 및 데이터 보호 (중요)
- **테스트 격리**: 테스트 실행 시 실제 개발/운영 데이터베이스 데이터가 삭제되는 것을 방지하기 위해 반드시 **인메모리 데이터베이스(SQLite :memory:)** 또는 독립된 테스트용 DB를 사용한다.
- **환경 설정**: `phpunit.xml` 파일 내에 `<server name="DB_CONNECTION" value="sqlite"/>` 및 `<server name="DB_DATABASE" value=":memory:"/>` 설정이 되어 있는지 항상 확인한다.
- **파괴적 명령 금지**: 테스트를 위해 메인 데이터베이스에 `migrate:fresh` 또는 `db:seed`를 직접 실행하지 않는다. 필요 시 테스트 코드 내에서 `RefreshDatabase` 트레이트를 사용하여 메모리 상에서만 스키마를 초기화한다.
- **트레이트 사용**: 데이터베이스 상태를 보존하기 위해 테스트 클래스 내에서 `RefreshDatabase` 또는 `DatabaseTransactions` 트레이트를 반드시 사용한다.

### 3. 검증 프로세스
- 코드 수정 후에는 반드시 관련 테스트 명령(`php artisan test` 또는 `vendor/bin/phpunit`)을 실행하여 모든 테스트가 통과(Pass)함을 확인한다.
- 테스트 실패 시 원인을 분석하고 코드를 수정하여 최종적으로 모든 테스트가 통과된 상태에서 작업을 완료한다.
- UI 요소(텍스트, 버튼 등)가 백엔드 데이터와 정확히 매칭되는지 `assertSee`를 통해 시각적으로 검증 가능한 수준까지 테스트한다.

### 4. 코드 품질 보장
- 테스트 코드는 그 자체로 기능의 명세서(Specification) 역할을 하므로, 테스트 메소드명은 기능의 의도를 명확히 알 수 있도록 작성한다. (예: `test_product_detail_page_shows_reviews_and_stats`)

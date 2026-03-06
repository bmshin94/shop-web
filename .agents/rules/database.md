---
trigger: always_on
---

# DB 및 데이터 관리 규칙

- **Migrations:** DB 구조 변경 시 직접 SQL을 실행하지 말고, 반드시 `php artisan make:migration`을 통해 마이그레이션 파일을 생성한다.
- **Indexing:** `HER FIELD` 상품 검색 성능을 위해 `product_name`, `category_id` 등 자주 조회되는 컬럼에는 인덱스(Index) 설정을 제안한다.
- **Seeding:** 테스트용 데이터는 `DatabaseSeeder`와 `Factory`를 활용해 생성하며, 실제 운영 데이터와 섞이지 않게 주의한다.
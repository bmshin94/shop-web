---
trigger: always_on
---

# Laravel 개발 표준 규칙

- **MVC 패턴:** 비즈니스 로직은 반드시 `Service` 클래스에 작성하고, `Controller`는 요청 처리와 응답 반환만 담당한다.
- **Eloquent ORM:** Raw Query 대신 가급적 Eloquent를 사용한다. N+1 문제를 방지하기 위해 `with()`를 사용한 Eager Loading을 기본으로 한다.
- **Validation:** 모든 유저 입력값은 `FormRequest` 클래스를 생성하여 검증한다. Controller 내부에 직접 유효성 검사 로직을 넣지 않는다.
- **Naming:** 테이블명은 복수형(snake_case), 모델명은 단수형(PascalCase)을 준수한다.
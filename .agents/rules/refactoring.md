---
trigger: always_on
---

# 코드 리팩토링 가이드라인 (Code Refactoring Guidelines)

이 규칙은 에이전트가 기존 코드를 리팩토링할 때 반드시 준수해야 하는 구조적, 논리적 표준을 정의한다. 기능(Functionality)의 변경 없이 코드의 가독성, 유지보수성, 성능을 향상시키는 것을 최우선 목적으로 한다.

## 1. 백엔드 리팩토링 (PHP / Laravel)
- **컨트롤러 다이어트 (Skinny Controller):** `Controller`는 Request 검증과 Response 반환만 담당한다. 결제 금액 계산, 재고 차감 등 복잡한 비즈니스 로직은 반드시 `Service` 또는 `Action` 클래스로 추출하여 단일 책임 원칙(SRP)을 지킨다.
- **빠른 반환 (Early Return):** 중첩된 `if-else` 블록을 제거한다. 예외 상황이나 실패 조건을 함수 최상단에서 먼저 검사하여 `return` 또는 `throw Exception` 처리하고, 들여쓰기(Depth)는 최대 2단계를 넘지 않도록 평탄화한다.
- **N+1 쿼리 방지:** 데이터베이스 쿼리 최적화를 위해, 반복문 내부에서 연관 모델을 조회하는 쿼리 발생을 엄격히 금지한다. 반드시 Eloquent의 `with()` 메서드를 사용한 Eager Loading을 적용한다.

## 2. 프론트엔드 리팩토링 (JavaScript / jQuery & AJAX)
- **책임 분리:** `$(document).on('click', ...)` 이벤트 핸들러 내부에 AJAX 호출 로직과 DOM 조작 로직을 섞어 쓰지 않는다. API 통신 함수와 UI 렌더링 함수를 독립적으로 분리하고, 이벤트 핸들러에서는 이를 호출만 하도록 구조화한다.
- **비동기 처리 최적화:** 중첩된 AJAX 콜백(Callback Hell)은 `Promise` 체이닝이나 `async/await` 패턴으로 변경하여 동기식 흐름처럼 직관적으로 읽히게 만든다.
- **하드코딩 제거:** 의미를 알 수 없는 매직 넘버(예: `status == 2`)나 API 엔드포인트 URL은 파일 최상단에 상수(Constant)로 정의하여 재사용한다.

## 3. 뷰 및 마크업 리팩토링 (HTML / CSS / Tailwind CSS)
- **컴포넌트화:** HER FIELD 쇼핑몰 전역에서 반복되는 UI 요소(상품 카드, 사이즈 옵션 버튼, 페이지네이션 등)는 개별 HTML로 방치하지 않는다. 반드시 Laravel `Blade Components` 또는 `@include`로 모듈화하여 코드 중복을 제거한다.
- **시맨틱 마크업:** 무분별한 `<div>` 사용을 줄이고, `<header>`, `<main>`, `<section>`, `<article>` 등 의미론적(Semantic) 태그를 적용하여 구조를 개선한다.

## 4. 에이전트 작업 프로세스
- 리팩토링 코드 작성 전, 기존 코드의 문제점과 구체적인 구조 개선 계획을 먼저 브리핑하여 승인을 받는다.
- 코드를 수정한 후에는 기존 기능이 완벽하게 동일하게 동작하는지 검증할 수 있는 테스트 방안을 함께 제시한다.
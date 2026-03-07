---
trigger: always_on
---

# 프론트엔드 및 통신 규칙

- **UI Framework:** 레이아웃 및 스타일링은 **Tailwind CSS**를 최우선으로 활용하며, 커스텀 CSS는 `resources/css/app.css`에 최소화하여 작성한다.
- **AJAX & CSRF:** 모든 jQuery AJAX 요청에는 반드시 Laravel의 `X-CSRF-TOKEN` 헤더를 포함한다.
- **Blade Template:** 반복되는 UI 요소(상품 카드, 버튼 등)는 `@include`나 `Blade Components`로 분리하여 재사용성을 높인다.
- **DOM Event:** jQuery 사용 시 `$(document).on('click', ...)` 방식보다는 명확한 셀렉터를 사용하고, 가급적 이벤트 위임을 활용한다.
/**
 * 찜하기(Wishlist) 관련 기능 모음
 * - 비접근성, 가독성을 위해 async/await 패턴으로 리팩토링
 * - API 로직과 UI 업데이트 로직을 분리
 */

const WishlistAPI = {
    /**
     * 특정 상품의 찜 상태를 토글합니다.
     * @param {number|string} productId 
     * @returns {Promise<Object>} API 응답 데이터
     */
    async toggle(productId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token is missing!');
            throw new Error('보안 토큰(CSRF)을 찾을 수 없습니다.');
        }

        const data = new URLSearchParams({ _token: csrfToken });

        const response = await fetch(`/wishlist/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                // fetch를 사용할 땐 x-www-form-urlencoded 나 json 을 명시해주는 것이 좋습니다.
                'Content-Type': 'application/x-www-form-urlencoded',
                // Laravel에서 AJAX 요청으로 인식하도록 설정
                'X-Requested-With': 'XMLHttpRequest' 
            },
            body: data
        });

        if (!response.ok) {
            // 401 Unauthorized
            if (response.status === 401) {
                throw new Error('로그인이 필요한 서비스입니다.');
            }
            throw new Error('요청 처리 중 오류가 발생했습니다.');
        }

        return await response.json();
    }
};

const WishlistUI = {
    /**
     * 찜 목록이 0개가 되었을 때의 처리 (현재 페이지 새로고침)
     */
    handleEmptyWishlistPage() {
        if (document.querySelectorAll('.wishlist-item').length === 0) {
            window.location.reload();
        }
    },

    /**
     * 헤더의 찜 개수 배지를 실시간으로 업데이트합니다.
     * @param {number|undefined} count 
     */
    updateBadge(count) {
        if (count === undefined) return;

        const $wishlistBadge = document.querySelector('.header-wishlist-count');
        if (!$wishlistBadge) return;

        $wishlistBadge.textContent = count;
        
        if (count > 0) {
            $wishlistBadge.classList.remove('hidden');
            $wishlistBadge.classList.add('flex', 'animate-bounce-subtle');
            setTimeout(() => $wishlistBadge.classList.remove('animate-bounce-subtle'), 1000);
        } else {
            $wishlistBadge.classList.remove('flex');
            $wishlistBadge.classList.add('hidden');
        }
    },

    /**
     * 찜 추가 시 아이콘 및 알림 UI 업데이트
     * @param {HTMLElement} btn 
     * @param {HTMLElement} icon 
     */
    showAddedState(btn, icon) {
        icon.classList.add('filled', 'text-red-500');
        icon.style.fontVariationSettings = "'FILL' 1";
        
        if (typeof showToast === 'function') {
            showToast('찜 목록에 추가되었습니다.', 'favorite', 'bg-[#181211]');
        }
    },

    /**
     * 찜 취소(제거) 시 아이콘 및 알림 UI 업데이트
     * @param {HTMLElement} btn 
     * @param {HTMLElement} icon 
     */
    showRemovedState(btn, icon) {
        icon.classList.remove('filled', 'text-red-500');
        icon.style.fontVariationSettings = "'FILL' 0";
        
        if (typeof showToast === 'function') {
            showToast('찜 목록에서 제거되었습니다.', 'heart_broken', 'bg-[#ec3713]');
        }

        // 해당 뷰가 마이페이지 등 찜 목록 뷰인지 확인 후 아이템 제거
        const item = btn.closest('.wishlist-item');
        if (item) {
            item.style.transition = 'opacity 0.3s ease';
            item.style.opacity = '0';
            setTimeout(() => {
                item.remove();
                this.handleEmptyWishlistPage();
            }, 300);
        }
    }
};

// ----------------------------------------------------
// Event Listeners (이벤트 위임)
// ----------------------------------------------------

// jQuery 코드들을 최신 표준 바닐라 자바스크립트 기반 이벤트 위임으로 리팩토링합니다.
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', async (e) => {
        // .btn-toggle-wishlist 버튼 또는 그 자식 요소 클릭 감지
        const targetBtn = e.target.closest('.btn-toggle-wishlist');
        
        if (targetBtn) {
            e.preventDefault();
            e.stopPropagation(); // 이벤트 전파 중단 (부모 클릭 방지)

            if (targetBtn.classList.contains('processing')) return;
            
            const productId = targetBtn.dataset.id;
            if (!productId) return;

            const icon = targetBtn.querySelector('.material-symbols-outlined');
            
            try {
                // 1. 상태 업데이트 (중복 클릭 방지)
                targetBtn.classList.add('processing');

                // 2. 비동기 통신 (API)
                const response = await WishlistAPI.toggle(productId);

                // 3. UI 업데이트
                if (response.status === 'added') {
                    WishlistUI.showAddedState(targetBtn, icon);
                } else {
                    WishlistUI.showRemovedState(targetBtn, icon);
                }

                WishlistUI.updateBadge(response.wishlistCount);

            } catch (error) {
                console.error('Wishlist toggle error:', error);
                if (typeof showToast === 'function') {
                    showToast(error.message, 'error', 'bg-red-500');
                } else {
                    alert(error.message);
                }
            } finally {
                // 완료 후 상태 복귀
                targetBtn.classList.remove('processing');
            }
        }
    });
});

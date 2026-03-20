/**
 * 상품 상세 디테일 페이지 전용 JS
 * - `product-detail.blade.php` 에 있던 인라인 스크립트를 분리함.
 * - `window.ProductConfig` 객체를 통해 필요한 초기 데이터를 전달받음.
 */

document.addEventListener("DOMContentLoaded", () => {
    // 상품 기본 정보 (블레이드에서 전달받음)
    const config = window.ProductConfig || {};
    
    const BASE_PRICE = config.basePrice || 0;
    const PRODUCT_ID = config.productId;
    const IS_GUEST = config.isGuest;
    const HAS_COLORS = config.hasColors;
    const HAS_SIZES = config.hasSizes;

    let quantity = 1;
    let selectedSize = "";
    let selectedColor = config.initialColor || "";

    /**
     * @param {string} message 
     * @param {string} icon 
     * @param {string} color 
     */
    function showToast(message, icon = "check_circle", color = "bg-text-main") {
        const container = document.getElementById("toastContainer");
        if (!container) return;
        
        const toast = document.createElement("div");
        toast.className = `flex items-center gap-3 ${color} text-white px-6 py-3.5 rounded-xl shadow-2xl text-sm font-bold pointer-events-auto toast-enter`;
        toast.innerHTML = `<span class="material-symbols-outlined text-lg">${icon}</span><span>${message}</span>`;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove("toast-enter");
            toast.classList.add("toast-exit");
            toast.addEventListener("animationend", () => toast.remove());
        }, 2500);
    }

    // Modal Functions (전역 오버라이드)
    window.openModal = function(modal) {
        if (!modal) return;
        modal.style.display = "flex";
        modal.classList.remove("hidden");
        document.body.style.overflow = "hidden";
    };
    
    window.closeModal = function(modal) {
        if (!modal) return;
        modal.style.display = "none";
        modal.classList.add("hidden");
        document.body.style.overflow = "";
    };

    // 모달 닫기 버튼 공통 이벤트 연결
    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = this.closest('.fixed');
            if (modal) window.closeModal(modal);
        });
    });

    // 외부 영역 클릭 시 모달 닫기
    const modals = ['cartSuccessModal', 'cartConfirmModal', 'sizeGuideModal'];
    modals.forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.addEventListener("click", (e) => {
                if (e.target === modal) window.closeModal(modal);
            });
        }
    });

    // ----------------------------------------------------
    // Tab Switching
    // ----------------------------------------------------
    const tabBtns = document.querySelectorAll(".tab-btn");
    const tabContents = document.querySelectorAll(".tab-content");

    function activateTab(targetId) {
        tabBtns.forEach(b => {
            b.classList.remove("border-primary", "text-primary");
            b.classList.add("border-transparent", "text-text-muted");
        });
        tabContents.forEach(c => c.classList.add("hidden"));

        const activeBtn = document.querySelector(`.tab-btn[data-tab="${targetId}"]`);
        const activeContent = document.getElementById(targetId);
        
        if (activeBtn) {
            activeBtn.classList.add("border-primary", "text-primary");
            activeBtn.classList.remove("border-transparent", "text-text-muted");
        }
        if (activeContent) {
            activeContent.classList.remove("hidden");
        }
    }

    tabBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const targetId = btn.getAttribute("data-tab");
            activateTab(targetId);
            history.replaceState(null, "", "#" + targetId);
            document.getElementById(targetId)?.scrollIntoView({ behavior: "smooth" });
        });
    });

    // 초기 해시 체크
    if (window.location.hash) {
        const hash = window.location.hash.substring(1);
        if (['details', 'reviews', 'qna', 'shipping'].includes(hash)) {
            activateTab(hash);
        }
    }

    // Top Review Link
    const topReviewLink = document.getElementById("top-review-link");
    if (topReviewLink) {
        topReviewLink.addEventListener("click", (e) => {
            e.preventDefault();
            activateTab("reviews");
            document.getElementById("reviews")?.scrollIntoView({ behavior: "smooth" });
        });
    }

    // Load More Reviews
    const loadMoreBtn = document.getElementById("loadMoreReviews");
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener("click", () => {
            const hiddenReviews = document.querySelectorAll(".review-item.hidden");
            for (let i = 0; i < 5 && i < hiddenReviews.length; i++) {
                hiddenReviews[i].classList.remove("hidden");
            }
            if (document.querySelectorAll(".review-item.hidden").length === 0) {
                loadMoreBtn.parentElement.classList.add("hidden");
            }
        });
    }

    // ----------------------------------------------------
    // Product Gallery & Zoom
    // ----------------------------------------------------
    const thumbBtns = document.querySelectorAll(".thumb-btn");
    const mainImg = document.getElementById("main-product-image");
    
    thumbBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            thumbBtns.forEach(b => b.classList.remove("active", "border-primary", "opacity-100"));
            btn.classList.add("active", "border-primary", "opacity-100");
            
            const imgEl = btn.querySelector("img");
            if (imgEl && mainImg) {
                const src = imgEl.src;
                mainImg.style.opacity = "0.5";
                setTimeout(() => { 
                    mainImg.src = src; 
                    mainImg.style.opacity = "1"; 
                }, 150);
            }
        });
    });

    const zoomModal = document.getElementById("imageZoomModal");
    const zoomImage = document.getElementById("zoomImage");
    const zoomClose = document.getElementById("zoomClose");
    if (mainImg && zoomModal && zoomImage && zoomClose) {
        mainImg.addEventListener("click", () => { 
            zoomImage.src = mainImg.src; 
            window.openModal(zoomModal); 
        });
        zoomClose.addEventListener("click", () => window.closeModal(zoomModal));
    }

    // ----------------------------------------------------
    // Options Selection 
    // ----------------------------------------------------
    const colorBtns = document.querySelectorAll(".color-btn");
    colorBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            colorBtns.forEach(b => b.classList.remove("ring-2", "ring-primary"));
            btn.classList.add("ring-2", "ring-primary");
            selectedColor = btn.getAttribute("data-color-name");
            const label = document.getElementById("colorLabel");
            if (label) label.textContent = selectedColor;
        });
    });

    const sizeBtns = document.querySelectorAll(".size-option-btn");
    sizeBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            sizeBtns.forEach(b => {
                b.classList.remove("border-2", "border-primary", "bg-primary/5", "text-primary", "shadow-sm");
                b.classList.add("border", "border-gray-300", "bg-white", "text-text-main");
            });
            btn.classList.remove("border", "border-gray-300", "bg-white", "text-text-main");
            btn.classList.add("border-2", "border-primary", "bg-primary/5", "text-primary", "shadow-sm");
            selectedSize = btn.textContent.trim();
        });
    });

    // ----------------------------------------------------
    // Size Guide 
    // ----------------------------------------------------
    const sizeGuideModal = document.getElementById("sizeGuideModal");
    const sgBtn = document.getElementById("sizeGuideBtn");
    const sgClose = document.getElementById("sizeGuideClose");
    if (sizeGuideModal && sgBtn && sgClose) {
        sgBtn.addEventListener("click", () => window.openModal(sizeGuideModal));
        sgClose.addEventListener("click", () => window.closeModal(sizeGuideModal));
    }

    // ----------------------------------------------------
    // Price Calculation
    // ----------------------------------------------------
    function updateQuantity(newQty) {
        quantity = Math.max(1, Math.min(99, newQty));
        const totalItemPrice = BASE_PRICE * quantity;
        let shippingFee = 0;

        if (config.shippingType === '무료') {
            shippingFee = 0;
        } else if (config.shippingType === '고정') {
            shippingFee = config.shippingFee;
        } else {
            // 기본 조건부 배송
            shippingFee = totalItemPrice >= 50000 ? 0 : 3000;
        }

        const qDisp = document.getElementById("qtyDisplay");
        const tPrice = document.getElementById("totalPrice");
        const sInfo = document.getElementById("shippingFeeInfo");

        if (qDisp) qDisp.textContent = quantity;
        if (tPrice) tPrice.textContent = "₩" + (totalItemPrice + shippingFee).toLocaleString();
        
        if (sInfo) {
            if (shippingFee > 0) {
                sInfo.textContent = `(배송비 ₩${shippingFee.toLocaleString()} 포함)`;
                sInfo.classList.remove('hidden');
            } else {
                sInfo.textContent = "(무료배송 적용됨)";
                sInfo.classList.remove('hidden');
            }
        }
    }
    
    updateQuantity(1); // 초기화
    
    const qPlus = document.getElementById("qtyPlus");
    const qMinus = document.getElementById("qtyMinus");
    if (qPlus) qPlus.addEventListener("click", () => updateQuantity(quantity + 1));
    if (qMinus) qMinus.addEventListener("click", () => updateQuantity(quantity - 1));

    // ----------------------------------------------------
    // User Actions (Wishlist, Share, Cart, Buy)
    // ----------------------------------------------------

    // Wishlist Toggle
    const wBtn = document.getElementById("wishlist-btn");
    if (wBtn) {
        wBtn.addEventListener("click", async function() {
            if (IS_GUEST) {
                showToast("로그인이 필요한 서비스입니다", "login", "bg-red-500");
                setTimeout(() => location.href = config.routes.login, 1500);
                return;
            }

            const icon = this.querySelector(".material-symbols-outlined");
            
            try {
                const res = await fetch(config.routes.wishlistToggle, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                
                if (data.status === 'added') {
                    this.classList.add("text-primary");
                    this.classList.remove("text-text-main");
                    if (icon) icon.style.fontVariationSettings = "'FILL' 1";
                } else {
                    this.classList.remove("text-primary");
                    this.classList.add("text-text-main");
                    if (icon) icon.style.fontVariationSettings = "'FILL' 0";
                }
                
                const toastIcon = data.status === 'added' ? "favorite" : "heart_broken";
                const toastColor = data.status === 'added' ? "bg-primary" : "bg-gray-600";
                showToast(data.message || '처리되었습니다.', toastIcon, toastColor);
                
                // 전역 헤더 찜 카운트 연동
                if (typeof WishlistUI !== 'undefined') {
                    WishlistUI.updateBadge(data.wishlistCount);
                } else if (data.wishlistCount !== undefined) {
                    const wishlistBadges = document.querySelectorAll(".header-wishlist-count");
                    wishlistBadges.forEach(badge => {
                        badge.textContent = data.wishlistCount;
                        if (data.wishlistCount > 0) {
                            badge.classList.remove("hidden");
                            badge.classList.add("flex");
                        } else {
                            badge.classList.remove("flex");
                            badge.classList.add("hidden");
                        }
                    });
                }
            } catch (error) {
                console.error('Wishlist Error:', error);
                showToast("처리 중 문제가 발생했습니다.", "error", "bg-red-500");
            }
        });
    }

    // Share
    const sBtn = document.getElementById("share-btn");
    if (sBtn) {
        sBtn.addEventListener("click", async () => {
            try {
                await navigator.clipboard.writeText(location.href);
                showToast("링크가 클립보드에 복사되었습니다", "content_copy", "bg-green-600");
            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        });
    }

    // Add to Cart Logic
    const cBtn = document.getElementById("addToCartBtn");
    if (cBtn) {
        cBtn.addEventListener("click", async () => {
            if (IS_GUEST) {
                showToast("로그인이 필요한 서비스입니다", "login", "bg-red-500");
                setTimeout(() => location.href = config.routes.login, 1500);
                return;
            }

            if (HAS_COLORS && !selectedColor) {
                showToast("색상을 선택해주세요", "error", "bg-red-500");
                return;
            }
            if (HAS_SIZES && !selectedSize) {
                showToast("사이즈를 선택해주세요", "error", "bg-red-500");
                return;
            }

            const addToCart = async (force = false) => {
                try {
                    const res = await fetch(config.routes.cartStore, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: PRODUCT_ID,
                            color: selectedColor,
                            size: selectedSize,
                            quantity: quantity,
                            force: force
                        })
                    });
                    
                    const data = await res.json();

                    if (data.status === 'duplicate') {
                        const cartModal = document.getElementById("cartConfirmModal");
                        if (!cartModal) return;
                        
                        window.openModal(cartModal);
                        
                        const proceedBtn = document.getElementById("cartConfirmProceed");
                        const cancelBtn = document.getElementById("cartConfirmCancel");
                        
                        const onProceed = () => {
                            window.closeModal(cartModal);
                            addToCart(true);
                            cleanup();
                        };
                        const onCancel = () => {
                            window.closeModal(cartModal);
                            cleanup();
                        };
                        const cleanup = () => {
                            proceedBtn.removeEventListener("click", onProceed);
                            cancelBtn.removeEventListener("click", onCancel);
                        };
                        
                        proceedBtn.addEventListener("click", onProceed);
                        cancelBtn.addEventListener("click", onCancel);

                    } else if (data.status === 'success') {
                        const successModal = document.getElementById("cartSuccessModal");
                        if (successModal) window.openModal(successModal);
                        
                        const cartBadges = document.querySelectorAll(".header-cart-count");
                        cartBadges.forEach(badge => {
                            badge.textContent = data.cart_count;
                            badge.classList.remove("hidden");
                        });
                    } else {
                        showToast(data.message || "처리에 실패했습니다", "error", "bg-red-500");
                    }
                } catch (error) {
                    console.error('Cart Store Error:', error);
                    showToast("오류가 발생했습니다", "error", "bg-red-500");
                }
            };

            await addToCart();
        });
    }

    // Buy Now
    const buyNowBtn = document.getElementById("buyNowBtn");
    if (buyNowBtn) {
        buyNowBtn.addEventListener("click", async () => {
            if (IS_GUEST) {
                showToast("로그인이 필요한 서비스입니다", "login", "bg-red-500");
                setTimeout(() => location.href = config.routes.login, 1500);
                return;
            }

            if (HAS_COLORS && !selectedColor) {
                showToast("색상을 선택해주세요", "error", "bg-red-500");
                return;
            }
            if (HAS_SIZES && !selectedSize) {
                showToast("사이즈를 선택해주세요", "error", "bg-red-500");
                return;
            }

            try {
                const res = await fetch(config.routes.buyNow, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': config.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: PRODUCT_ID,
                        color: selectedColor,
                        size: selectedSize,
                        quantity: quantity
                    })
                });

                const data = await res.json();
                
                if (data.redirect) {
                    location.href = data.redirect;
                } else {
                    showToast(data.message || "처리에 실패했습니다", "error", "bg-red-500");
                }
            } catch (err) {
                console.error("Buy Now Error", err);
                showToast("오류가 발생했습니다", "error", "bg-red-500");
            }
        });
    }

    // ----------------------------------------------------
    // Other Utilities
    // ----------------------------------------------------
});

// 전역 공개 (블레이드 인라인 스크립트에서도 사용)
window.deleteInquiry = async function(id) {
    if (!await (typeof showConfirm === 'function' ? showConfirm('정말 이 문의를 삭제하시겠어요?') : confirm('정말 이 문의를 삭제하시겠어요?'))) return;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const res = await fetch(`/qna/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (res.ok) {
            if (typeof showToast === 'function') {
                showToast(data.message || '삭제되었습니다.', 'delete', 'bg-red-500');
            }
            setTimeout(() => location.reload(), 1500);
        } else {
            if (typeof showToast === 'function') {
                showToast(data.message || '삭제 중 오류가 발생했습니다.', 'error', 'bg-red-500');
            }
        }
    } catch(err) {
        console.error('Delete Inquiry Error:', err);
        if (typeof showToast === 'function') {
            showToast('통신 오류가 발생했습니다.', 'error', 'bg-red-500');
        }
    }
};

<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController; // 사용자용 EventController 추가! 
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\OotdController;
use App\Http\Controllers\SupportController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ExhibitionController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\MagazineController as AdminMagazineController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\OotdController as AdminOotdController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\InquiryController; // InquiryController 추가! 
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\PointController as AdminPointController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- 관리자 페이지 (Admin Dashboard) ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest (Not logged in) Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware(['auth:admin', 'admin.permission'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // 검색 로그 관리 
    // 리뷰 관리 ️
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('reviews.show');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/search-logs', [\App\Http\Controllers\Admin\SearchLogController::class, 'index'])->name('search-logs.index');
    Route::delete('/search-logs/{search_log}', [\App\Http\Controllers\Admin\SearchLogController::class, 'destroy'])->name('search-logs.destroy');
    Route::post('/search-logs/clear', [\App\Http\Controllers\Admin\SearchLogController::class, 'clearAll'])->name('search-logs.clear');

    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/trash', [AdminEventController::class, 'trash'])->name('events.trash');
    Route::get('/events/search-members', [AdminEventController::class, 'searchMembers'])->name('events.search-members');
    Route::get('/events/{event}/participants', [AdminEventController::class, 'participants'])->name('events.participants');
    Route::get('/events/{event}/participants/export', [AdminEventController::class, 'exportParticipants'])->name('events.participants.export');
    Route::patch('/events/{event}/participants/{member}/toggle-winner', [AdminEventController::class, 'toggleParticipantWinner'])->name('events.participants.toggle-winner');
    Route::get('/events/create', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}/toggle-hero', [AdminEventController::class, 'toggleHero'])->name('events.toggle-hero');
    Route::put('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::patch('/events/{event}/restore', [AdminEventController::class, 'restore'])->withTrashed()->name('events.restore');
    Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    Route::delete('/events/{event}/force', [AdminEventController::class, 'forceDestroy'])->withTrashed()->name('events.force-destroy');
    Route::get('/exhibitions', [ExhibitionController::class, 'index'])->name('exhibitions.index');
    Route::get('/exhibitions/trash', [ExhibitionController::class, 'trash'])->name('exhibitions.trash');
    Route::get('/exhibitions/create', [ExhibitionController::class, 'create'])->name('exhibitions.create');
    Route::post('/exhibitions', [ExhibitionController::class, 'store'])->name('exhibitions.store');
    Route::get('/exhibitions/{exhibition}', [ExhibitionController::class, 'show'])->name('exhibitions.show');
    Route::get('/exhibitions/{exhibition}/edit', [ExhibitionController::class, 'edit'])->name('exhibitions.edit');
    Route::put('/exhibitions/{exhibition}', [ExhibitionController::class, 'update'])->name('exhibitions.update');
    Route::patch('/exhibitions/{exhibition}/restore', [ExhibitionController::class, 'restore'])->withTrashed()->name('exhibitions.restore');
    Route::delete('/exhibitions/{exhibition}', [ExhibitionController::class, 'destroy'])->name('exhibitions.destroy');
    Route::delete('/exhibitions/{exhibition}/force', [ExhibitionController::class, 'forceDestroy'])->withTrashed()->name('exhibitions.force-destroy');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/members/trash', [AdminMemberController::class, 'trash'])->name('members.trash');
    Route::get('/members/{member}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::patch('/members/{member}', [AdminMemberController::class, 'update'])->name('members.update');
    Route::patch('/members/{member}/restore', [AdminMemberController::class, 'restore'])->withTrashed()->name('members.restore');
    Route::delete('/members/{member}', [AdminMemberController::class, 'destroy'])->name('members.destroy');
    Route::delete('/members/{member}/force', [AdminMemberController::class, 'forceDestroy'])->withTrashed()->name('members.force-destroy');
    Route::get('/operators', [OperatorController::class, 'index'])->name('operators.index');
    Route::get('/operators/trash', [OperatorController::class, 'trash'])->name('operators.trash');
    Route::get('/operators/create', [OperatorController::class, 'create'])->name('operators.create');
    Route::post('/operators', [OperatorController::class, 'store'])->name('operators.store');
    Route::get('/operators/{operator}', [OperatorController::class, 'show'])->name('operators.show');
    Route::patch('/operators/{operator}', [OperatorController::class, 'update'])->name('operators.update');
    Route::patch('/operators/{operator}/restore', [OperatorController::class, 'restore'])->withTrashed()->name('operators.restore');
    Route::delete('/operators/{operator}', [OperatorController::class, 'destroy'])->name('operators.destroy');
    Route::delete('/operators/{operator}/force', [OperatorController::class, 'forceDestroy'])->withTrashed()->name('operators.force-destroy');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/trash', [OrderController::class, 'trash'])->name('orders.trash');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::patch('/orders/{order}/restore', [OrderController::class, 'restore'])->withTrashed()->name('orders.restore');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}/force', [OrderController::class, 'forceDestroy'])->withTrashed()->name('orders.force-destroy');

    // 교환/반품 관리
    Route::get('/order-claims', [\App\Http\Controllers\Admin\OrderClaimController::class, 'index'])->name('order-claims.index');
    Route::get('/order-claims/trash', [\App\Http\Controllers\Admin\OrderClaimController::class, 'trash'])->name('order-claims.trash');
    Route::get('/order-claims/{order_claim}', [\App\Http\Controllers\Admin\OrderClaimController::class, 'show'])->name('order-claims.show');
    Route::patch('/order-claims/{order_claim}', [\App\Http\Controllers\Admin\OrderClaimController::class, 'update'])->name('order-claims.update');
    Route::patch('/order-claims/{order_claim}/restore', [\App\Http\Controllers\Admin\OrderClaimController::class, 'restore'])->withTrashed()->name('order-claims.restore');
    Route::delete('/order-claims/{order_claim}', [\App\Http\Controllers\Admin\OrderClaimController::class, 'destroy'])->name('order-claims.destroy');
    Route::delete('/order-claims/{order_claim}/force', [\App\Http\Controllers\Admin\OrderClaimController::class, 'forceDestroy'])->withTrashed()->name('order-claims.force-destroy');

    // 적립금 관리
    Route::get('/points', [AdminPointController::class, 'index'])->name('points.index');
    Route::get('/points/search-members', [AdminPointController::class, 'searchMembers'])->name('points.search-members');
    Route::post('/points', [AdminPointController::class, 'store'])->name('points.store');

    // 쿠폰 관리
    Route::resource('coupons', AdminCouponController::class);

    Route::get('/products', [AdminController::class, 'productList'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'productCreate'])->name('products.create');
    Route::post('/products', [AdminController::class, 'productStore'])->name('products.store');
    Route::get('/products/search', [AdminController::class, 'productSearch'])->name('products.search');
    Route::get('/products/{product}', [AdminController::class, 'productShow'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminController::class, 'productEdit'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'productUpdate'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'productDestroy'])->name('products.destroy');
    Route::get('/categories', [AdminController::class, 'categoryList'])->name('categories.index');
    Route::post('/categories', [AdminController::class, 'categoryStore'])->name('categories.store');
    Route::get('/categories/create', [AdminController::class, 'categoryCreate'])->name('categories.create');
    Route::get('/categories/{category}/edit', [AdminController::class, 'categoryEdit'])->name('categories.edit');

    // 알림 발송 관리
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{log}', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])->name('notifications.show');

    // 알림 템플릿 관리
    Route::get('/notification-templates', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'index'])->name('notification-templates.index');
    Route::post('/notification-templates/update-test-mode', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'updateTestMode'])->name('notification-templates.update-test-mode');
    Route::get('/notification-templates/{template}/edit', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'edit'])->name('notification-templates.edit');
    Route::put('/notification-templates/{template}', [\App\Http\Controllers\Admin\NotificationTemplateController::class, 'update'])->name('notification-templates.update');
    Route::put('/categories/{category}', [AdminController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'categoryDestroy'])->name('categories.destroy');
    Route::post('/categories/reorder', [AdminController::class, 'categoryReorder'])->name('categories.reorder');
    Route::get('/products/search', [AdminController::class, 'productSearch'])->name('products.search');

    // 문의 관리 
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}/answer', [InquiryController::class, 'updateAnswer'])->name('inquiries.answer');
    Route::delete('/inquiries/{inquiry}', [InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // 색상 관리 라우트
    Route::resource('/colors', ColorController::class)->except(['create', 'show'])->names('colors');

    // 사이즈 관리 라우트
    Route::get('/sizes', [SizeController::class, 'index'])->name('sizes.index');
    Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');
    Route::post('/sizes/groups', [SizeController::class, 'storeGroup'])->name('sizes.groups.store');
    Route::patch('/sizes/groups/{group}', [SizeController::class, 'updateGroup'])->name('sizes.groups.update');
    Route::delete('/sizes/groups/{group}', [SizeController::class, 'destroyGroup'])->name('sizes.groups.destroy');

    // 매거진 관리 라우트 
    Route::resource('/magazines', AdminMagazineController::class)->names('magazines');
    Route::resource('/notices', AdminNoticeController::class)->names('notices');
    Route::resource('/ootds', AdminOotdController::class)->names('ootds');
    Route::resource('/faqs', AdminFaqController::class)->names('faqs');

    // 메뉴 관리 라우트
    Route::resource('/menus', AdminMenuController::class)->names('menus');

    // 추가적인 관리자 라우트는 여기에 들어올 예정입니다
    });
});

// --- 사용자 페이지 ---

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MemberController::class, 'index'])->name('mypage');
    Route::get('/mypage/order-list', [MemberController::class, 'orderList'])->name('mypage.order-list');
    Route::get('/mypage/orders/{order_number}', [MemberController::class, 'orderDetail'])->name('mypage.order-detail');
    Route::get('/mypage/orders/{order_number}/exchange-return', [MemberController::class, 'exchangeReturnForm'])->name('mypage.exchange-return');
    Route::post('/mypage/orders/{order_number}/exchange-return', [MemberController::class, 'storeExchangeReturn'])->name('mypage.exchange-return.store');
    Route::post('/mypage/orders/{order_number}/cancel', [MemberController::class, 'cancelOrder'])->name('mypage.order-cancel');
    Route::post('/mypage/orders/{order_number}/confirm', [MemberController::class, 'confirmPurchase'])->name('mypage.order-confirm');
    Route::get('/mypage/orders/{order_number}/receipt', [MemberController::class, 'printReceipt'])->name('mypage.order-receipt');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 찜하기
    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/delete-selected', [WishlistController::class, 'destroySelected'])->name('wishlist.delete-selected');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clearAll'])->name('wishlist.clear');

    // 장바구니
    // 장바구니 관리
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/bulk', [CartController::class, 'bulkDestroy'])->name('cart.bulk-destroy');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // 결제 / 바로구매
    Route::post('/buy-now', [CheckoutController::class, 'buyNow'])->name('buy-now');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/verify', [CheckoutController::class, 'verifyPayment'])->name('checkout.verify');

    // 리뷰 작성 및 관리
    Route::get('/review/write', [ReviewController::class, 'create'])->name('review.write');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/review/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::post('/review/{review}/update', [ReviewController::class, 'update'])->name('review.update');

    // 회원정보 수정 
    Route::get('/mypage/profile', [MemberController::class, 'confirmPasswordForm'])->name('mypage.profile');
    Route::post('/mypage/profile/confirm', [MemberController::class, 'confirmPassword'])->name('mypage.profile.confirm');
    Route::get('/mypage/profile-edit', [MemberController::class, 'profileEditForm'])->name('mypage.profile-edit');
    Route::patch('/mypage/profile-edit', [MemberController::class, 'updateProfile'])->name('mypage.profile.update');

    Route::get('/mypage/review', [MemberController::class, 'reviewList'])->name('mypage.review');
    Route::get('/mypage/cancel-list', [MemberController::class, 'cancelList'])->name('mypage.cancel-list');
    Route::get('/mypage/refund-list', [MemberController::class, 'refundList'])->name('mypage.refund-list');
    Route::get('/mypage/wishlist', [MemberController::class, 'wishlist'])->name('mypage.wishlist');
    Route::get('/mypage/recent', [MemberController::class, 'recentViewList'])->name('mypage.recent');
    Route::delete('/mypage/recent/selected', [MemberController::class, 'deleteSelectedRecentViews'])->name('mypage.recent.delete-selected');
    Route::delete('/mypage/recent/clear', [MemberController::class, 'clearRecentViews'])->name('mypage.recent.clear');
    Route::get('/mypage/receipt', [MemberController::class, 'receiptList'])->name('mypage.receipt');
    Route::get('/mypage/withdraw', function () { 
        return view('pages.mypage-withdraw', ['member' => auth()->user()]); 
    })->name('mypage.withdraw');
    Route::post('/mypage/withdraw', [MemberController::class, 'withdraw'])->name('mypage.withdraw.post');
    Route::get('/mypage/inquiry', [MemberController::class, 'inquiryList'])->name('mypage.inquiry');
    Route::post('/mypage/inquiry', [MemberController::class, 'storeInquiry'])->name('mypage.inquiry.store');
    Route::get('/qna/write', function () { return view('pages.qna-write'); })->name('qna.write');
    Route::get('/qna/{inquiry}/edit', [MemberController::class, 'editInquiry'])->name('qna.edit');
    Route::post('/qna/{inquiry}/update', [MemberController::class, 'updateInquiry'])->name('qna.update');
    Route::delete('/qna/{inquiry}', [MemberController::class, 'destroyInquiry'])->name('qna.destroy');
    Route::get('/mypage/coupon', [MemberController::class, 'couponList'])->name('mypage.coupon');
    Route::post('/mypage/coupon/register', [MemberController::class, 'registerCoupon'])->name('mypage.coupon.register');
    Route::get('/mypage/point', [MemberController::class, 'pointList'])->name('mypage.point');

    // OOTD 등록, 좋아요, 수정, 삭제 
    Route::get('/community/ootd/create', [OotdController::class, 'create'])->name('ootd.create');
    Route::post('/community/ootd', [OotdController::class, 'store'])->name('ootd.store');
    Route::post('/community/ootd/{ootd}/like', [OotdController::class, 'toggleLike'])->name('ootd.like');
    Route::get('/community/ootd/{ootd}/edit', [OotdController::class, 'edit'])->name('ootd.edit');
    Route::put('/community/ootd/{ootd}', [OotdController::class, 'update'])->name('ootd.update');
    Route::delete('/community/ootd/{ootd}', [OotdController::class, 'destroy'])->name('ootd.destroy');
});


// --- 상품 관련 ---
Route::get('/product-list', [ProductController::class, 'index'])->name('product-list');
Route::get('/products/new', [ProductController::class, 'newArrivals'])->name('products.new');
Route::get('/products/best', [ProductController::class, 'bestProducts'])->name('products.best');
Route::get('/product-detail/{slug}', [ProductController::class, 'show'])->name('product-detail');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/autocomplete', [ProductController::class, 'autocomplete'])->name('products.autocomplete');

Route::get('/products/bulk-quick-view', [ProductController::class, 'getBulkQuickViewData'])->name('product-bulk-quick-view');
Route::get('/products/{id}/quick-view', [ProductController::class, 'getQuickViewData'])->name('product-quick-view');

// 인증 및 검증 관련 (로그인하지 않은 사용자만 접근 가능)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');
    
    // 아이디 / 비밀번호 찾기
    Route::get('/find-email', [AuthController::class, 'showFindEmailForm'])->name('email.find');
    Route::post('/find-email', [AuthController::class, 'findEmail'])->name('email.find.post');
    Route::get('/find-password', [AuthController::class, 'showFindPasswordForm'])->name('password.find');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.post');

    // 소셜 로그인
    Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('login.social');
    Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('login.social.callback');
});

// 휴대폰 및 이메일 인증 (로그인 여부와 상관없이 접근 가능하도록 밖으로 이동 ✨)
Route::post('/verify-phone/send', [VerificationController::class, 'sendCode'])->name('verify.phone.send');
Route::post('/verify-phone/confirm', [VerificationController::class, 'verifyCode'])->name('verify.phone.confirm');
Route::post('/verify-email/send', [VerificationController::class, 'sendEmailCode'])->name('email.send');
Route::post('/verify-email/confirm', [VerificationController::class, 'verifyEmailCode'])->name('email.verify');

// 공통 페이지 (인증 여부와 상관없이 접근 가능)
Route::get('/support', [SupportController::class, 'index'])->name('support');
Route::get('/support/notice', [SupportController::class, 'notices'])->name('support.notice');
Route::get('/support/membership', function () { return view('pages.support-membership'); })->name('support.membership');
Route::get('/support/exchange', function () { return view('pages.support-exchange'); })->name('support.exchange');

Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::get('/community/magazine/more', [CommunityController::class, 'moreMagazines'])->name('magazine.more-data');
Route::get('/community/ootd/more', [CommunityController::class, 'moreOotds'])->name('ootd.more-data');
Route::get('/community/notice/more', [CommunityController::class, 'moreNotices'])->name('notice.more-data'); // 공지사항 더보기 추가! 
Route::get('/community/notice', function () { return view('pages.community-notice'); })->name('community.notice');

Route::get('/community/membership', function () { return view('pages.community-membership'); })->name('community.membership');
Route::get('/community/exchange', function () { return view('pages.community-exchange'); })->name('community.exchange');
// 이벤트 페이지 관련
Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/event/more', [EventController::class, 'loadMore'])->name('event.more');
Route::post('/event/{event}/participate', [EventController::class, 'participate'])->name('event.participate.submit')->middleware('auth');
Route::delete('/event/{event}/participate', [EventController::class, 'cancelParticipation'])->name('event.participate.cancel')->middleware('auth');
Route::get('/event/participate', function () { return view('pages.event-participate'); })->name('event.participate');

// 기획전 라우트 추가! 
Route::get('/exhibition', [\App\Http\Controllers\ExhibitionController::class, 'index'])->name('exhibition.index');
Route::get('/exhibition/{slug}', [\App\Http\Controllers\ExhibitionController::class, 'show'])->name('exhibition.show');

// Route::get('/mypage/inquiry', function () { return view('pages.mypage-inquiry'); })->name('mypage.inquiry'); // 삭제! 

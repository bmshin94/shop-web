<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MemberController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ExhibitionController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SettingController;
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
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/trash', [EventController::class, 'trash'])->name('events.trash');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::patch('/events/{event}/restore', [EventController::class, 'restore'])->withTrashed()->name('events.restore');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::delete('/events/{event}/force', [EventController::class, 'forceDestroy'])->withTrashed()->name('events.force-destroy');
    Route::get('/exhibitions', [ExhibitionController::class, 'index'])->name('exhibitions.index');
    Route::get('/exhibitions/trash', [ExhibitionController::class, 'trash'])->name('exhibitions.trash');
    Route::get('/exhibitions/create', [ExhibitionController::class, 'create'])->name('exhibitions.create');
    Route::post('/exhibitions', [ExhibitionController::class, 'store'])->name('exhibitions.store');
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
    Route::delete('/orders/{order}/force', [OrderController::class, 'forceDestroy'])->withTrashed()->name('orders.force-destroy');
    Route::get('/products', [AdminController::class, 'productList'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'productCreate'])->name('products.create');
    Route::post('/products', [AdminController::class, 'productStore'])->name('products.store');
    Route::get('/products/search', [AdminController::class, 'productSearch'])->name('products.search');
    Route::get('/products/{product}', [AdminController::class, 'productShow'])->name('products.show');
    Route::get('/products/{product}/edit', [AdminController::class, 'productEdit'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'productUpdate'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'productDestroy'])->name('products.destroy');
    Route::get('/categories', [AdminController::class, 'categoryList'])->name('categories.index');
    Route::get('/categories/create', [AdminController::class, 'categoryCreate'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'categoryStore'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'categoryEdit'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'categoryDestroy'])->name('categories.destroy');
    Route::post('/categories/reorder', [AdminController::class, 'categoryReorder'])->name('categories.reorder');
    Route::get('/products/search', [AdminController::class, 'productSearch'])->name('products.search');

    // 색상 관리 라우트
    Route::resource('/colors', ColorController::class)->except(['create', 'show'])->names('colors');

    // 사이즈 관리 라우트
    Route::get('/sizes', [SizeController::class, 'index'])->name('sizes.index');
    Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
    Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');
    Route::post('/sizes/groups', [SizeController::class, 'storeGroup'])->name('sizes.groups.store');
    Route::delete('/sizes/groups/{group}', [SizeController::class, 'destroyGroup'])->name('sizes.groups.destroy');

    // 메뉴 관리 라우트
    Route::resource('/menus', AdminMenuController::class)->names('menus');

    // 추가적인 관리자 라우트는 여기에 들어올 예정입니다
    });
});

// --- 사용자 페이지 ---

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MemberController::class, 'index'])->name('mypage');
    Route::get('/mypage/order-list', [MemberController::class, 'orderList'])->name('mypage.order-list');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --- 1단계 배치 (상품/장바구니) ---
Route::get('/product-list', [ProductController::class, 'index'])->name('product-list');
Route::get('/products/new', [ProductController::class, 'newArrivals'])->name('products.new');
Route::get('/products/best', [ProductController::class, 'bestProducts'])->name('products.best');

Route::get('/product-detail/{slug}', [ProductController::class, 'show'])->name('product-detail');

Route::get('/cart', function () {
    return view('pages.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('pages.checkout');
})->name('checkout');

// 인증 및 검증 관련 (로그인하지 않은 사용자만 접근 가능)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check-email');

    // 비밀번호 찾기
    Route::get('/find-password', [AuthController::class, 'showFindPasswordForm'])->name('password.find');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset.post');

    // 아이디 찾기
    Route::get('/find-email', [AuthController::class, 'showFindEmailForm'])->name('email.find');
    Route::post('/find-email', [AuthController::class, 'findEmail'])->name('email.find.post');

    // 소셜 로그인
    Route::get('/login/{provider}', [AuthController::class, 'redirectToProvider'])->name('login.social');
    Route::get('/login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

    // 인증 발송 (가입/찾기 공용이므로 여기 포함)
    Route::post('/sms/send', [VerificationController::class, 'sendCode'])->name('sms.send');
    Route::post('/sms/verify', [VerificationController::class, 'verifyCode'])->name('sms.verify');
    Route::post('/email/send', [VerificationController::class, 'sendEmailCode'])->name('email.send');
    Route::post('/email/verify', [VerificationController::class, 'verifyEmailCode'])->name('email.verify');
});

// 로그인한 사용자 전용
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- 2단계 배치 (마이페이지 하위) ---
Route::get('/mypage/order-detail', function () {
    return view('pages.mypage-order-detail');
})->name('mypage.order-detail');

Route::get('/mypage/claim-list', function () {
    return view('pages.mypage-claim-list');
})->name('mypage.claim-list');

Route::get('/mypage/refund-list', function () {
    return view('pages.mypage-refund-list');
})->name('mypage.refund-list');

Route::get('/mypage/coupon', function () {
    return view('pages.mypage-coupon');
})->name('mypage.coupon');

Route::get('/mypage/point', function () {
    return view('pages.mypage-point');
})->name('mypage.point');

Route::get('/mypage/deposit', function () {
    return view('pages.mypage-deposit');
})->name('mypage.deposit');

// --- 3단계 배치 (마이페이지 나머지 + 고객센터) ---
Route::get('/mypage/profile', function () { return view('pages.mypage-profile'); })->name('mypage.profile');
Route::get('/mypage/profile-edit', function () { return view('pages.mypage-profile-edit'); })->name('mypage.profile-edit');
Route::get('/mypage/review', function () { return view('pages.mypage-review'); })->name('mypage.review');
Route::get('/mypage/wishlist', function () { return view('pages.mypage-wishlist'); })->name('mypage.wishlist');
Route::get('/mypage/recent', function () { return view('pages.mypage-recent'); })->name('mypage.recent');
Route::get('/mypage/receipt', function () { return view('pages.mypage-receipt'); })->name('mypage.receipt');
Route::get('/mypage/withdraw', function () { return view('pages.mypage-withdraw'); })->name('mypage.withdraw');

Route::get('/support', function () { return view('pages.support'); })->name('support');
Route::get('/support/notice', function () { return view('pages.support-notice'); })->name('support.notice');
Route::get('/support/membership', function () { return view('pages.support-membership'); })->name('support.membership');
Route::get('/support/exchange', function () { return view('pages.support-exchange'); })->name('support.exchange');

// --- 4단계 배치 (커뮤니티, 이벤트, 기타) ---
Route::get('/community', function () { return view('pages.community'); })->name('community');
Route::get('/community/notice', function () { return view('pages.community-notice'); })->name('community.notice');
Route::get('/community/membership', function () { return view('pages.community-membership'); })->name('community.membership');
Route::get('/community/exchange', function () { return view('pages.community-exchange'); })->name('community.exchange');

Route::get('/event', function () { return view('pages.event'); })->name('event');
Route::get('/event/participate', function () { return view('pages.event-participate'); })->name('event.participate');

Route::get('/exhibition', function () { return view('pages.exhibition'); })->name('exhibition');
Route::get('/qna/write', function () { return view('pages.qna-write'); })->name('qna.write');
Route::get('/review/write', function () { return view('pages.review-write'); })->name('review.write');
Route::get('/mypage/inquiry', function () { return view('pages.mypage-inquiry'); })->name('mypage.inquiry');

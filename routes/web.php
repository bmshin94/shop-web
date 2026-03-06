<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('pages.index');
})->name('home');

Route::get('/login', function () {
    return view('pages.login');
})->name('login');

Route::get('/mypage', function () {
    return view('pages.mypage');
})->name('mypage');

// --- 1단계 배치 (상품/장바구니) ---
Route::get('/product-list', function () {
    return view('pages.product-list');
})->name('product-list');

Route::get('/product-detail', function () {
    return view('pages.product-detail');
})->name('product-detail');

Route::get('/cart', function () {
    return view('pages.cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('pages.checkout');
})->name('checkout');

Route::get('/register', function () {
    return view('pages.register');
})->name('register');


// --- 2단계 배치 (마이페이지 하위) ---
Route::get('/mypage/order-list', function () {
    return view('pages.mypage-order-list');
})->name('mypage.order-list');

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
Route::get('/find-password', function () { return view('pages.find-password'); })->name('find-password');
Route::get('/qna/write', function () { return view('pages.qna-write'); })->name('qna.write');
Route::get('/review/write', function () { return view('pages.review-write'); })->name('review.write');

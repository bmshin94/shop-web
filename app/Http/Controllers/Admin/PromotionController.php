<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PromotionController extends Controller
{
    /**
     * 관리자 이벤트 관리 화면을 조회한다.
     *
     * @return View
     */
    public function events(): View
    {
        return view('admin.operations.events');
    }

    /**
     * 관리자 기획전 관리 화면을 조회한다.
     *
     * @return View
     */
    public function exhibitions(): View
    {
        return view('admin.operations.exhibitions');
    }
}

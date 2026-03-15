<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $homeService;

    /**
     * HomeController 생성자 ✨💖
     * 
     * @param HomeService $homeService
     */
    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    /**
     * 쇼핑몰 메인 페이지 출력! 🏠🎬✨
     */
    public function index()
    {
        // 홈 서비스에서 필요한 데이터들을 한꺼번에 가져와요! 😊
        $data = $this->homeService->getHomeData();

        // 뷰(pages.index)로 데이터를 보낼게요~ ✨💖
        return view('pages.index', $data);
    }
}

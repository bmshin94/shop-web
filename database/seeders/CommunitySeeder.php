<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magazine;
use App\Models\Ootd;
use App\Models\Notice;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 외래 키 검사 잠시 끄기 ️
        Schema::disableForeignKeyConstraints();

        Magazine::truncate();
        Ootd::truncate();
        Notice::truncate();
        DB::table('ootd_likes')->truncate(); // 좋아요 기록도 싹- 비우기! ️

        // 1. 매거진 샘플 데이터 (풍성하게! )
        $magData = [
            ['LIFESTYLE', '건강한 일상을 위한 러닝 가이드', '에디터 Jina', 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=800', '러닝은 가장 쉽고 효과적인 전신 운동입니다. '],
            ['WORKOUT', '홈트레이닝 필수템: 요가 매트 고르는 법', '에디터 Min', 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800', '나에게 딱 맞는 매트를 고르는 꿀팁! '],
            ['FASHION', '애슬레저 룩의 진화, 스타일리쉬하게', '트렌드 리포트', 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800', '일상에서도 힙하게 즐기는 애슬레저 룩! '],
            ['HEALTH', '운동 후 근육통, 어떻게 관리할까?', '에디터 Soo', 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=800', '효과적인 리커버리 방법을 알려드려요. '],
            ['FASHION', '봄 신상 크롭탑 코디 제안 ', '스타일팀', 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=800', '상큼한 컬러로 봄을 맞이해 보세요! '],
            ['WORKOUT', '필라테스 초보자가 알아야 할 5가지', '에디터 Luna', 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=800', '처음 시작하는 분들을 위한 완벽 가이드! '],
            ['LIFESTYLE', '직장인을 위한 10분 스트레칭 루틴', '에디터 Jin', 'https://images.unsplash.com/photo-1552196564-97ca98bb2d01?w=800', '사무실에서도 틈틈이 건강 챙기기! '],
            ['HEALTH', '여성을 위한 단백질 섭취 가이드', '영양사 Kim', 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800', '건강하고 예쁜 몸매를 위한 식단 팁! ']
        ];

        foreach ($magData as $data) {
            Magazine::create([
                'category' => $data[0],
                'title' => $data[1],
                'author' => $data[2],
                'image_url' => $data[3],
                'content' => $data[4] . "\n\n관리자가 추천하는 특별한 콘텐츠를 즐겨보세요! ",
                'is_visible' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // 2. 가상 회원들 생성 (OOTD용! )
        $members = [];
        $names = ['ActiveLuna', 'FitGirl99', 'PilatesMaster', 'JoggingLife', 'YogaQueen', 'HealthMania', 'StyleIcon', 'GymRat'];
        foreach ($names as $name) {
            $members[] = Member::firstOrCreate(
                ['email' => strtolower($name) . '@example.com'],
                [
                    'name' => $name,
                    'phone' => '010-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                    'password' => Hash::make('password'),
                    'status' => '활성',
                ]
            );
        }

        // 3. OOTD 샘플 데이터 (다양하게! )
        $ootdData = [
            ['https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?w=600', '오늘도 오운완!  #액티브우먼OOTD', rand(50, 500)],
            ['https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=600', '신상 레깅스 핏 너무 좋아요! ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1434596922112-19c563067271?w=600', '필라테스 수업 듣고 왔어요! ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600', '아침 조깅 완료! ️ 상쾌해요!', rand(50, 500)],
            ['https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=600', '운동복도 패션이죠! 힙하게~ ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1483721310020-03333e577078?w=600', '오늘의 헬스장 룩! ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?w=600', '복근아 생겨라! 열운 중! ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1554244933-d87676a10d89?w=600', '테니스룩으로도 딱이에요! ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600', '홈트 시작 전 한 컷! ', rand(50, 500)],
            ['https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=600', '요가복 색감이 너무 예뻐요... ', rand(50, 500)]
        ];

        foreach ($ootdData as $index => $data) {
            Ootd::create([
                'member_id' => $members[$index % count($members)]->id,
                'image_url' => $data[0],
                'content' => $data[1],
                'likes_count' => $data[2],
                'is_visible' => true,
                'instagram_url' => 'https://www.instagram.com/p/C' . rand(100000, 999999),
            ]);
        }

        // 4. 공지사항 샘플 데이터 (디테일하게! )
        $noticeData = [
            ['공지', '개인정보처리방침 개정 안내', true, '항상 저희 Active Women을 이용해 주셔서 감사합니다. '],
            ['일반', '스토어 앱 v2.1.0 업데이트 안내', false, '더욱 편리해진 앱을 만나보세요! '],
            ['이벤트', '봄맞이 리뷰왕 선발 대회! ', false, '예쁜 리뷰 남기고 혜택 받아 가세요! '],
            ['공지', '3월 카드사 무이자 할부 혜택 안내', false, '알뜰한 쇼핑 되세요! '],
            ['일반', '배송 서비스 점검으로 인한 출고 일시 중단 안내', true, '점검 기간 확인 부탁드려요! '],
            ['이벤트', '신규 회원 가입 시 10,000원 쿠폰 즉시 증정!', false, '지금 바로 가입하세요! '],
            ['공지', 'CS 고객센터 운영 시간 변경 안내', false, '이용에 참고 부탁드립니다. '],
            ['일반', '봄 신상 얼리버드 20% 할인 시작!', false, '누구보다 빠르게 득템하세요! ']
        ];

        foreach ($noticeData as $data) {
            Notice::create([
                'type' => $data[0],
                'title' => $data[1],
                'is_important' => $data[2],
                'content' => $data[3] . "\n\n궁금하신 점은 언제든 고객센터로 문의해 주세요! ",
                'is_visible' => true,
                'published_at' => now()->subDays(rand(1, 15)),
            ]);
        }

        // 외래 키 검사 다시 켜기 ️
        Schema::enableForeignKeyConstraints();
    }
}

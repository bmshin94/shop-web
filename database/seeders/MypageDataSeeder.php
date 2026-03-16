<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\RecentView;
use Illuminate\Support\Facades\DB;

class MypageDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 모든 회원 가져오기
        $members = Member::all();

        if ($members->isEmpty()) {
            $this->command->info('대상이 될 회원이 없어서 시딩을 건너뜁니다!');
            return;
        }

        // 2. 랜덤 상품 가져오기
        $products = Product::inRandomOrder()->take(50)->get();

        if ($products->isEmpty()) {
            $this->command->info('대상이 될 상품이 없어서 시딩을 건너뜁니다!');
            return;
        }

        // 3. 모든 회원에게 데이터 뿌리기
        foreach ($members as $member) {
            // 찜한 상품 초기화 및 생성
            Wishlist::where('member_id', $member->id)->delete();
            $wishlistProducts = $products->random(min(8, $products->count()));
            foreach ($wishlistProducts as $product) {
                Wishlist::create([
                    'member_id' => $member->id,
                    'product_id' => $product->id,
                ]);
            }

            // 최근 본 상품 초기화 및 생성
            RecentView::where('member_id', $member->id)->delete();
            $recentViewProducts = $products->random(min(12, $products->count()));
            foreach ($recentViewProducts as $index => $product) {
                RecentView::create([
                    'member_id' => $member->id,
                    'product_id' => $product->id,
                    'viewed_at' => now()->subHours($index * 2),
                ]);
            }
        }

        $this->command->info($members->count() . "명의 회원에게 찜 목록 및 최근 본 상품 데이터를 생성했습니다!");
    }
}

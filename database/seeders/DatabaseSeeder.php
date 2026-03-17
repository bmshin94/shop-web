<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class, // 사이트 기본 설정 추가! 🚀
            AdminMenuSeeder::class,
            CategorySeeder::class,
            ColorSeeder::class,
            SizeSeeder::class, // ProductSeeder 이전에 위치!
            ProductSeeder::class,
            MemberSeeder::class,
            OperatorSeeder::class,
            OrderSeeder::class,
            EventSeeder::class,
            ExhibitionSeeder::class,
            ReviewSeeder::class,
            CouponSeeder::class,
            InquirySeeder::class,
            CommunitySeeder::class,
            FaqSeeder::class,
        ]);
    }
}

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
            AdminMenuSeeder::class,
            CategorySeeder::class,
            ColorSeeder::class,
            ProductSeeder::class,
            SizeSeeder::class,
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

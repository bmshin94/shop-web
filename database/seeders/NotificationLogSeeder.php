<?php

namespace Database\Seeders;

use App\Models\NotificationLog;
use Illuminate\Database\Seeder;

class NotificationLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 기존 데이터를 싹 지우고 새로 채워넣을게! (테스트용)  
        NotificationLog::truncate();

        // 100개 가짜 데이터 생성!   
        NotificationLog::factory()->count(100)->create();
    }
}

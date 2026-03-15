<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Inquiry;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 회원이 한 명도 없으면 안 되니까! 
        $member = Member::first() ?? Member::factory()->create();

        // 1:1 문의 데이터 10개 생성 (답변 대기/완료 섞어서!) 
        Inquiry::factory()->count(10)->create([
            'member_id' => $member->id
        ]);

        $this->command->info('1:1 문의 테스트 데이터 10개가 생성되었습니다! ');
    }
}

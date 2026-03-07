<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * 회원 관리 샘플 데이터를 생성한다.
     */
    public function run(): void
    {
        Member::withTrashed()->forceDelete();

        Member::factory()
            ->count(10)
            ->create();
    }
}

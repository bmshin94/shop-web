<?php

namespace Database\Seeders;

use App\Models\Operator;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * 운영자 관리 샘플 데이터를 생성한다.
     */
    public function run(): void
    {
        Operator::withTrashed()->forceDelete();

        // 기본 관리자 계정 생성
        Operator::create([
            'name' => '최고관리자',
            'email' => 'admin@admin.com',
            'phone' => '010-0000-0000',
            'password' => \Illuminate\Support\Facades\Hash::make('m1124981'),
            'status' => '활성',
            'menu_permissions' => null, // 전체 권한
        ]);

        Operator::factory()
            ->count(10)
            ->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Exhibition;
use Illuminate\Database\Seeder;

class ExhibitionSeeder extends Seeder
{
    /**
     * 기획전 샘플 데이터를 10건 생성한다.
     *
     * @return void
     */
    public function run(): void
    {
        Exhibition::query()->delete();

        Exhibition::factory()
            ->count(10)
            ->create();
    }
}

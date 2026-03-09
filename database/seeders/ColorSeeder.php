<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            ['name' => '블랙', 'hex_code' => '#000000'],
            ['name' => '화이트', 'hex_code' => '#FFFFFF'],
            ['name' => '차콜', 'hex_code' => '#333333'],
            ['name' => '네이비', 'hex_code' => '#000080'],
            ['name' => '라벤더', 'hex_code' => '#E6E6FA'],
            ['name' => '민트', 'hex_code' => '#98FF98'],
            ['name' => '로즈핑크', 'hex_code' => '#FF66CC'],
            ['name' => '올리브', 'hex_code' => '#808000'],
            ['name' => '라임 그린', 'hex_code' => '#32CD32'],
            ['name' => '머스타드', 'hex_code' => '#FFDB58'],
            ['name' => '스카이 블루', 'hex_code' => '#87CEEB'],
            ['name' => '코랄', 'hex_code' => '#FF7F50'],
            ['name' => '포레스트 그린', 'hex_code' => '#228B22'],
            ['name' => '더스티 로즈', 'hex_code' => '#DCAE96'],
            ['name' => '버건디', 'hex_code' => '#800020'],
            ['name' => '틸', 'hex_code' => '#008080'],
            ['name' => '라이트 그레이', 'hex_code' => '#D3D3D3'],
            ['name' => '베이지', 'hex_code' => '#F5F5DC'],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(['name' => $color['name']], $color);
        }
    }
}

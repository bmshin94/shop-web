<?php

namespace Database\Factories;

use App\Models\Exhibition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ExhibitionFactory extends Factory
{
    protected $model = Exhibition::class;

    public function definition(): array
    {
        $title = $this->faker->randomElement([
            '24 SPRING 애슬레저 룩',
            '홈트 필수 기어 10선',
            '주말 하이킹 기획전',
            '여름 바캉스 스페셜',
            '가을 신상 프리뷰',
            '겨울 아우터 컬렉션',
            '운동 후 릴렉스 아이템',
            '프리미엄 요가 매트전',
            '액티브 우먼 베스트 셀러',
            '시즌 오프 클리어런스',
        ]) . ' ' . $this->faker->unique()->numberBetween(1, 1000);

        return [
            'title' => $title,
            'slug' => Str::slug($title . '-' . Str::random(5)),
            'status' => '진행중',
            'banner_image_url' => 'https://images.unsplash.com/photo-' . $this->faker->numberBetween(1500000000000, 1600000000000) . '?q=80&w=2070',
            'summary' => 'Active Women이 큐레이션한 스페셜 테마 콜렉션. 지금 가장 사랑받는 아이템을 만나보세요.',
            'description' => $this->faker->paragraph(3),
            'start_at' => now()->subDays(7),
            'end_at' => now()->addDays(30),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}

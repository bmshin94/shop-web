<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        $startAt = now()->addDays(fake()->numberBetween(-7, 10));
        $endAt = (clone $startAt)->addDays(fake()->numberBetween(3, 20));

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(100, 999),
            'status' => fake()->randomElement(Event::STATUSES),
            'banner_image_url' => 'https://images.unsplash.com/photo-1500000000000?w=1200&h=628&fit=crop',
            'summary' => fake()->sentence(),
            'description' => fake()->paragraphs(2, true),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}

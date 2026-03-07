<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('010-####-####'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => fake()->randomElement(Member::STATUSES),
            'last_login_at' => fake()->boolean(70) ? now()->subDays(fake()->numberBetween(0, 45)) : null,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operator>
 */
class OperatorFactory extends Factory
{
    protected $model = Operator::class;

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
        $menuKeys = array_keys(Operator::menuDefinitions());

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('010-####-####'),
            'password' => static::$password ??= Hash::make('password'),
            'status' => fake()->randomElement(Operator::STATUSES),
            'menu_permissions' => $menuKeys,
            'last_login_at' => fake()->boolean(80) ? now()->subDays(fake()->numberBetween(0, 14)) : null,
        ];
    }
}

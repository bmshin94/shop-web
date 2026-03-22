<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingAddress>
 */
class ShippingAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'address_name' => $this->faker->randomElement(['우리집', '회사', '부모님댁', '친구집']),
            'recipient_name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'zip_code' => $this->faker->postcode(),
            'address' => $this->faker->address(),
            'address_detail' => $this->faker->secondaryAddress(),
            'is_default' => false,
        ];
    }
}

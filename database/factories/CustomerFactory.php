<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
    'full_name'   => $this->faker->name(),
    'national_id' => $this->faker->unique()->numerify('##############'), // 14 digits
    'birth_date'  => $this->faker->date(),
    'email'       => $this->faker->unique()->safeEmail(),
];
    }
}

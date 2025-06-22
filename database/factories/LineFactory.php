<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Plan;
use App\Models\User;

class LineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $gcodes = ['010', '011', '012', '015'];
        $providers = [
            '010' => 'Orange',
            '011' => 'Etisalat',
            '012' => 'WE',
            '015' => 'Vodafone',
        ];

        $gcode = $this->faker->randomElement($gcodes);
        $provider = $providers[$gcode] ?? 'Vodafone';

        return [
            'gcode'             => $gcode,
            'phone_number'      => $this->faker->unique()->numerify('#########'), // 9 digits to follow gcode
            'second_phone'      => $this->faker->optional()->numerify('01#########'),
            'provider'          => $provider,
            'status'            => $this->faker->randomElement(['active', 'inactive']),
            'offer_name'        => $this->faker->word(),
            'branch_name'       => $this->faker->city(),
            'employee_name'     => $this->faker->name(),
            'line_type'         => $this->faker->randomElement(['prepaid', 'postpaid']),
            'plan_id'           => Plan::inRandomOrder()->first()?->id,
            'package'           => $this->faker->word(),
            'payment_date'      => $this->faker->date(),
            'last_invoice_date' => $this->faker->date(),
            'notes'             => $this->faker->sentence(),
            'added_by'          => User::inRandomOrder()->first()?->id,
        ];
    }
}

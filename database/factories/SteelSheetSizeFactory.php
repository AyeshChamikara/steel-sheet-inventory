<?php

namespace Database\Factories;
use App\Models\SteelSheetSize;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SteelSheetSize>
 */
class SteelSheetSizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SteelSheetSize::class;

    public function definition()
    {
        return [
            'size' => $this->faker->randomFloat(2, 0.1, 1.0), // Generate a random size (e.g., 0.47mm)
        ];
    }
}

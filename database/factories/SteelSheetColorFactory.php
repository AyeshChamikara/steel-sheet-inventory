<?php

namespace Database\Factories;
use App\Models\SteelSheetColor;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SteelSheetColor>
 */
class SteelSheetColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SteelSheetColor::class;

    public function definition()
    {
        return [
            'color' => $this->faker->safeColorName, // Generate a random color name (e.g., green, yellow)
        ];
    }
}

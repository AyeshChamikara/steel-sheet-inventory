<?php

namespace Database\Factories;
use App\Models\SteelSheetType;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SteelSheetType>
 */
class SteelSheetTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SteelSheetType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word, // Generate a unique name (e.g., Korea, China)
        ];
    }
}

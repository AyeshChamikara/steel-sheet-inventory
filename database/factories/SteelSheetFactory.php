<?php

namespace Database\Factories;
use App\Models\SteelSheet;
use App\Models\SteelSheetType;
use App\Models\SteelSheetSize;
use App\Models\SteelSheetColor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SteelSheet>
 */
class SteelSheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SteelSheet::class;

    public function definition()
    {
        return [
            'type_id' => SteelSheetType::inRandomOrder()->first()->id ?? null,
            'size_id' => SteelSheetSize::inRandomOrder()->first()->id ?? null,
            'color_id' => SteelSheetColor::inRandomOrder()->first()->id ?? null,
            'total_count' => $this->faker->numberBetween(1000, 10000), // Random inventory count
        ];
    }
}

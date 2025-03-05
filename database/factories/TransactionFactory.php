<?php

namespace Database\Factories;
use App\Models\Transaction;
use App\Models\SteelSheet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'steel_sheet_id' => SteelSheet::inRandomOrder()->first()->id ?? null,
            'quantity' => $this->faker->randomElement([100, -50, 200, -100]), // Random quantity (positive or negative)
        ];
    }
}

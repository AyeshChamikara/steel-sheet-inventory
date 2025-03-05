<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('steel_sheet_sizes', function (Blueprint $table) {
            $table->id();
            $table->decimal('size', 5, 2)->unique(); // e.g., 0.47mm, 0.40mm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steel_sheet_sizes');
    }
};

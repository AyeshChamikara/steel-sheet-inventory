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
        Schema::create('steel_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('steel_sheet_types')->onDelete('cascade');
            $table->foreignId('size_id')->constrained('steel_sheet_sizes')->onDelete('cascade');
            $table->foreignId('color_id')->constrained('steel_sheet_colors')->onDelete('cascade');
            $table->integer('total_count')->default(0); // Inventory count
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steel_sheets');
    }
};

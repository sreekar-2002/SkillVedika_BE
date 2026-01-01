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
        Schema::create('course_details_page_content_placements_and_reserve', function (Blueprint $table) {
            $table->id();

              // Placement section
            $table->json('placements_title')->nullable();
            $table->string('placements_subtitle')->nullable();
            $table->json('placement_images')->nullable(); // array of logo URLs

            // Reserve section
            $table->json('reserve_title')->nullable();
            $table->string('reserve_subtitle')->nullable();
            $table->string('reserve_box1')->nullable();
            $table->string('reserve_box2')->nullable();
            $table->string('reserve_box3')->nullable();
            $table->string('reserve_button_name')->nullable();
            $table->string('reserve_button_link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_details_page_content_placements_and_reserve');
    }
};

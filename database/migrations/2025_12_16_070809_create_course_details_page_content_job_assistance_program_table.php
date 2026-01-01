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
        Schema::create('course_details_page_content_job_assistance_program', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();     // JSON title structure
            $table->string('subtitle')->nullable();
            $table->json('points')->nullable();    // 6 points

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_details_page_content_job_assistance_program');
    }
};

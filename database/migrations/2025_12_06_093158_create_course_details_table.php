<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->index();
            $table->string('subtitle')->nullable();
            $table->json('skill')->nullable();            // store structured content / HTML array
            $table->json('trainers')->nullable();       // store structured content / HTML array
            $table->json('agenda')->nullable();
            $table->json('why_choose')->nullable();
            $table->json('who_should_join')->nullable();
            $table->json('key_outcomes')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
     // more structured meta if needed
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_details');
    }
};

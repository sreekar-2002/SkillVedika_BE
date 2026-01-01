<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('on_job_support_page_contents', function (Blueprint $table) {
            $table->id();

            // HERO SECTION
            $table->json('hero_title')->nullable();
            $table->longText('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();

            // REAL-TIME HELP
            $table->json('realtime_title')->nullable();
            $table->string('realtime_subheading')->nullable();
            $table->longText('realtime_description')->nullable();
            $table->string('realtime_subsection_title1')->nullable();
            $table->string('subsection_title1_description')->nullable();
            $table->string('realtime_subsection_title2')->nullable();
            $table->string('subsection_title2_description')->nullable();
            $table->string('realtime_image')->nullable();

            // WHO IS THIS FOR
            $table->string('who_target')->nullable();
            $table->json('who_title')->nullable();
            $table->longText('who_subtitle')->nullable();
            $table->json('who_cards')->nullable();

            // HOW WE HELP
            $table->json('how_title')->nullable();
            $table->string('how_subtitle')->nullable();
            $table->json('how_points')->nullable();
            $table->string('how_footer')->nullable();

            // PROCESS
            $table->json('process_title')->nullable();
            $table->string('process_subtitle')->nullable();
            $table->json('process_points')->nullable();

            // WHY CHOOSE
            $table->json('why_title')->nullable();
            $table->json('why_points')->nullable();
            $table->string('why_image')->nullable();

            // READY TO EMPOWER
            $table->json('ready_title')->nullable();
            $table->longText('ready_description')->nullable();
            $table->string('ready_button')->nullable();
            $table->string('ready_button_link')->nullable();
            $table->string('ready_image')->nullable();

            // DEMO SECTION
            $table->string('demo_target')->nullable();
            $table->json('demo_title')->nullable();
            $table->string('demo_subtitle')->nullable();
            $table->json('demo_points')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('on_job_support_page_contents');
    }
};

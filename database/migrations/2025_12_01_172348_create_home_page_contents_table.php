<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_page_contents', function (Blueprint $table) {
            $table->id();

            // Hero section
            $table->longText('hero_heading')->nullable();
            $table->json('hero_content')->nullable();
            $table->json('hero_popular')->nullable();
            $table->string('hero_image')->nullable();

            // Explore section
            $table->longText('explore_heading')->nullable();
            $table->longText('explore_content')->nullable();
            $table->json('explore_tabs')->nullable();

            // Key features
            $table->longText('key_features_title')->nullable();
            $table->longText('key_features_content')->nullable();
            $table->json('key_features_points')->nullable();

            // Job assistance
            $table->longText('job_assistance_heading')->nullable();
            $table->longText('job_assistance_content')->nullable();
            $table->json('job_assistance_points')->nullable();

            // Job support
            $table->longText('job_support_title')->nullable();
            $table->longText('job_support_content')->nullable();
            $table->json('job_support_payment_types')->nullable();
            $table->string('job_support_button')->nullable();
            $table->string('job_support_button_link')->nullable();

            // Blog
            $table->longText('blog_section_heading')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_contents');
    }
};

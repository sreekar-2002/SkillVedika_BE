<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Website controls
            $table->string('website_title')->nullable();
            $table->string('website_url')->nullable();
            $table->string('google_analytics')->nullable();
            $table->string('video_url')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Header Footer Images (file paths stored in storage/public)
            $table->string('header_logo')->nullable();
            $table->string('footer_logo')->nullable();

            // Banners (file paths stored in storage/public)
            $table->string('course_banner')->nullable();
            $table->string('blog_banner')->nullable();

            // Addresses
            $table->text('location_1')->nullable();
            $table->text('location_2')->nullable();

            // Footer
            $table->text('footer_description')->nullable();
            $table->string('copyright')->nullable();

            // Social URLs
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

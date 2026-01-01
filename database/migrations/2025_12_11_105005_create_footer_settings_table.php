<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();

            // Section titles + newsletter
            $table->string('get_in_touch')->nullable();
            $table->string('email_placeholder')->nullable();

            // Logo
            $table->string('logo')->nullable();

            // About section
            $table->text('about')->nullable();

            // Explore section (title + links)
            $table->string('explore')->nullable();
            $table->json('explore_links')->nullable();

            // Support section (title + links)
            $table->string('support')->nullable();
            $table->json('support_links')->nullable();

            // Contact section (title + JSON details)
            $table->string('contact')->nullable();
            $table->json('contact_details')->nullable();  // <-- Fixed duplicate

            // Social media section
            $table->string('follow_us')->nullable();
            $table->json('social_media_icons')->nullable();
            $table->json('social_links')->nullable();

            // Footer copy
            $table->string('copyright')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};

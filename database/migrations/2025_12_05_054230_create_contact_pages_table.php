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
        Schema::create('contact_page_contents', function (Blueprint $table) {
            $table->id();
             // Hero
            $table->json('hero_title')->nullable();       // JSON {part1, part2, text}
            $table->longText('hero_description')->nullable();
            $table->string('hero_button')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();

            // Contact us section
            $table->string('contactus_target')->nullable();
            $table->json('contactus_title')->nullable(); // JSON {part1, part2, text}
            $table->longText('contactus_subtitle')->nullable();

            $table->string('contacts_email_label')->nullable();
            $table->string('contacts_email_id')->nullable();
            $table->string('contacts_email_id_link')->nullable();

            $table->string('contacts_phone_label')->nullable();
            $table->string('contacts_phone_number')->nullable();
            $table->string('contacts_phone_number_link')->nullable();

            $table->string('contactus_location1_label')->nullable();
            $table->string('contactus_location1_address')->nullable();
            $table->string('contactus_location1_address_link')->nullable();

            $table->string('contactus_location2_label')->nullable();
            $table->string('contactus_location2_address')->nullable();
            $table->string('contactus_location2_address_link')->nullable();

            // Map
            $table->json('map_title')->nullable(); // JSON {part1, part2, text}
            $table->longText('map_subtitle')->nullable();
            $table->longText('map_link')->nullable(); // Google Maps embed URL for USA office
            $table->longText('map_link_india')->nullable(); // Google Maps embed URL for India office

            // Demo
            $table->string('demo_target')->nullable();
            $table->json('demo_title')->nullable();   // JSON {text, title2}
            $table->longText('demo_subtitle')->nullable();
            $table->json('demo_points')->nullable();  // JSON array of {title, description}

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_page_contents');
    }
};

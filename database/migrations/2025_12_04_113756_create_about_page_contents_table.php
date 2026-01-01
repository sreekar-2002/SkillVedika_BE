<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('about_page_contents', function (Blueprint $table) {
            $table->id();

            // HERO
            $table->string('aboutus_image')->nullable();
            $table->json('aboutus_title')->nullable();      // {part1, part2}
            $table->longText('aboutus_description')->nullable();

            // DEMO SECTION
            $table->json('demo_title')->nullable();         // {part1, part2}
            $table->json('demo_content')->nullable();       // ["text"]

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('about_page_contents');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_page_contents', function (Blueprint $table) {
            $table->id();

            // HERO SECTION
            $table->json('hero_title')->nullable();      // { part1, part2, text }
            $table->longText('hero_description')->nullable();
            $table->string('hero_image')->nullable();

            // BLOG SIDEBAR NAME
            $table->string('sidebar_name')->nullable()->default('Categories');

            // DEMO SECTION
            $table->json('demo_title')->nullable();      // { part1, part2, text }
            $table->string('demo_subtitle')->nullable();
            $table->json('demo_points')->nullable();     // [ {title, description} ]

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_page_contents');
    }
};

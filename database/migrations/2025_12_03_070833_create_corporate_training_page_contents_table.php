<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorporateTrainingPageContentsTable extends Migration
{
    public function up()
    {
        Schema::create('corporate_training_page_contents', function (Blueprint $table) {
            $table->id();

            // ---------------------
            // HERO SECTION
            // ---------------------
            $table->json('hero_title')->nullable();            // { part1, highlight }
            $table->text('hero_subheading')->nullable();       // description
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();

            // ---------------------
            // EMPOWER SECTION
            // ---------------------
            $table->json('empower_title')->nullable();         // { part1, part2 }
            $table->longText('empower_description')->nullable(); // TipTap HTML
            $table->string('empower_image')->nullable();

            // ---------------------
            // PORTFOLIO SECTION
            // ---------------------
            $table->json('portfolio_title')->nullable();
            $table->text('portfolio_subtitle')->nullable();
            $table->json('portfolio_items')->nullable();       // array of items

            // ---------------------
            // ADVANTAGE SECTION
            // ---------------------
            $table->json('advantages_title')->nullable();
            $table->text('advantages_subtitle')->nullable();
            $table->json('advantages_left_items')->nullable();   // sections 1–4
            $table->json('advantages_right_items')->nullable();  // sections 5–8

            // ---------------------
            // TALENT / HR GUIDE SECTION
            // ---------------------
            $table->json('hr_guide_title')->nullable();
            $table->text('hr_guide_subtitle')->nullable();
            $table->json('hr_guide_steps')->nullable();        // {label, desc} array

            // ---------------------
            // DEMO SECTION
            // ---------------------
            $table->json('demo_title')->nullable();            // { title }
            $table->json('demo_points')->nullable();           // [ "text" ]

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('corporate_training_page_contents');
    }
}

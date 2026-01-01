<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveDemoSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('live_demo_settings', function (Blueprint $table) {
            $table->id();

            // JSON fields
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('buttonLabel')->nullable();

            // Plain text labels
            $table->string('nameLabel')->nullable();
            $table->string('emailLabel')->nullable();
            $table->string('mobileLabel')->nullable();
            $table->string('selectCoursesLabel')->nullable();
            $table->string('termsLabel')->nullable();

            // Footer text
            $table->string('footerText')->nullable();

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('live_demo_settings');
    }
}

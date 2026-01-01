<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seos', function (Blueprint $table) {
            $table->id();

            $table->string('page_name');                     // Home Page, Blog Page etc.
            $table->string('meta_title')->nullable();        // SEO Title
            $table->longText('meta_description')->nullable();// SEO Description
            $table->longText('meta_keywords')->nullable();   // SEO Keywords

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};

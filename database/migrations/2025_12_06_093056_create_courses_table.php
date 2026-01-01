<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();       // URL to Cloudinary or local storage
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('students')->default(0);
            $table->foreignId('category_id')->nullable();
            $table->enum('status', ['trending','popular','free','none'])->default('none');
            $table->enum('mode',['active', 'inactive'])->default('active');         // JSON array of strings
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

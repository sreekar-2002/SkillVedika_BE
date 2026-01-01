<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id('blog_id');

            $table->string('blog_name');
            $table->string('url_friendly_title')->unique();

            // CATEGORY FK FROM categories TABLE
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');

            $table->string('banner_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('blog_content')->nullable();

            $table->string('published_by')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft');

            $table->enum('recent_blog', ['YES', 'NO'])
                  ->default('NO');

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            $table->json('extra')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};

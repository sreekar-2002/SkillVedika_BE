<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            if (!Schema::hasColumn('course_details', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('course_id');
                $table->index('slug'); // Add index for faster lookups
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            if (Schema::hasColumn('course_details', 'slug')) {
                $table->dropIndex(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            if (!Schema::hasColumn('course_details', 'meta_json')) {
                $table->json('meta_json')->nullable()->after('meta_keywords');
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_details', function (Blueprint $table) {
            if (Schema::hasColumn('course_details', 'meta_json')) {
                $table->dropColumn('meta_json');
            }
        });
    }
};

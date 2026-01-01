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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

             // Lead details
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Multi-course selection (array)
            $table->json('courses')->nullable();

            // Status (New, Contacted, Closed)
            $table->string('status')->default('New');

            // Message sent by the student
            $table->text('message')->nullable();

            // When admin contacted them
            $table->timestamp('contacted_on')->nullable();

            // Extra fields from website form
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

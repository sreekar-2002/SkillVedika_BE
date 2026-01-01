<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\CourseDetail;

class GenerateCourseSlugsSeeder extends Seeder
{
    /**
     * Generate slugs for existing courses that don't have slugs
     */
    public function run(): void
    {
        $courseDetails = CourseDetail::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($courseDetails as $detail) {
            $course = Course::find($detail->course_id);

            if ($course && $course->title) {
                $baseSlug = Str::slug($course->title);
                $slug = $baseSlug;
                $counter = 1;

                // Ensure uniqueness
                while (CourseDetail::where('slug', $slug)
                    ->where('id', '!=', $detail->id)
                    ->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $detail->slug = $slug;
                $detail->save();

                $this->command->info("Generated slug '{$slug}' for course: {$course->title}");
            }
        }

        $this->command->info("Slug generation completed!");
    }
}


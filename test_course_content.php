<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CoursePageContent;

// Check if record exists
$content = CoursePageContent::first();

if (!$content) {
    // Create initial record
    $content = CoursePageContent::create([
        'heading' => 'Browse Our Courses',
        'subheading' => 'Find the perfect course for your skill level',
        'sidebar_heading' => 'Course Categories',
    ]);
    echo "Created initial course page content:\n";
    echo json_encode($content, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Course page content already exists:\n";
    echo json_encode($content, JSON_PRETTY_PRINT) . "\n";
}

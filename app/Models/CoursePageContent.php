<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePageContent extends Model
{
    protected $table = "course_page_contents";

    protected $fillable = [
        'heading',
        'subheading',
        'sidebar_heading',
    ];

    // ❗ REMOVE ALL CASTS — These fields are NOT JSON
    protected $casts = [];
}

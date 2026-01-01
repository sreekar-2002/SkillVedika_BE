<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetailsPageContentJobAssistanceProgram extends Model
{
    use HasFactory;

    // migration creates table 'course_details_page_content_job_assistance_program'
    protected $table = 'course_details_page_content_job_assistance_program';

    protected $fillable = [
        'title',
        'subtitle',
        'points',
    ];

    protected $casts = [
        'title' => 'array',
        'points' => 'array',
    ];
}


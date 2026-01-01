<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseDetail extends Model
{
    protected $table = 'course_details';

    protected $fillable = [
        'course_id',
        'slug',
        'subtitle',
        'skill',
        'trainers',
        'agenda',
        'why_choose',
        'who_should_join',
        'key_outcomes',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_json',
        // new section fields
        'why_choose_title',
        'why_choose_description',
        'who_should_join_title',
        'who_should_join_description',
        'key_outcomes_title',
        'key_outcomes_description',
    ];

    protected $casts = [
        'skill' => 'array',
        'trainers' => 'array',
        'agenda' => 'array',
        'why_choose' => 'array',
        'who_should_join' => 'array',
        'key_outcomes' => 'array',
        'meta_json' => 'array',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

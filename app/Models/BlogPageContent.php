<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPageContent extends Model
{
    protected $table = 'blog_page_contents';

    protected $fillable = [
        'hero_title',
        'hero_description',
        'hero_image',

        'sidebar_name',

        'demo_title',
        'demo_subtitle',
        'demo_points',
    ];

    protected $casts = [
        'hero_title' => 'array',
        'demo_title' => 'array',
        'demo_points' => 'array',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
    'website_title',
    'website_url',
    'google_analytics',
    'video_url',
    'phone',
    'email',
    'location_1',
    'location_2',

    // Footer
    'footer_description',
    'copyright',

    // Social
    'facebook_url',
    'instagram_url',
    'linkedin_url',
    'youtube_url',

    // Images
    'header_logo',
    'footer_logo',
    'course_banner',
    'blog_banner',
];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

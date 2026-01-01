<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageContent extends Model
{
    protected $table = 'about_page_contents';

    protected $fillable = [
        'aboutus_image',
        'aboutus_title',
        'aboutus_description',
        'demo_title',
        'demo_content',
    ];

    protected $casts = [
        'aboutus_title' => 'array',
        'demo_title' => 'array',
        'demo_content' => 'array',
    ];
}

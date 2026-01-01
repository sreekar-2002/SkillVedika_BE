<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveDemoSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'nameLabel',
        'emailLabel',
        'mobileLabel',
        'selectCoursesLabel',
        'termsLabel',
        'buttonLabel',
        'footerText'
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'buttonLabel' => 'array',
    ];
}

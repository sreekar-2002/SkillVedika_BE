<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateTrainingPageContent extends Model
{
    protected $fillable = [
        'hero_title', 'hero_subheading', 'hero_button_text',
        'hero_button_link', 'hero_image',

        'empower_title', 'empower_description', 'empower_image',

        'portfolio_title', 'portfolio_subtitle', 'portfolio_items',

        'advantages_title', 'advantages_subtitle',
        'advantages_left_items', 'advantages_right_items',

        'hr_guide_title', 'hr_guide_subtitle', 'hr_guide_steps',

        'demo_title', 'demo_points',
    ];

    protected $casts = [
        'hero_title' => 'array',
        'empower_title' => 'array',
        'portfolio_title' => 'array',
        'portfolio_items' => 'array',

        'advantages_title' => 'array',
        'advantages_left_items' => 'array',
        'advantages_right_items' => 'array',

        'hr_guide_title' => 'array',
        'hr_guide_steps' => 'array',

        'demo_title' => 'array',
        'demo_points' => 'array',
    ];
}

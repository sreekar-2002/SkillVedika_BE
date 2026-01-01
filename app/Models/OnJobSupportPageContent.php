<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnJobSupportPageContent extends Model
{
    protected $table = 'on_job_support_page_contents';

    protected $fillable = [
        'hero_title','hero_description','hero_button_text','hero_button_link','hero_image',
        'realtime_title','realtime_subheading','realtime_description',
        'realtime_subsection_title1','subsection_title1_description',
        'realtime_subsection_title2','subsection_title2_description','realtime_image',
        'who_target','who_title','who_subtitle','who_cards',
        'how_title','how_subtitle','how_points','how_footer',
        'process_title','process_subtitle','process_points',
        'why_title','why_points','why_image',
        'ready_title','ready_description','ready_button','ready_button_link','ready_image',
        'demo_target','demo_title','demo_subtitle','demo_points'
    ];

    protected $casts = [
        'hero_title' => 'array',
        'realtime_title' => 'array',
        'who_title' => 'array',
        'who_cards' => 'array',
        'how_title' => 'array',
        'how_points' => 'array',
        'process_title' => 'array',
        'process_points' => 'array',
        'why_title' => 'array',
        'why_points' => 'array',
        'ready_title' => 'array',
        'demo_title' => 'array',
        'demo_points' => 'array',
    ];
}

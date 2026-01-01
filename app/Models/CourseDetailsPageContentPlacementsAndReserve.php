<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDetailsPageContentPlacementsAndReserve extends Model
{
    protected $table = 'course_details_page_content_placements_and_reserve';

    protected $fillable = [
        'placements_title',
        'placements_subtitle',
        'placement_images',
        'reserve_title',
        'reserve_subtitle',
        // DB columns are reserve_block1/2/3
        'reserve_block1',
        'reserve_block2',
        'reserve_block3',
        'reserve_button_name',
        'reserve_button_link',
    ];

    // Don't auto-cast - handle manually in controller since fields might be JSON strings
    // This allows us to parse them properly regardless of how they're stored
}


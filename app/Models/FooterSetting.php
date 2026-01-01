<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $table = 'footer_settings';

    protected $fillable = [
        'get_in_touch',
        'email_placeholder',
        'logo',
        'about',
        'explore',
        'explore_links',
        'support',
        'support_links',
        'contact',
        'contact_details',
        'follow_us',
        'social_media_icons',
        'social_links',
        'copyright',
    ];

    protected $casts = [
        'explore_links'      => 'array',
        'support_links'      => 'array',
        'contact_details'    => 'array',
        'social_media_icons' => 'array',
        'social_links'       => 'array',
    ];
}

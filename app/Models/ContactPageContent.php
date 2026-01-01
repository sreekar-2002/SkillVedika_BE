<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPageContent extends Model
{
    // Table renamed to contact_page_contents
    protected $table = 'contact_page_contents';

    protected $fillable = [
        // Hero
        'hero_title',
        'hero_description',
        'hero_button',
        'hero_button_link',
        'hero_image',

        // Contact us
        'contactus_target',
        'contactus_title',
        'contactus_subtitle',

        'contacts_email_label',
        'contacts_email_id',
        'contacts_email_id_link',

        'contacts_phone_label',
        'contacts_phone_number',
        'contacts_phone_number_link',

        'contactus_location1_label',
        'contactus_location1_address',
        'contactus_location1_address_link',

        'contactus_location2_label',
        'contactus_location2_address',
        'contactus_location2_address_link',

        // Map
        'map_title',
        'map_subtitle',
        'map_link',
        'map_link_india',

        // Demo
        'demo_target',
        'demo_title',
        'demo_subtitle',
        'demo_points',
    ];

    /**
     * Cast JSON columns to array when accessed and encode when saved.
     */
    protected $casts = [
        'hero_title' => 'array',
        'contactus_title' => 'array',
        'map_title' => 'array',
        'demo_title' => 'array',
        'demo_points' => 'array',
    ];


}

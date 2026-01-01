<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    protected $table = "home_page_contents";

    protected $fillable = [
        "hero_heading",
        "hero_content",
        "hero_popular",
        "hero_image",
        "explore_heading",
        "explore_content",
        "explore_tabs",
        "key_features_title",
        "key_features_content",
        "key_features_points",
        "job_assistance_heading",
        "job_assistance_content",
        "job_assistance_points",
        "job_support_title",
        "job_support_content",
        "job_support_payment_types",
        "job_support_button",
        "job_support_button_link",
        "blog_section_heading"
    ];

    protected $casts = [
        "hero_content" => "array",
        "hero_popular" => "array",
        "explore_tabs" => "array",
        "key_features_points" => "array",
        "job_assistance_points" => "array",
        "job_support_payment_types" => "array",
    ];
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update the main settings record
        Setting::updateOrCreate(
            ['id' => 1], // Ensure only one settings record
            [
                'website_title' => 'SkillVedika',
                'website_url' => 'https://skillvedika.com',
                'google_analytics' => '',
                'video_url' => 'https://www.youtube.com/embed/DOKVREgWKbE',
                'phone' => '+91-8790900881',
                'email' => 'support@skillvedika.com',
                'header_logo' => null,
                'footer_logo' => null,
                'course_banner' => null,
                'blog_banner' => null,
                'location_1' => '501, Manjeera Majestic Commercial, KPHB, Hyderabad, India',
                'location_2' => '25730 Lennox Hale Dr, Aldie VA 20105',
                'footer_description' => 'SkillVedika is a professional training institute offering high-quality, expert-led courses designed to help learners grow.',
                'copyright' => 'skillvedika.com | All Rights Reserved.',
                'facebook_url' => 'https://www.facebook.com/skillvedika',
                'instagram_url' => 'https://www.instagram.com/skillvedika',
                'linkedin_url' => 'https://www.linkedin.com/company/skillvedika',
                'youtube_url' => 'https://www.youtube.com/@SkillVedika',
            ]
        );
    }
}

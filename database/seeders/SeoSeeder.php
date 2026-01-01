<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seo;

class SeoSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'id' => 1,
                'page_name' => 'Home Page',
                'meta_title' => 'SkillVedika | Best IT Training Institute for SAP',
                'meta_description' => 'SkillVedika offers expert-led IT training in SAP, AWS DevOps...',
                'meta_keywords' => 'SAP training, AWS, Data Science, SkillVedika',
            ],
            [
                'id' => 2,
                'page_name' => 'Course Listing',
                'meta_title' => 'Top Online & Offline Courses to Learn Any Skill | SkillVedika',
                'meta_description' => 'Browse the best online and offline skill-based courses...',
                'meta_keywords' => 'Courses, SkillVedika',
            ],
            [
                'id' => 3,
                'page_name' => 'Blog Listing',
                'meta_title' => 'Best Skill Learning Tips & Career Guides | SkillVedika Blog',
                'meta_description' => 'SkillVedika Blog helps you grow faster...',
                'meta_keywords' => 'Blog, Skill Tips',
            ],
            [
                'id' => 5,
                'page_name' => 'Website FAQ',
                'meta_title' => 'Frequently Asked Questions (FAQs)',
                'meta_description' => 'Have questions? Explore our FAQ page...',
                'meta_keywords' => 'FAQ, Questions',
            ],
            [
                'id' => 9,
                'page_name' => 'About Us',
                'meta_title' => 'About SkillVedika | Empowering Skill-Based Learning',
                'meta_description' => 'Learn more about SkillVedika...',
                'meta_keywords' => 'About SkillVedika',
            ],
            [
                'id' => 10,
                'page_name' => 'Contact Us',
                'meta_title' => 'Contact Us | Get in Touch with SkillVedika',
                'meta_description' => 'Have questions or need help? Contact us...',
                'meta_keywords' => 'Contact, Support',
            ],
            [
                'id' => 11,
                'page_name' => 'Terms & Conditions',
                'meta_title' => 'Terms & Conditions | SkillVedika',
                'meta_description' => 'Read the Terms & Conditions of SkillVedika...',
                'meta_keywords' => 'Terms, Conditions',
            ],
            [
                'id' => 16,
                'page_name' => 'Corporate Training',
                'meta_title' => 'Corporate Training',
                'meta_description' => 'Learn about our corporate training solutions...',
                'meta_keywords' => 'Corporate Training',
            ],
            [
                'id' => 17,
                'page_name' => 'On Job Support',
                'meta_title' => 'On Job Support',
                'meta_description' => 'Get expert on-job support from SkillVedika...',
                'meta_keywords' => 'Job Support',
            ],
            [
                'id' => 18,
                'page_name' => 'Become an Instructor',
                'meta_title' => 'Become an Instructor',
                'meta_description' => 'Join as a trainer on SkillVedika...',
                'meta_keywords' => 'Instructor, Trainer',
            ],
        ];

        foreach ($rows as $row) {
            Seo::updateOrCreate(['id' => $row['id']], $row);
        }
    }
}

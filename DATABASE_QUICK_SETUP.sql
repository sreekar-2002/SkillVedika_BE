-- ============================================================
-- QUICK SETUP: Copy and paste this entire block into MySQL
-- ============================================================

USE Admins;

CREATE TABLE IF NOT EXISTS settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(255) DEFAULT '',
    admin_email VARCHAR(255) DEFAULT '',
    admin_password VARCHAR(255) DEFAULT '',
    website_title VARCHAR(255) DEFAULT '',
    website_url VARCHAR(255) DEFAULT '',
    google_analytics VARCHAR(255) DEFAULT '',
    video_url VARCHAR(255) DEFAULT '',
    phone VARCHAR(20) DEFAULT '',
    email VARCHAR(255) DEFAULT '',
    header_logo VARCHAR(255) DEFAULT NULL,
    footer_logo VARCHAR(255) DEFAULT NULL,
    course_banner VARCHAR(255) DEFAULT NULL,
    blog_banner VARCHAR(255) DEFAULT NULL,
    location_1 LONGTEXT DEFAULT NULL,
    location_2 LONGTEXT DEFAULT NULL,
    footer_description LONGTEXT DEFAULT NULL,
    copyright VARCHAR(255) DEFAULT '',
    facebook_url VARCHAR(255) DEFAULT '',
    instagram_url VARCHAR(255) DEFAULT '',
    linkedin_url VARCHAR(255) DEFAULT '',
    youtube_url VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (
    id, admin_name, admin_email, admin_password,
    website_title, website_url, google_analytics, video_url,
    phone, email,
    location_1, location_2,
    footer_description, copyright,
    facebook_url, instagram_url, linkedin_url, youtube_url
) VALUES (
    1,
    'Admin', 'admin@skillvedika.com', '$2y$12$eZ/5nEWslbDaLqmhSXbO..Y0VqVzqCnXB1VbkVdT.LdxvZGcu3D.C',
    'SkillVedika', 'https://skillvedika.com', '', 'https://www.youtube.com/embed/DOKVREgWKbE',
    '+91-8790900881', 'support@skillvedika.com',
    '501, Manjeera Majestic Commercial, KPHB, Hyderabad, India',
    '25730 Lennox Hale Dr, Aldie VA 20105',
    'SkillVedika is a professional training institute offering high-quality, expert-led courses designed to help learners grow.',
    'skillvedika.com | All Rights Reserved.',
    'https://www.facebook.com/skillvedika',
    'https://www.instagram.com/skillvedika',
    'https://www.linkedin.com/company/skillvedika',
    'https://www.youtube.com/@SkillVedika'
) ON DUPLICATE KEY UPDATE id=id;

-- Verify the table was created
SELECT COUNT(*) as 'Settings Records' FROM settings;

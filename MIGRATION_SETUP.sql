-- ======================================================
-- MIGRATION SETUP GUIDE FOR SKILLVEDIKA
-- ======================================================
-- If you cannot run: php artisan migrate --force
-- Run this SQL directly in your MySQL client
-- ======================================================

-- TABLE: settings (for website configuration)
CREATE TABLE IF NOT EXISTS settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Admin Profile (stored separately from admins table)
    admin_name VARCHAR(255) DEFAULT '',
    admin_email VARCHAR(255) DEFAULT '',
    admin_password VARCHAR(255) DEFAULT '',

    -- Website Controls
    website_title VARCHAR(255) DEFAULT '',
    website_url VARCHAR(255) DEFAULT '',
    google_analytics VARCHAR(255) DEFAULT '',
    video_url VARCHAR(255) DEFAULT '',

    -- Contact Info
    phone VARCHAR(20) DEFAULT '',
    email VARCHAR(255) DEFAULT '',

    -- Logos
    header_logo VARCHAR(255) DEFAULT NULL,
    footer_logo VARCHAR(255) DEFAULT NULL,

    -- Banners
    course_banner VARCHAR(255) DEFAULT NULL,
    blog_banner VARCHAR(255) DEFAULT NULL,

    -- Addresses
    location_1 LONGTEXT DEFAULT NULL,
    location_2 LONGTEXT DEFAULT NULL,

    -- Footer
    footer_description LONGTEXT DEFAULT NULL,
    copyright VARCHAR(255) DEFAULT '',

    -- Social URLs
    facebook_url VARCHAR(255) DEFAULT '',
    instagram_url VARCHAR(255) DEFAULT '',
    linkedin_url VARCHAR(255) DEFAULT '',
    youtube_url VARCHAR(255) DEFAULT '',

    -- Timestamps
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings record
INSERT INTO settings (
    id, admin_name, admin_email, admin_password,
    website_title, website_url, google_analytics, video_url,
    phone, email,
    location_1, location_2,
    footer_description, copyright,
    facebook_url, instagram_url, linkedin_url, youtube_url
) VALUES (
    1,
    'Admin', 'admin@skillvedika.com', '$2y$12$YOUR_HASHED_PASSWORD_HERE',
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

-- ======================================================
-- HOW TO RUN MIGRATIONS PROPERLY
-- ======================================================
-- Option 1: Via Command Line (requires PHP installed and in PATH)
--
--   cd backend
--   php artisan migrate --force
--   php artisan db:seed
--
-- Option 2: If you have Laravel installed via Composer:
--
--   composer install
--   php artisan migrate --force
--   php artisan db:seed
--
-- Option 3: If using Docker:
--
--   docker-compose up -d
--   docker-compose exec app php artisan migrate --force
--   docker-compose exec app php artisan db:seed
--
-- Option 4: Run raw SQL (copy-paste above into MySQL Workbench or phpMyAdmin)
-- ======================================================

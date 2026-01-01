-- Step 1: Create the Admin database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `Admin`;

-- Step 2: Use the Admin database
USE `Admin`;

-- Step 3: Create migrations table (Laravel uses this to track migrations)
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- You can now run migrations via: php artisan migrate --force

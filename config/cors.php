<?php

// Get allowed origins from environment variable or use defaults
$allowedOrigins = env('FRONTEND_URLS', 'http://localhost:3000,http://127.0.0.1:3000');
$origins = array_map('trim', explode(',', $allowedOrigins));

// Add default localhost origins for development
// Include common Next.js dev server ports (3000, 3001) and Vite ports (5173)
$defaultOrigins = [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'http://localhost:3001',
    'http://127.0.0.1:3001',
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'http://0.0.0.0:3000',
    // Add any port for localhost (common for admin frontend)
    'http://localhost',
    'http://127.0.0.1',
];

// Merge environment origins with defaults, remove duplicates
$allOrigins = array_unique(array_merge($origins, $defaultOrigins));
// Filter out empty values
$allOrigins = array_filter($allOrigins, function($origin) {
    return !empty($origin);
});

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Dynamic origins: supports both localhost (dev) and Vercel (production)
    // Set FRONTEND_URLS in .env: "https://your-app.vercel.app,https://www.yourdomain.com"
    'allowed_origins' => array_values($allOrigins),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Enable credentials so Sanctum cookie-based auth works from the frontend
    'supports_credentials' => true,

];


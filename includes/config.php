<?php
/**
 * Lily 1776 Coffee — Configuration
 * 
 * SECURITY: API keys are loaded from .env file (not committed to Git).
 * Create .env in the site root on your server with the actual keys.
 */
session_start();

// Load .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // skip comments
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Remove quotes if present
        $value = trim($value, '"\'');
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Roastify API
define('ROASTIFY_API_KEY', getenv('ROASTIFY_API_KEY') ?: '');
define('ROASTIFY_API_URL', 'https://api.roastify.app/v1');

// Stripe
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');
define('STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: '');
define('STRIPE_WEBHOOK_SECRET', getenv('STRIPE_WEBHOOK_SECRET') ?: '');

// Database
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'lily1776_orders');
define('DB_USER', getenv('DB_USER') ?: '');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Site
define('SITE_URL', getenv('SITE_URL') ?: 'https://www.lily1776coffee.com');
define('SITE_NAME', 'Lily 1776 Coffee');

// Shipping
define('SHIPPING_FIRST_ITEM', 450);   // $4.50 in cents
define('SHIPPING_ADDITIONAL', 150);    // $1.50 in cents
define('FREE_SHIPPING_THRESHOLD', 5000); // $50.00 in cents

// Product cache TTL (seconds)
define('PRODUCT_CACHE_TTL', 3600);

// Pricing tiers — each price is a historically meaningful year
define('PRICE_TIER_1776', 1776);
define('PRICE_TIER_1976', 1976);
define('PRICE_TIER_2026', 2026);

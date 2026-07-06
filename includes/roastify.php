<?php
/**
 * Roastify API Helper Functions
 */

require_once __DIR__ . '/config.php';

/**
 * Fetch all merchant products from Roastify API
 * Returns array of products with id, title, description, images, variants
 */
function roastify_get_products(): array {
    $ch = curl_init(ROASTIFY_API_URL . '/products?pageSize=50');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['x-api-key: ' . ROASTIFY_API_KEY],
        CURLOPT_TIMEOUT => 10,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        return [];
    }

    $data = json_decode($response, true);
    return $data['products'] ?? [];
}

/**
 * Get cached products (file-based cache for simplicity on shared hosting)
 */
function get_products_cached(): array {
    $cacheFile = __DIR__ . '/../cache/products.json';
    $cacheDir = dirname($cacheFile);
    
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    // Check if cache is fresh
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < PRODUCT_CACHE_TTL) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if ($cached) return $cached;
    }

    // Fetch fresh
    $products = roastify_get_products();
    if (!empty($products)) {
        file_put_contents($cacheFile, json_encode($products));
    }

    return $products;
}

/**
 * Map a Roastify product to our display format
 */
function map_product(array $raw): array {
    // Determine price tier based on product type/title
    $title = strtolower($raw['title'] ?? '');
    $type = strtolower($raw['productType'] ?? '');
    
    if (strpos($title, 'matcha') !== false) {
        $priceCents = PRICE_TIER_2026;
    } elseif (strpos($title, 'mushroom') !== false || strpos($title, 'harbor toss') !== false) {
        $priceCents = PRICE_TIER_1976;
    } else {
        $priceCents = PRICE_TIER_1776;
    }

    // Get the best mockup image (first in images array)
    $image = '';
    if (!empty($raw['images'])) {
        $image = $raw['images'][0]['url'] ?? '';
    }

    // Get variants (Whole Bean / Ground)
    $variants = [];
    foreach ($raw['variants'] ?? [] as $v) {
        $variants[] = [
            'id' => $v['id'] ?? '',
            'sku' => $v['sku'] ?? '',
            'title' => $v['title'] ?? 'Default',
        ];
    }

    return [
        'id' => $raw['id'] ?? '',
        'title' => $raw['title'] ?? '',
        'description' => $raw['description'] ?? '',
        'productType' => $raw['productType'] ?? '',
        'image' => $image,
        'images' => $raw['images'] ?? [],
        'variants' => $variants,
        'priceCents' => $priceCents,
        'priceDisplay' => '$' . number_format($priceCents / 100, 2),
    ];
}

/**
 * Get all products mapped and ready for display
 */
function get_all_products(): array {
    $raw = get_products_cached();
    return array_map('map_product', $raw);
}

/**
 * Get a single product by ID
 */
function get_product_by_id(string $id): ?array {
    $products = get_all_products();
    foreach ($products as $p) {
        if ((string)$p['id'] === (string)$id) {
            return $p;
        }
    }
    return null;
}

/**
 * Submit an order to Roastify
 */
function roastify_create_order(array $customer, array $lineItems): array {
    $payload = json_encode([
        'customer' => [
            'firstName' => $customer['first_name'],
            'lastName' => $customer['last_name'],
            'email' => $customer['email'],
        ],
        'shippingAddress' => [
            'line1' => $customer['address1'],
            'line2' => $customer['address2'] ?? '',
            'city' => $customer['city'],
            'state' => $customer['state'],
            'zip' => $customer['zip'],
            'country' => 'US',
        ],
        'lineItems' => $lineItems,
    ]);

    $ch = curl_init(ROASTIFY_API_URL . '/orders');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'x-api-key: ' . ROASTIFY_API_KEY,
            'Content-Type: application/json',
            'Idempotency-Key: ' . uniqid('lily1776_order_', true),
        ],
        CURLOPT_TIMEOUT => 30,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'success' => ($httpCode >= 200 && $httpCode < 300),
        'code' => $httpCode,
        'body' => json_decode($response, true),
    ];
}

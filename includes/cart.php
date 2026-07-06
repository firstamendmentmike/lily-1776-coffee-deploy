<?php
/**
 * Cart Management — PHP Sessions
 */

function cart_init(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

function cart_add(string $productId, string $variantId, string $name, string $grind, int $priceCents, string $image, int $qty = 1): void {
    cart_init();
    $key = $productId . '_' . $variantId;
    
    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] += $qty;
    } else {
        $_SESSION['cart'][$key] = [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'name' => $name,
            'grind' => $grind,
            'price_cents' => $priceCents,
            'image' => $image,
            'quantity' => $qty,
        ];
    }
}

function cart_remove(string $key): void {
    cart_init();
    unset($_SESSION['cart'][$key]);
}

function cart_update_qty(string $key, int $qty): void {
    cart_init();
    if ($qty <= 0) {
        cart_remove($key);
    } elseif (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['quantity'] = $qty;
    }
}

function cart_get(): array {
    cart_init();
    return $_SESSION['cart'] ?? [];
}

function cart_count(): int {
    cart_init();
    $count = 0;
    foreach ($_SESSION['cart'] ?? [] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

function cart_subtotal(): int {
    cart_init();
    $total = 0;
    foreach ($_SESSION['cart'] ?? [] as $item) {
        $total += $item['price_cents'] * $item['quantity'];
    }
    return $total;
}

function cart_shipping(): int {
    cart_init();
    $count = cart_count();
    if ($count === 0) return 0;
    
    $subtotal = cart_subtotal();
    if ($subtotal >= FREE_SHIPPING_THRESHOLD) return 0;
    
    return SHIPPING_FIRST_ITEM + (max(0, $count - 1) * SHIPPING_ADDITIONAL);
}

function cart_total(): int {
    return cart_subtotal() + cart_shipping();
}

function cart_clear(): void {
    cart_init();
    $_SESSION['cart'] = [];
}

function cart_to_stripe_line_items(): array {
    $items = [];
    foreach (cart_get() as $item) {
        $items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $item['name'] . ' (' . $item['grind'] . ')',
                    'images' => [$item['image']],
                ],
                'unit_amount' => $item['price_cents'],
            ],
            'quantity' => $item['quantity'],
        ];
    }
    
    // Add shipping as a line item
    $shipping = cart_shipping();
    if ($shipping > 0) {
        $items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Shipping',
                ],
                'unit_amount' => $shipping,
            ],
            'quantity' => 1,
        ];
    }
    
    return $items;
}

function cart_to_roastify_line_items(): array {
    $items = [];
    foreach (cart_get() as $item) {
        $items[] = [
            'variantId' => $item['variant_id'],
            'quantity' => $item['quantity'],
        ];
    }
    return $items;
}

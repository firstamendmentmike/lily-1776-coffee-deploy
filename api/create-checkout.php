<?php
/**
 * Create Stripe Checkout Session
 * 
 * Called when customer clicks "Proceed to Checkout" from cart page.
 * Redirects customer to Stripe-hosted checkout page.
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/cart.php';
require_once __DIR__ . '/../includes/db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cart.php');
    exit;
}

$cartItems = cart_get();
if (empty($cartItems)) {
    header('Location: /cart.php');
    exit;
}

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// Build Stripe line items
$lineItems = cart_to_stripe_line_items();

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'shipping_address_collection' => [
            'allowed_countries' => ['US'],
        ],
        'success_url' => SITE_URL . '/confirmation.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => SITE_URL . '/cart.php',
        'metadata' => [
            'source' => 'lily1776coffee',
        ],
    ]);

    // Save cart data to DB keyed by Stripe session ID (for webhook to use later)
    save_pending_order($session->id, [
        'items' => cart_to_roastify_line_items(),
        'cart_display' => $cartItems,
    ]);

    // Redirect to Stripe Checkout
    header('Location: ' . $session->url);
    exit;

} catch (\Exception $e) {
    // Log error and redirect back to cart with error
    error_log('Stripe checkout error: ' . $e->getMessage());
    header('Location: /cart.php?error=checkout_failed');
    exit;
}

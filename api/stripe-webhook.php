<?php
/**
 * Stripe Webhook Handler
 * 
 * Receives payment confirmation from Stripe, then submits the order to Roastify API.
 * 
 * Endpoint: https://www.lily1776coffee.com/api/stripe-webhook.php
 * Event: checkout.session.completed
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/roastify.php';

// Read raw POST body
$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

// Verify webhook signature
try {
    $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, STRIPE_WEBHOOK_SECRET);
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit('Invalid signature');
}

// Handle the event
if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;
    $stripeSessionId = $session->id;

    // Get the pending order from our database
    $order = get_pending_order($stripeSessionId);
    if (!$order) {
        error_log("Webhook: No pending order found for session $stripeSessionId");
        http_response_code(200);
        exit;
    }

    // Extract customer/shipping info from Stripe session
    $shipping = $session->shipping_details ?? null;
    $customerDetails = $session->customer_details ?? null;

    if (!$shipping || !$customerDetails) {
        update_order_status($stripeSessionId, 'failed', ['error' => 'Missing shipping/customer details']);
        http_response_code(200);
        exit;
    }

    // Parse name
    $nameParts = explode(' ', $shipping->name ?? '', 2);
    $firstName = $nameParts[0] ?? '';
    $lastName = $nameParts[1] ?? '';

    // Build customer data for Roastify
    $customer = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $customerDetails->email ?? '',
        'address1' => $shipping->address->line1 ?? '',
        'address2' => $shipping->address->line2 ?? '',
        'city' => $shipping->address->city ?? '',
        'state' => $shipping->address->state ?? '',
        'zip' => $shipping->address->postal_code ?? '',
    ];

    // Submit order to Roastify
    $lineItems = $order['cart_data']['items'] ?? [];
    $result = roastify_create_order($customer, $lineItems);

    if ($result['success']) {
        update_order_status($stripeSessionId, 'submitted', $result['body']);
    } else {
        update_order_status($stripeSessionId, 'failed', $result);
        error_log("Roastify order failed for session $stripeSessionId: " . json_encode($result));
    }
}

http_response_code(200);
echo 'OK';

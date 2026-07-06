<?php
/**
 * Lily 1776 Coffee — Order Confirmation
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/cart.php';

// Clear the cart after successful checkout
cart_clear();

$pageTitle = 'Order Confirmed — Lily 1776 Coffee';
include __DIR__ . '/templates/header.php';
?>

    <section class="confirmation-section">
        <div class="section-inner confirmation-inner">
            <div class="confirmation-card">
                <div class="confirmation-icon">&#10003;</div>
                <h1>Order Confirmed!</h1>
                <p class="confirmation-subtitle">Your coffee is being roasted fresh.</p>
                <p>Thank you for your order. Your coffee will be roasted to order and shipped directly to you. You'll receive a confirmation email with tracking information once your order ships.</p>
                
                <div class="confirmation-details">
                    <h3>What happens next:</h3>
                    <ol>
                        <li>Your coffee is roasted fresh to order</li>
                        <li>Quality checked and packaged</li>
                        <li>Shipped directly to your door</li>
                        <li>You'll receive tracking via email</li>
                    </ol>
                </div>

                <div class="confirmation-cta">
                    <a href="/catalog.php" class="btn btn-navy">Continue Shopping</a>
                    <a href="/" class="btn btn-outline">Back to Home</a>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/templates/footer.php'; ?>

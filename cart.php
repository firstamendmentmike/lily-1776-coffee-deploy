<?php
/**
 * Lily 1776 Coffee — Cart Page
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/cart.php';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        cart_remove($_POST['remove']);
        header('Location: /cart.php');
        exit;
    }
    if (isset($_POST['update_qty'])) {
        cart_update_qty($_POST['item_key'], intval($_POST['new_qty']));
        header('Location: /cart.php');
        exit;
    }
    if (isset($_POST['clear_cart'])) {
        cart_clear();
        header('Location: /cart.php');
        exit;
    }
}

$cartItems = cart_get();
$subtotal = cart_subtotal();
$shipping = cart_shipping();
$total = cart_total();

$pageTitle = 'Your Cart — Lily 1776 Coffee';
include __DIR__ . '/templates/header.php';
?>

    <section class="cart-section">
        <div class="section-inner">
            <h1>Your Cart</h1>

            <?php if (empty($cartItems)): ?>
                <div class="empty-cart">
                    <p>Your cart is empty.</p>
                    <a href="/catalog.php" class="btn btn-navy">Shop Now &rarr;</a>
                </div>
            <?php else: ?>
                <div class="cart-layout">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <?php foreach ($cartItems as $key => $item): ?>
                        <div class="cart-item">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-img">
                            <div class="cart-item-info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="cart-item-grind"><?= htmlspecialchars($item['grind']) ?></p>
                                <p class="cart-item-price">$<?= number_format($item['price_cents'] / 100, 2) ?></p>
                            </div>
                            <div class="cart-item-actions">
                                <form method="POST" class="qty-form">
                                    <input type="hidden" name="update_qty" value="1">
                                    <input type="hidden" name="item_key" value="<?= htmlspecialchars($key) ?>">
                                    <div class="qty-selector">
                                        <button type="submit" name="new_qty" value="<?= $item['quantity'] - 1 ?>" class="qty-btn">-</button>
                                        <span class="qty-display"><?= $item['quantity'] ?></span>
                                        <button type="submit" name="new_qty" value="<?= $item['quantity'] + 1 ?>" class="qty-btn">+</button>
                                    </div>
                                </form>
                                <form method="POST">
                                    <button type="submit" name="remove" value="<?= htmlspecialchars($key) ?>" class="remove-btn">Remove</button>
                                </form>
                            </div>
                            <div class="cart-item-total">
                                $<?= number_format(($item['price_cents'] * $item['quantity']) / 100, 2) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$<?= number_format($subtotal / 100, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span><?= $shipping === 0 ? 'FREE' : '$' . number_format($shipping / 100, 2) ?></span>
                        </div>
                        <?php if ($subtotal < FREE_SHIPPING_THRESHOLD && $subtotal > 0): ?>
                            <p class="shipping-note">Add $<?= number_format((FREE_SHIPPING_THRESHOLD - $subtotal) / 100, 2) ?> more for free shipping!</p>
                        <?php endif; ?>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span>$<?= number_format($total / 100, 2) ?></span>
                        </div>
                        <form action="/api/create-checkout.php" method="POST">
                            <button type="submit" class="btn btn-navy btn-lg btn-full">Proceed to Checkout</button>
                        </form>
                        <a href="/catalog.php" class="continue-shopping">Continue Shopping</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include __DIR__ . '/templates/footer.php'; ?>

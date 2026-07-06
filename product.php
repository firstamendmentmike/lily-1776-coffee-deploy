<?php
/**
 * Lily 1776 Coffee — Product Detail Page
 */
require_once __DIR__ . '/includes/roastify.php';
require_once __DIR__ . '/includes/cart.php';

$productId = $_GET['id'] ?? '';
if (!$productId) {
    header('Location: /catalog.php');
    exit;
}

$product = get_product_by_id($productId);
if (!$product) {
    header('Location: /catalog.php');
    exit;
}

// Handle add-to-cart POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $variantId = $_POST['variant_id'] ?? '';
    $grind = $_POST['grind'] ?? 'Default';
    $qty = max(1, intval($_POST['quantity'] ?? 1));
    
    if ($variantId) {
        cart_add(
            $product['id'],
            $variantId,
            $product['title'],
            $grind,
            $product['priceCents'],
            $product['image'],
            $qty
        );
        header('Location: /cart.php');
        exit;
    }
}

$pageTitle = htmlspecialchars($product['title']) . ' — Lily 1776 Coffee';

include __DIR__ . '/templates/header.php';
?>

    <section class="product-detail">
        <div class="section-inner">
            <div class="product-detail-grid">
                <!-- Product Image -->
                <div class="product-detail-image">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                </div>

                <!-- Product Info -->
                <div class="product-detail-info">
                    <span class="product-type-label"><?= htmlspecialchars($product['productType']) ?></span>
                    <h1><?= htmlspecialchars($product['title']) ?></h1>
                    <p class="product-price-large"><?= $product['priceDisplay'] ?></p>
                    
                    <?php if ($product['description']): ?>
                        <p class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <?php endif; ?>

                    <!-- Add to Cart Form -->
                    <form method="POST" class="add-to-cart-form">
                        <input type="hidden" name="add_to_cart" value="1">
                        
                        <!-- Variant Selector (Whole Bean / Ground) -->
                        <?php if (count($product['variants']) > 1): ?>
                            <div class="form-group">
                                <label for="variant_id">Grind:</label>
                                <select name="variant_id" id="variant_id" class="form-select" required>
                                    <?php foreach ($product['variants'] as $v): ?>
                                        <option value="<?= htmlspecialchars($v['id']) ?>"><?= htmlspecialchars($v['title']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="grind" id="grindHidden" value="<?= htmlspecialchars($product['variants'][0]['title'] ?? 'Default') ?>">
                            </div>
                        <?php elseif (count($product['variants']) === 1): ?>
                            <input type="hidden" name="variant_id" value="<?= htmlspecialchars($product['variants'][0]['id']) ?>">
                            <input type="hidden" name="grind" value="<?= htmlspecialchars($product['variants'][0]['title'] ?? 'Default') ?>">
                        <?php else: ?>
                            <input type="hidden" name="variant_id" value="<?= htmlspecialchars($product['id']) ?>">
                            <input type="hidden" name="grind" value="Default">
                        <?php endif; ?>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <div class="qty-selector">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">-</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="qty-input">
                                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-navy btn-lg btn-full">Add to Cart</button>
                    </form>

                    <!-- Shipping Info -->
                    <div class="product-shipping-info">
                        <p>&#10003; Free shipping on orders over $50</p>
                        <p>&#10003; Third-party lab tested for purity</p>
                        <p>&#10003; Roasted fresh to order</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function changeQty(delta) {
        const input = document.getElementById('quantity');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > 10) val = 10;
        input.value = val;
    }
    // Sync grind hidden field with select
    const variantSelect = document.getElementById('variant_id');
    if (variantSelect) {
        variantSelect.addEventListener('change', function() {
            document.getElementById('grindHidden').value = this.options[this.selectedIndex].text;
        });
    }
    </script>

<?php include __DIR__ . '/templates/footer.php'; ?>

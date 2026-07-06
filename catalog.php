<?php
/**
 * Lily 1776 Coffee — Product Catalog
 */
require_once __DIR__ . '/includes/roastify.php';

$pageTitle = 'Shop All — Lily 1776 Coffee';
$products = get_all_products();

// Category filter
$cat = $_GET['cat'] ?? '';
$catLabels = [
    'single-origin' => 'Single Origins',
    'blends' => 'Blends',
    'pods' => 'Pods',
    'instant' => 'Instant',
    'functional' => 'Functional',
    'matcha' => 'Matcha',
];

if ($cat && isset($catLabels[$cat])) {
    $pageTitle = $catLabels[$cat] . ' — Lily 1776 Coffee';
    $products = array_filter($products, function($p) use ($cat) {
        $type = strtolower($p['productType']);
        switch ($cat) {
            case 'single-origin': return strpos($type, 'single') !== false;
            case 'blends': return strpos($type, 'blend') !== false || strpos($type, '12oz box') !== false;
            case 'pods': return strpos($type, 'pod') !== false;
            case 'instant': return strpos($type, 'instant') !== false || strpos($type, 'tube') !== false;
            case 'functional': return strpos($type, 'mushroom') !== false || strpos(strtolower($p['title']), 'mushroom') !== false;
            case 'matcha': return strpos(strtolower($p['title']), 'matcha') !== false;
            default: return true;
        }
    });
}

include __DIR__ . '/templates/header.php';
?>

    <!-- Catalog Header -->
    <section class="catalog-header">
        <div class="section-inner">
            <h1><?= htmlspecialchars($catLabels[$cat] ?? 'Shop All') ?></h1>
            <p class="catalog-subtitle">Every price is a year. Every bag is lab-tested.</p>
        </div>
    </section>

    <!-- Category Filters -->
    <section class="catalog-filters">
        <div class="section-inner">
            <div class="filter-bar">
                <a href="/catalog.php" class="filter-btn <?= !$cat ? 'active' : '' ?>">All</a>
                <?php foreach ($catLabels as $key => $label): ?>
                    <a href="/catalog.php?cat=<?= $key ?>" class="filter-btn <?= $cat === $key ? 'active' : '' ?>"><?= $label ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <section class="catalog-grid-section">
        <div class="section-inner">
            <?php if (empty($products)): ?>
                <p class="empty-state">No products found in this category.</p>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $p): ?>
                    <a href="/product.php?id=<?= urlencode($p['id']) ?>" class="product-card">
                        <div class="product-card-img">
                            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                            <span class="badge-lab">&#9745; Lab Tested</span>
                        </div>
                        <div class="product-card-body">
                            <h3><?= htmlspecialchars($p['title']) ?></h3>
                            <span class="product-type"><?= htmlspecialchars($p['productType']) ?></span>
                            <div class="product-card-footer">
                                <span class="product-price"><?= $p['priceDisplay'] ?></span>
                                <span class="btn-sm btn-navy">View</span>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php include __DIR__ . '/templates/footer.php'; ?>

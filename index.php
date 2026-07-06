<?php
/**
 * Lily 1776 Coffee — Homepage
 */
require_once __DIR__ . '/includes/roastify.php';

$pageTitle = 'Lily 1776 Coffee — American-Made Specialty Coffee';
$products = get_all_products();

// Featured products (first 4)
$featured = array_slice($products, 0, 4);

include __DIR__ . '/templates/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg">
            <img src="/img/hero-flag-bg.jpg" alt="" class="hero-bg-img">
        </div>
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-eyebrow">&mdash; American-Made Specialty Coffee</span>
                <h1 class="hero-title">Lily 1776</h1>
                <p class="hero-subtitle">Coffee Company</p>
                <p class="hero-desc">Small-batch single origins, signature blends, and functional brews — roasted on American soil. Third-party lab tested. One honest price: <strong>$17.76</strong></p>
                <div class="hero-cta">
                    <a href="/catalog.php" class="btn btn-navy">Shop the Catalog &rarr;</a>
                    <a href="/story.php" class="btn btn-oxblood">Our Story</a>
                </div>
            </div>
            <div class="hero-seal">
                <img src="/img/wax-seal.png" alt="Lily 1776 Wax Seal" class="seal-img">
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-section">
        <div class="section-inner">
            <h2 class="section-title">Featured Coffees</h2>
            <div class="product-grid">
                <?php foreach ($featured as $p): ?>
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
            <div class="section-cta">
                <a href="/catalog.php" class="btn btn-navy">View All Products &rarr;</a>
            </div>
        </div>
    </section>

    <!-- Brand Promise -->
    <section class="promise-section">
        <div class="section-inner">
            <div class="promise-grid">
                <div class="promise-card">
                    <h4>American-Made</h4>
                    <p>Roasted in small batches on US soil.</p>
                </div>
                <div class="promise-card">
                    <h4>Traceable Origins</h4>
                    <p>High-altitude specialty lots, by region.</p>
                </div>
                <div class="promise-card">
                    <h4>Independently Tested</h4>
                    <p>FoodChain ID verified for purity.</p>
                </div>
                <div class="promise-card">
                    <h4>Fairly Priced</h4>
                    <p>$17.76 / $19.76 / $20.26 — every price is a year.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet Lily -->
    <section class="lily-section">
        <div class="section-inner lily-inner">
            <div class="lily-text">
                <h2>Meet Lily</h2>
                <p>The namesake, the mascot, the reason we do what we do. Lily is a Jack Russell Terrier with more energy than a double shot of espresso and more loyalty than any brand ambassador money could buy.</p>
                <p>She doesn't care about profit margins or market positioning. She cares about walks, treats, and making sure every bag of coffee that leaves this house is worth a damn.</p>
            </div>
            <div class="lily-photo">
                <img src="/img/lily-photo.webp" alt="Lily the Jack Russell Terrier">
            </div>
        </div>
    </section>

<?php include __DIR__ . '/templates/footer.php'; ?>

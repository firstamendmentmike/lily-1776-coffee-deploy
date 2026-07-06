<?php
/**
 * Lily 1776 Coffee — Our Story
 */
$pageTitle = 'Our Story — Lily 1776 Coffee';
include __DIR__ . '/templates/header.php';
?>

    <!-- Story Hero -->
    <section class="story-hero">
        <div class="section-inner">
            <span class="hero-eyebrow">&#9733; Our Story</span>
            <h1>Coffee with a backbone.</h1>
            <p>Lily 1776 was built on a simple idea: make honest, exceptional coffee on American soil, test it openly, and price it fairly — so good coffee belongs to everyone, not just the few.</p>
        </div>
    </section>

    <!-- Story Content -->
    <section class="story-content">
        <div class="section-inner story-grid">
            <div class="story-text">
                <p>We source traceable, high-altitude lots from celebrated growing regions — the Sidama hills of Ethiopia, the Amazonas slopes of Peru, the highlands of Guatemala and Colombia — and roast them in small batches here at home.</p>
                <p>Every roasted lot is sent to an independent laboratory for screening. Heavy metals, mycotoxins, aflatoxins, yeast and mold — the things you can't taste but deserve to know about. We publish what we find because transparency is not a marketing line for us; it is the product.</p>
                <p>And then there's the price. Every tier is a year that means something: <strong>$17.76</strong> (1776), <strong>$19.76</strong> (1976), and <strong>$20.26</strong> (2026). Quality coffee shouldn't require a premium-pricing decoder ring.</p>
            </div>
            <div class="story-image">
                <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663484888774/lily-photo.jpg" alt="Lily the Jack Russell Terrier with coffee bags">
            </div>
        </div>
    </section>

    <!-- Promise Cards -->
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

<?php include __DIR__ . '/templates/footer.php'; ?>

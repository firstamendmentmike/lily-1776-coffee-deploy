<?php require_once __DIR__ . '/../includes/cart.php'; cart_init(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Lily 1776 Coffee — American-Made Specialty Coffee') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDesc ?? 'Small-batch single origins, signature blends, and functional brews — roasted on American soil. Third-party lab tested. One honest price: $17.76.') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/img/favicon.ico">
</head>
<body>
    <!-- Promo Banner -->
    <div class="promo-banner" id="promoBanner">
        <p>&#9733; Independence Day Special — Everything in the store: <strong>$17.76</strong> — Celebrate America's 250th &#9733;</p>
        <button onclick="document.getElementById('promoBanner').style.display='none'" aria-label="Dismiss">&times;</button>
    </div>

    <!-- Header -->
    <header class="site-header" id="siteHeader">
        <div class="header-inner">
            <a href="/" class="logo">
                <img src="https://files.manuscdn.com/user_upload_by_module/session_file/310519663484888774/dmWneGzDIdzWHYVh.png" alt="Lily 1776 Seal" class="logo-seal">
                <span class="logo-text">Lily 1776<small>Coffee Co.</small></span>
            </a>

            <nav class="main-nav" id="mainNav">
                <a href="/catalog.php">Shop All</a>
                <a href="/catalog.php?cat=single-origin">Single Origins</a>
                <a href="/catalog.php?cat=blends">Blends</a>
                <a href="/catalog.php?cat=pods">Pods</a>
                <a href="/catalog.php?cat=functional">Functional</a>
                <a href="/story.php">Our Story</a>
            </nav>

            <div class="header-actions">
                <a href="/cart.php" class="cart-link" aria-label="Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    <?php if (cart_count() > 0): ?>
                        <span class="cart-badge"><?= cart_count() ?></span>
                    <?php endif; ?>
                </a>
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </header>

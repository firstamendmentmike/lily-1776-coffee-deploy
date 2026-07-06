/**
 * Lily 1776 Coffee — Main JS
 */

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById('mobileMenuBtn');
    const nav = document.getElementById('mainNav');
    
    if (menuBtn && nav) {
        menuBtn.addEventListener('click', function() {
            nav.classList.toggle('open');
            menuBtn.classList.toggle('active');
        });
    }

    // Header scroll effect
    const header = document.getElementById('siteHeader');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 60) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
});

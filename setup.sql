-- Lily 1776 Coffee — Database Setup
-- Run this in phpMyAdmin after creating the database

CREATE TABLE IF NOT EXISTS pending_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stripe_session_id VARCHAR(255) UNIQUE NOT NULL,
    cart_data JSON NOT NULL,
    status ENUM('pending', 'paid', 'submitted', 'failed') DEFAULT 'pending',
    roastify_response JSON DEFAULT NULL,
    customer_email VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

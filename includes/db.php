<?php
/**
 * Database Connection Helper
 */

require_once __DIR__ . '/config.php';

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

function save_pending_order(string $stripeSessionId, array $cartData): void {
    $db = get_db();
    $stmt = $db->prepare(
        "INSERT INTO pending_orders (stripe_session_id, cart_data, status) 
         VALUES (?, ?, 'pending')
         ON DUPLICATE KEY UPDATE cart_data = VALUES(cart_data)"
    );
    $stmt->execute([$stripeSessionId, json_encode($cartData)]);
}

function get_pending_order(string $stripeSessionId): ?array {
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM pending_orders WHERE stripe_session_id = ?");
    $stmt->execute([$stripeSessionId]);
    $row = $stmt->fetch();
    if ($row) {
        $row['cart_data'] = json_decode($row['cart_data'], true);
        $row['roastify_response'] = json_decode($row['roastify_response'] ?? 'null', true);
    }
    return $row ?: null;
}

function update_order_status(string $stripeSessionId, string $status, ?array $roastifyResponse = null): void {
    $db = get_db();
    $stmt = $db->prepare(
        "UPDATE pending_orders SET status = ?, roastify_response = ? WHERE stripe_session_id = ?"
    );
    $stmt->execute([$status, json_encode($roastifyResponse), $stripeSessionId]);
}

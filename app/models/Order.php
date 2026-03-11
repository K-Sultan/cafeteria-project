<?php

require_once __DIR__ . "/../../utility/database.php";

class Order {
    public static function create($userId, $roomNo, $notes, $totalAmount, $items) {
        $conn = Database::getConnection();
        
        try {
            $conn->beginTransaction();
            
            // 1. Insert into orders table
            $stmt = $conn->prepare("INSERT INTO orders (user_id, room_no, notes, status, total_amount) VALUES (?, ?, ?, 'processing', ?)");
            $stmt->execute([$userId, $roomNo, $notes, $totalAmount]);
            
            $orderId = $conn->lastInsertId();
            
            // 2. Insert into order_items table
            $stmtItems = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
            
            foreach ($items as $item) {
                $stmtItems->execute([
                    $orderId, 
                    $item['id'], 
                    $item['quantity'], 
                    $item['price']
                ]);
            }
            
            $conn->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        }
    }

    public static function getLatestOrderForUser($userId) {
        $conn = Database::getConnection();
        
        // Find the most recent order ID that wasn't cancelled
        $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status != 'cancelled' ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            return []; // No previous orders
        }
        
        $orderId = $order['id'];
        
        // Fetch the items for that order, joining with products to get details
        $stmtItems = $conn->prepare("
            SELECT p.id, p.name, p.price, p.image, oi.quantity 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmtItems->execute([$orderId]);
        
        return $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }
}

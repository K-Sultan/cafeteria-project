<?php

require_once __DIR__ . "/../../utility/database.php";

class Order
{
    public static function create($userId, $roomNo, $notes, $totalAmount, $items)
    {
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

    public static function getLatestOrderForUser($userId)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("SELECT id FROM orders WHERE user_id = ? AND status != 'cancelled' ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return [];
        }

        $orderId = $order['id'];

        $stmtItems = $conn->prepare("
            SELECT p.id, p.name, p.price, p.image, oi.quantity 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmtItems->execute([$orderId]);

        return $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOrdersByUserId($userId)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT id, room_no, notes, status, total_amount, created_at
            FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOrderById($orderId)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT id, user_id, room_no, notes, status, total_amount, created_at
            FROM orders
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOrderItems($orderId)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT 
                p.name,
                p.image,
                oi.quantity,
                oi.unit_price,
                (oi.quantity * oi.unit_price) AS subtotal
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
        ");

        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cancelOrder($orderId, $userId)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            UPDATE orders
            SET status = 'cancelled'
            WHERE id = ? AND user_id = ? AND status = 'pending'
        ");

        return $stmt->execute([$orderId, $userId]);
    }

    public static function getRoomsNumbers() {
        $conn = Database::getConnection();
    
        $stmt = $conn->query("SELECT DISTINCT room_no FROM orders ORDER BY room_no ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getOrdersWithItems($filters = []) {
        $conn = Database::getConnection();
    
        $query = "
            SELECT 
                o.id, o.user_id, o.room_no, o.notes, o.status, o.total_amount, o.created_at,
                u.name AS user_name,
                u.extension AS user_extension,
                p.id AS product_id,
                p.name AS product_name,
                p.image AS product_image,
                oi.quantity,
                oi.unit_price,
                (oi.quantity * oi.unit_price) AS subtotal
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
        ";
    
        $conditions = [];
        $params = [];
    
        if (!empty($filters['date'])) {
            $conditions[] = "DATE(o.created_at) = ?";
            $params[] = $filters['date'];
        }
    
        if (!empty($filters['status'])) {
            $conditions[] = "o.status = ?";
            $params[] = $filters['status'];
        }
    
        if (!empty($filters['room_no'])) {
            $conditions[] = "o.room_no = ?";
            $params[] = $filters['room_no'];
        }
    
        if (!empty($filters['user_id'])) {
            $conditions[] = "o.user_id = ?";
            $params[] = $filters['user_id'];
        }
    
        if ($conditions) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
    
        $query .= " ORDER BY o.created_at DESC, oi.id ASC";
    
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $orders = [];

        foreach ($rows as $row) {
            $orderId = $row['id'];

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'room_no' => $row['room_no'],
                    'notes' => $row['notes'],
                    'status' => $row['status'],
                    'total_amount' => $row['total_amount'],
                    'created_at' => $row['created_at'],
                    'user_name' => $row['user_name'],
                    'user_extension' => $row['user_extension'],
                    'items' => [],
                ];
            }

            $orders[$orderId]['items'][] = [
                'id' => $row['product_id'],
                'name' => $row['product_name'],
                'image' => $row['product_image'],
                'quantity' => $row['quantity'],
                'unit_price' => $row['unit_price'],
                'subtotal' => $row['subtotal'],
            ];
        }

        return array_values($orders);
    }


    public static function updateOrderStatus($orderId, $newStatus) {
        $conn = Database::getConnection();
    
        $stmt = $conn->prepare("
            UPDATE orders
            SET status = ?
            WHERE id = ?
        ");
    
        return $stmt->execute([$newStatus, $orderId]);
    }
    public static function getChecks($startDate = null, $endDate = null, $userId = null)
    {
        $conn = Database::getConnection();

        // Base query to get all orders with user names
        $sql = "SELECT o.*, u.name as user_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.status != 'cancelled'";
        $params = [];

        if ($startDate) {
            $sql .= " AND o.created_at >= ?";
            $params[] = $startDate . " 00:00:00";
        }
        if ($endDate) {
            $sql .= " AND o.created_at <= ?";
            $params[] = $endDate . " 23:59:59";
        }
        if ($userId) {
            $sql .= " AND o.user_id = ?";
            $params[] = $userId;
        }

        $sql .= " ORDER BY o.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Grouping logic for the nested accordions
        $checks = [];
        foreach ($orders as $order) {
            $uId = $order['user_id'];
            if (!isset($checks[$uId])) {
                $checks[$uId] = [
                    'user_name' => $order['user_name'],
                    'total_amount' => 0,
                    'orders' => []
                ];
            }

            // Get items for this specific order
            $order['items'] = self::getOrderItems($order['id']);

            $checks[$uId]['total_amount'] += $order['total_amount'];
            $checks[$uId]['orders'][] = $order;
        }

        return $checks;
    }
}
<?php

require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/User.php";

class OrderController {

    public function store() {
        // Since we send JSON from the frontend
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Invalid data format']);
            return;
        }

        $userId = $_SESSION['userId'] ?? null;
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        // If admin, they might be placing an order for another user
        if (User::isAdmin() && !empty($data['bill_to_user_id'])) {
            $userIdToBill = $data['bill_to_user_id'];
        } else {
            $userIdToBill = $userId;
        }

        $roomNo = $data['room_no'] ?? null;
        $notes = $data['notes'] ?? '';
        $items = $data['items'] ?? [];
        $totalAmount = $data['total_amount'] ?? 0;

        if (empty($items) || empty($roomNo)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields (cart items or room).']);
            return;
        }

        $orderId = Order::create($userIdToBill, $roomNo, $notes, $totalAmount, $items);

        if ($orderId) {
            echo json_encode(['success' => true, 'order_id' => $orderId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create order.']);
        }
    }
    //k
    public function index() {
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['userId'];
        $orders = Order::getOrdersByUserId($userId);

        View::render("my-orders", ["orders" => $orders]);
    }

    public function show() {
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['userId'];
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            die("Order ID is required.");
        }

        $order = Order::getOrderById($orderId);

        if (!$order || $order['user_id'] != $userId) {
            die("Order not found or unauthorized.");
        }

        $items = Order::getOrderItems($orderId);

        View::render("order-details", [
            "order" => $order,
            "items" => $items
        ]);
    }

    public function cancel() {
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['userId'];
        $orderId = $_POST['order_id'] ?? null;

        if (!$orderId) {
            die("Order ID is required.");
        }

        Order::cancelOrder($orderId, $userId);

        header("Location: /my-orders");
        exit;
    }
    //k 
}

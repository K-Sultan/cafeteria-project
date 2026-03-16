<?php

require_once __DIR__ . "/../models/Order.php";
require_once __DIR__ . "/../models/User.php";

class OrderController
{

    public function store()
    {
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
    public function index()
    {
        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['userId'];
        $orders = Order::getOrdersByUserId($userId);

        View::render("my-orders", ["orders" => $orders]);
    }

    public function show()
    {
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

    public function cancel()
    {
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

    public function updateStatus() {
        header('Content-Type: application/json');

        if (!User::isAdmin()) {
            http_response_code(403); // 403 is for unauthorized access
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) { 
            http_response_code(400); // 400 Bad Request for invalid JSON
            echo json_encode(['success' => false, 'message' => 'Invalid data format']);
            return;
        }

        $orderId = isset($data['order_id']) ? (int) $data['order_id'] : 0;
        $newStatus = trim($data['status'] ?? '');
        $allowedStatuses = ['processing', 'out_for_delivery', 'done', 'cancelled'];

        if ($orderId <= 0 || !in_array($newStatus, $allowedStatuses, true)) {
            http_response_code(422); // 422 Unprocessable Entity for invalid data
            echo json_encode(['success' => false, 'message' => 'Invalid order id or status']);
            return;
        }

        $updated = Order::updateOrderStatus($orderId, $newStatus);

        if ($updated) {
            echo json_encode(['success' => true]);
            return;
        }

        http_response_code(500); // 500 Internal Server Error for failed updates
        echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
    }


    public function home() {
        if (!User::isAdmin()) {
            header("Location: /login");
            exit;
        }

        $filters = [
            'date' => trim($_GET['date'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'room_no' => trim($_GET['room_no'] ?? ''),
            'user_id' => trim($_GET['user_id'] ?? ''),
        ];

        $users = User::getAllUsers();
        $rooms = Order::getRoomsNumbers();
        $orders = Order::getOrdersWithItems($filters);

        View::render("admin/home", [
            "users" => $users,
            "rooms" => $rooms,
            "orders" => $orders,
            "filters" => $filters,
        ]);

    }


    public function checks()
    {
        if (!User::isAdmin()) {
            header("Location: /");
            exit;
        }

        $startDate = $_GET['date_from'] ?? null;
        $endDate = $_GET['date_to'] ?? null;
        $userId = $_GET['user_id'] ?? null;

        $checks = Order::getChecks($startDate, $endDate, $userId);
        $users = User::getAllUsers();

        View::render("admin/checks", [
            "checks" => $checks,
            "users" => $users,
            "filters" => [
                "date_from" => $startDate,
                "date_to" => $endDate,
                "user_id" => $userId
            ]
        ]);
    }
}



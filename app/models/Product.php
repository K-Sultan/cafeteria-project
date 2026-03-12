<?php

require_once __DIR__ . "/../../utility/database.php";

class Product {
    public static function getAllAvailable() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM products WHERE is_available = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllCategories() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

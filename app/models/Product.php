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

    public static function categoryExists($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE LOWER(TRIM(name)) = LOWER(TRIM(?))");
        $stmt->execute([$name]);
        return $stmt->fetchColumn() > 0;
    }

    public static function addCategory($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($stmt->execute([$name])) {
            return $conn->lastInsertId();
        }
        return false;
    }

    public static function findCategoryById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function categoryHasProducts($categoryId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public static function deleteCategory($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }


    public static function getAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare(
            "SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             ORDER BY p.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function nameExists($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE LOWER(TRIM(name)) = LOWER(TRIM(?))");
        $stmt->execute([$name]);
        return $stmt->fetchColumn() > 0;
    }

    public static function nameExistsForOther($name, $excludeId) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE LOWER(TRIM(name)) = LOWER(TRIM(?)) AND id <> ?");
        $stmt->execute([$name, $excludeId]);
        return $stmt->fetchColumn() > 0;
    }

    public static function add($name, $price, $image_path, $category_id, $is_available) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id, is_available) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $price, $image_path, $category_id, $is_available]);
    }

    public static function findById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function updateAvailability($id, $is_available) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE products SET is_available = ? WHERE id = ?");
        return $stmt->execute([$is_available, $id]);
    }

    public static function update($id, $name, $price, $image_path, $category_id, $is_available) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ?, category_id = ?, is_available = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $image_path, $category_id, $is_available, $id]);
    }
}

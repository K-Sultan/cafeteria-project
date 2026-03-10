<?php


require_once __DIR__ . "/../../utility/database.php";


class User{
    public static function getAllUsers() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
    

}

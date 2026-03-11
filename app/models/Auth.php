<?php

require_once __DIR__ . "/../../utility/database.php";

class Auth {
  
  public static function login($email, $password) {

    $conn = Database::getConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false;
    }

    if (password_verify($password, $user['password'])) {
        return $user['id'];
    }

    return false;
}



}
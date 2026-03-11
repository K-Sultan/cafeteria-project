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
  
    if (password_verify($password, $user['password_hash'])) {
        return $user['id'];
    }

    return false;
}


 public static function emailExists($email) {
    $conn = Database::getConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return false;
    }
    return true;
}

public static function updatePassword($email, $newPassword) {
    $conn = Database::getConnection();

    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    return $stmt->execute([$newPassword, $email]);

}

}
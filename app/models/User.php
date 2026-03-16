<?php


require_once __DIR__ . "/../../utility/database.php";


class User
{
    public static function getAllUsers()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public static function isLogin()
    {
        if (isset($_SESSION["userId"])) {
            return true;
        } else {
            return false;
        }
    }

    public static function getCurrentUser()
    {
        if (self::isLogin()) {
            $conn = Database::getConnection();

            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION["userId"]]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }


    public static function getUserById($id)
    {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?? null;
    }

    public static function isAdmin()
    {
        if (self::isLogin()) {
            $user = self::getCurrentUser();
            return ($user["role"] == "admin");
        }
        return false;
    }

    public static function create($data)
    {
        $conn = Database::getConnection();
        $sql = "INSERT INTO users (name, email, password_hash, room_no, extension, profile_pic) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['room_no'],
            $data['extension'],
            $data['profile_pic']
        ]);
    }

    public static function update($id, $data)
    {
        $conn = Database::getConnection();

        $fields = [
            "name" => $data["name"],
            "email" => $data["email"],
            "room_no" => $data["room_no"],
            "extension" => $data["extension"]
        ];

        $sql = "UPDATE users SET name=?, email=?, room_no=?, extension=?";

        $params = [
            $fields["name"],
            $fields["email"],
            $fields["room_no"],
            $fields["extension"]
        ];

        // Update password if provided
        if (!empty($data["password"])) {
            $sql .= ", password_hash=?";
            $params[] = password_hash($data["password"], PASSWORD_DEFAULT);
        }

        // Update profile picture if provided
        if (!empty($data["profile_pic"])) {
            $sql .= ", profile_pic=?";
            $params[] = $data["profile_pic"];
        }

        $sql .= " WHERE id=?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);

        return $stmt->execute($params);
    }

    public static function delete($id)
    {
        $conn = Database::getConnection();

        $user = self::getUserById($id);

        if ($user && !empty($user["profile_pic"])) {

            $path = __DIR__ . "/../../../public/uploads/" . $user["profile_pic"];

            if (file_exists($path)) {
                unlink($path);
            }
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");

        return $stmt->execute([$id]);
    }

    public static function emailExists($email)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ? true : false;
    }
}

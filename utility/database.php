<?php 

class Database {
    private $host = "127.0.0.1";
    private $port = "5523";
    private $db_name = "jamavgwz_kafateria";
    private $username = "jamavgwz_kafateria";
    private $password = "KD4xUQo1o8";
    private static $instance = null;
    private $conn;

   private function __construct() {

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            } catch(PDOException $e) {
                die("Connection error: " . $e->getMessage());
            }

   }
    public static function getConnection() {
        if (self::$instance == null) {
          self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
   


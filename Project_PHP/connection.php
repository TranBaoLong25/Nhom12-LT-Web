<?php
class Database {
    private static $host = "localhost";
    private static $dbname = "homestay_db";
    private static $username = "root";
    private static $password = ""; 
    public static function getConnection() {
        try {
            $conn = new PDO(
                "mysql:host=" . self::$host . ";port=3306;dbname=" . self::$dbname . ";charset=utf8",
                self::$username,
                self::$password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Kết nối thất bại: " . $e->getMessage());
        }
    }
}
?>

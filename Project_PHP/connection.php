<?php
$host = "localhost";
$dbname = "homestay_db"; 
$username = "root";
$password = "09042005"; // nếu có password thì điền vào

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Thiết lập lỗi PDO thành Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>

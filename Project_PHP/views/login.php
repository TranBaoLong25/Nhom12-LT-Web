<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURA HOTEL</title>
    <link rel="stylesheet" href="/Project_PHP/assets/css/header.css">
    <link rel="stylesheet" href="/Project_PHP/assets/css/auth.css"> 
</head>
<body>


<?php

include_once('./fragments/header.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ tên đăng nhập và mật khẩu.';
    } elseif ($username === 'admin' && $password === '123456') {
        $_SESSION['user'] = $username;
        header('Location: welcome.php');
        exit();
    } else {
        $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
    }
}
?>
<main class="page-content">
        <div class="auth-container">
            <h2>Đăng nhập</h2>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <button type="submit" class="btn">Đăng nhập</button>
            </form>
            <a href="register.php" class="switch-link">Chưa có tài khoản? Đăng ký tại đây.</a>
        </div>
    </main>

</body>
</html>
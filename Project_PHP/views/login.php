<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../services/IUserService.php'; 
require_once __DIR__ . '/../repositories/IUserRepository.php'; 
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../models/User.php';


$conn = Database::getConnection();
$userService = new UserService(new UserRepository($conn));

$error = '';

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Vui lòng điền đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        $user = $userService->login($username, $password);
        if ($user) {
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'role' => $user->getRole()
            ];
            header('Location: /index.php'); 
            exit();
        } else {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - AURA HOTEL</title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/auth.css"> 
</head>
<body>

<?php include_once('./fragments/header.php'); ?>

<main class="page-content">
    <div class="auth-container">
        <h2>Đăng nhập</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
        <a href="register.php" class="switch-link">Chưa có tài khoản? Đăng ký tại đây.</a>
    </div>
</main>

</body>
</html>

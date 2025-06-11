<?php
session_start();
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../services/IUserService.php'; 
require_once __DIR__ . '/../repositories/IUserRepository.php'; 
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../repositories/UserRepository.php';
require_once __DIR__ . '/../models/User.php';

$conn = Database::getConnection();

$error = '';
$success = '';

// Nếu form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $role = $_POST['role'] ?? 'user';

    // Kiểm tra rỗng
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
    // Kiểm tra mật khẩu khớp
    elseif ($password !== $confirmPassword) {
        $error = "Mật khẩu xác nhận không khớp.";
    }
    // Kiểm tra độ dài mật khẩu
    elseif (strlen($password) < 6) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự.";
    }
    else {
        $userService = new UserService(new UserRepository($conn));

        // Kiểm tra tên đã tồn tại
        $existingUser = $userService->findByUsername($username);
        if ($existingUser) {
            $error = "Tên đăng nhập đã tồn tại.";
        } else {
            // Tạo user mới
            $newUser = new User(null, $username, $password, $role);
            $isSuccess = $userService->register($newUser);

            if ($isSuccess) {
                $success = "Đăng ký thành công! Bạn có thể đăng nhập.";
                header('Location: login.php');
                exit();
            } else {
                $error = "Đăng ký thất bại. Vui lòng thử lại.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
<main class="page-content">
    <div class="auth-container">
        <h2>Đăng ký</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Xác nhận mật khẩu:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" required>
                    <option value="user" <?= (($_POST['role'] ?? '') === 'user' ? 'selected' : '') ?>>Người dùng</option>
                    <option value="admin" <?= (($_POST['role'] ?? '') === 'admin' ? 'selected' : '') ?>>Quản trị viên</option>
                </select>
            </div>

            <?php if (!empty($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <button type="submit" class="btn">Đăng ký</button>
        </form>
        <a href="login.php" class="switch-link">Đã có tài khoản? Đăng nhập tại đây.</a>
    </div>
</main>
</body>
</html>

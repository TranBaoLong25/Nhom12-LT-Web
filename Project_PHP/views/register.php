<?php
session_start();
include_once('./fragments/header.php');

$error = '';    // Biến để lưu thông báo lỗi
$success = '';  // Biến để lưu thông báo thành công

// Kiểm tra nếu form đã được gửi đi (sử dụng phương thức POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Bắt đầu phần xử lý logic đăng ký ---
    // Kiểm tra các trường không được rỗng
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    }
    // Kiểm tra định dạng email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Địa chỉ email không hợp lệ.';
    }
    // Kiểm tra mật khẩu và xác nhận mật khẩu có khớp không
    elseif ($password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp.';
    }
    // Kiểm tra độ dài mật khẩu (ví dụ: tối thiểu 6 ký tự)
    elseif (strlen($password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } else {
        // Trong ứng dụng thực tế: bạn sẽ mã hóa mật khẩu bằng password_hash()
        // và lưu thông tin người dùng mới vào cơ sở dữ liệu.
        $success = 'Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.';
        // Sau khi đăng ký thành công, bạn có thể tự động chuyển hướng về trang đăng nhập
        // header('Location: login.php?registered=true');
        // exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <?php if ($success): ?>
                    <p class="success"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
                <button type="submit" class="btn">Đăng ký</button>
            </form>
            <a href="login.php" class="switch-link">Đã có tài khoản? Đăng nhập tại đây.</a>
        </div>
    </main>
    <?php // include_once('./fragments/footer.php'); ?>
</body>
</html>
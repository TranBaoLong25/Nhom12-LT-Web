
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo '<p>Bạn chưa đăng nhập. <a href="/login.php">Đăng nhập</a></p>';
    exit();
}

// Kiểm tra quyền admin
if ($_SESSION['user']['role'] !== 'admin') {
    echo '<p>Bạn không đủ quyền hạn. <a href="/index.php">Quay về trang chủ</a></p>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="container">
        <h2>Quản lý người dùng</h2>

        <!-- Form tìm kiếm -->
        <form action="/index.php" method="get">
            <input type="hidden" name="controller" value="manageruser">
            <input type="hidden" name="action" value="search">
            <input type="text" name="keyword" placeholder="Nhập username..." required>
            <button type="submit">Tìm kiếm</button>
        </form>

        <!-- Hiển thị nếu không có kết quả -->
        <?php if (empty($users)) : ?>
            <p>Không tìm thấy người dùng nào.</p>
        <?php else : ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user->getId()) ?></td>
                            <td><?= htmlspecialchars($user->getUsername()) ?></td>
                            <td><?= htmlspecialchars($user->getRole()) ?></td>
                            <td>
                                <a href="/index.php?controller=manageruser&action=edit&id=<?= $user->getId() ?>">Sửa</a> |
                                <a href="/index.php?controller=manageruser&action=delete&id=<?= $user->getId() ?>" onclick="return confirm('Xóa người dùng này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>

        <!-- Form thêm người dùng -->
        <div class="user-form">
            <h3>Thêm người dùng</h3>
            <form action="/index.php?controller=manageruser&action=save" method="post">
                <label>Tên đăng nhập:</label>
                <input type="text" name="username" required><br>

                <label>Mật khẩu:</label>
                <input type="password" name="password" required><br>

                <label>Vai trò:</label>
                <select name="role">
                    <option value="customer">Customer</option>
                    <option value="manager">Manager</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select><br>

                <button type="submit">Thêm</button>
            </form>
        </div>

        <!-- Form sửa người dùng -->
        <?php if (isset($editUser)) : ?>
            <div class="user-form">
                <h3>Sửa người dùng</h3>
                <form action="/index.php?controller=manageruser&action=update" method="post">
                    <input type="hidden" name="id" value="<?= $editUser->getId() ?>">
                    
                    <label>Tên đăng nhập:</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($editUser->getUsername()) ?>" required><br>

                    <label>Mật khẩu:</label>
                    <input type="password" name="password" required><br>

                    <label>Vai trò:</label>
                    <select name="role">
                        <option value="student" <?= $editUser->getRole() === 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="parent" <?= $editUser->getRole() === 'parent' ? 'selected' : '' ?>>Parent</option>
                        <option value="manager" <?= $editUser->getRole() === 'manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="staff" <?= $editUser->getRole() === 'staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="admin" <?= $editUser->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select><br>

                    <button type="submit">Cập nhật</button>
                </form>
            </div>
        <?php endif ?>
    </div>
</body>
</html>

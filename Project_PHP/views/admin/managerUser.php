<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.php');
    exit;
}

require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../models/User.php');
require_once(__DIR__ . '/../../repositories/IUserRepository.php');
require_once(__DIR__ . '/../../repositories/UserRepository.php');
require_once(__DIR__ . '/../../services/IUserService.php');
require_once(__DIR__ . '/../../services/UserService.php');

$conn = Database::getConnection();
$userRepository = new UserRepository($conn);
$userService = new UserService($userRepository);

$users = $users ?? $userService->getAllUsers();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        nav a { margin-right: 10px; text-decoration: none; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        .user-form { margin-top: 30px; }
        input, select { margin: 5px; padding: 5px; }
    </style>
</head>
<body>
<nav>
    <a href="/">Trang Chủ</a>
    <a href="/views/admin/managerUser.php">Users</a>
    <a href="/views/admin/managerService.php">Service</a>
    <a href="/views/admin/managerHomestay.php">Homestay</a>
    <a href="/views/admin/managerBookedRoom.php">BookedRoom</a>
    <a href="/views/admin/managerBookedService.php">BookedService</a>
</nav>

<div class="container">
    <!-- Form tìm kiếm -->
    <form method="GET" action="/index.php">
        <input type="hidden" name="controller" value="manageruser">
        <input type="hidden" name="action" value="searchUser">
        <input type="text" name="keyword" placeholder="Tìm theo username">
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Danh sách người dùng -->
    <h2>Danh sách người dùng</h2>
    <?php if (empty($users)): ?>
        <p>Không tìm thấy người dùng!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Mật khẩu</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user->getId()) ?></td>
                        <td><?= htmlspecialchars($user->getUsername()) ?></td>
                        <td><?= htmlspecialchars($user->getPassword()) ?></td>
                        <td><?= htmlspecialchars($user->getRole()) ?></td>
                        <td>
                            <a href="/index.php?controller=manageruser&action=editUser&id=<?= $user->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=manageruser&action=deleteUser&id=<?= $user->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Form thêm/sửa người dùng -->
    <div class="user-form">
        <?php if (isset($editUser)) { ?>
            <h3>Sửa người dùng</h3>
            <form action="/index.php?controller=manageruser&action=updateUser" method="POST">
                <input type="hidden" name="id" value="<?= $editUser->getId() ?>">
                <input type="text" name="username" value="<?= $editUser->getUsername() ?>" required>
                <input type="text" name="password" value="<?= $editUser->getPassword() ?>" required>
                <select name="role" required>
                    <option value="admin" <?= $editUser->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="manager" <?= $editUser->getRole() === 'manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="staff" <?= $editUser->getRole() === 'staff' ? 'selected' : '' ?>>Staff</option>
                    <option value="customer" <?= $editUser->getRole() === 'customer' ? 'selected' : '' ?>>Customer</option>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        <?php } else { ?>
            <h3>Thêm người dùng</h3>
            <form action="/index.php?controller=manageruser&action=save" method="POST">
                <input type="text" name="username" placeholder="Tên đăng nhập" required>
                <input type="text" name="password" placeholder="Mật khẩu" required>
                <select name="role" required>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
                <button type="submit">Thêm</button>
            </form>
        <?php } ?>
    </div>
</div>
</body>
</html>

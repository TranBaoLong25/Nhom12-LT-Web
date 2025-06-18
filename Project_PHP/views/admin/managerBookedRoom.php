<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.php');
    exit;
}

require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../models/BookedRoom.php');
require_once(__DIR__ . '/../../repositories/IBookedRoomRepository.php');
require_once(__DIR__ . '/../../repositories/BookedRoomRepository.php');
require_once(__DIR__ . '/../../services/IBookedRoomService.php');
require_once(__DIR__ . '/../../services/BookedRoomService.php');

$conn = Database::getConnection();
$BookedRoomRepository = new BookedRoomRepository($conn);
$BookedRoomService = new BookedRoomService($BookedRoomRepository);

$BookedRooms = $BookedRooms ?? $BookedRoomService->findAll();
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
        .BookedRoom-form { margin-top: 30px; }
        input, select { margin: 5px; padding: 5px; }
    </style>
</head>
<body>
<nav>
    <a href="/">Trang Chủ</a>
    <a href="/views/admin/managerBookedRoom.php">BookedRooms</a>
    <a href="/views/admin/managerService.php">Service</a>
    <a href="/views/admin/managerHomestay.php">Homestay</a>
    <a href="/views/admin/managerBookedRoom.php">BookedRoom</a>
    <a href="/views/admin/managerBookedService.php">BookedService</a>
</nav>

<div class="container">
    <!-- Form tìm kiếm -->
    <form method="GET" action="/index.php">
        <input type="hidden" name="controller" value="managerBookedRoom">
        <input type="hidden" name="action" value="searchBookedRoom">
        <input type="text" name="keyword" placeholder="Tìm theo BookedRoomname">
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Danh sách người dùng -->
    <h2>Danh sách người dùng</h2>
    <?php if (empty($BookedRooms)): ?>
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
                <?php foreach ($BookedRooms as $BookedRoom): ?>
                    <tr>
                        <td><?= htmlspecialchars($BookedRoom->getId()) ?></td>
                        <td><?= htmlspecialchars($BookedRoom->getBookedRoomname()) ?></td>
                        <td><?= htmlspecialchars($BookedRoom->getPassword()) ?></td>
                        <td><?= htmlspecialchars($BookedRoom->getRole()) ?></td>
                        <td>
                            <a href="/index.php?controller=managerBookedRoom&action=editBookedRoom&id=<?= $BookedRoom->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerBookedRoom&action=deleteBookedRoom&id=<?= $BookedRoom->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Form thêm/sửa người dùng -->
    <div class="BookedRoom-form">
        <?php if (isset($editBookedRoom)) { ?>
            <h3>Sửa người dùng</h3>
            <form action="/index.php?controller=managerBookedRoom&action=updateBookedRoom" method="POST">
                <input type="hidden" name="id" value="<?= $editBookedRoom->getId() ?>">
                <input type="text" name="BookedRoomname" value="<?= $editBookedRoom->getBookedRoomname() ?>" required>
                <input type="text" name="password" value="<?= $editBookedRoom->getPassword() ?>" required>
                <select name="role" required>
                    <option value="admin" <?= $editBookedRoom->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="manager" <?= $editBookedRoom->getRole() === 'manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="staff" <?= $editBookedRoom->getRole() === 'staff' ? 'selected' : '' ?>>Staff</option>
                    <option value="customer" <?= $editBookedRoom->getRole() === 'customer' ? 'selected' : '' ?>>Customer</option>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        <?php } else { ?>
            <h3>Thêm người dùng</h3>
            <form action="/index.php?controller=managerBookedRoom&action=save" method="POST">
                <input type="text" name="BookedRoomname" placeholder="Tên đăng nhập" required>
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

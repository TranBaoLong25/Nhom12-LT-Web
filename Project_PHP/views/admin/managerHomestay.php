<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.php');
    exit;
}

require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../models/HomeStay.php');
require_once(__DIR__ . '/../../repositories/IHomeStayRepository.php');
require_once(__DIR__ . '/../../repositories/HomeStayRepository.php');
require_once(__DIR__ . '/../../services/IHomeStayService.php');
require_once(__DIR__ . '/../../services/HomeStayService.php');

$conn = Database::getConnection();
$homestayRepository = new HomeStayRepository($conn);
$homestayService = new HomeStayService($homestayRepository);

$homestays = $homestays ?? $homestayService->getAllHomeStay();
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
        .Homestay-form { margin-top: 30px; }
        input, select { margin: 5px; padding: 5px; }
    </style>
</head>
<body>
<nav>
    <a href="/">Trang Chủ</a>
    <a href="/views/admin/managerUser.php">Homestays</a>
    <a href="/views/admin/managerService.php">Service</a>
    <a href="/views/admin/managerHomestay.php">Homestay</a>
    <a href="/views/admin/managerBookedRoom.php">BookedRoom</a>
    <a href="/views/admin/managerBookedService.php">BookedService</a>
</nav>

<div class="container">
    <!-- Form tìm kiếm -->
    <form method="GET" action="/index.php">
        <input type="hidden" name="controller" value="managerHomestay">
        <input type="hidden" name="action" value="searchHomestay">
        <input type="text" name="keyword" placeholder="Tìm theo id">
        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- Danh sách người dùng -->
    <h2>Danh sách người dùng</h2>
    <?php if (empty($homestays)): ?>
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
                <?php foreach ($homestays as $homestay): ?>
                    <tr>
                        <td><?= htmlspecialchars($Homestay->getId()) ?></td>
                        <td><?= htmlspecialchars($Homestay->getHomestayname()) ?></td>
                        <td><?= htmlspecialchars($Homestay->getPassword()) ?></td>
                        <td><?= htmlspecialchars($Homestay->getRole()) ?></td>
                        <td>
                            <a href="/index.php?controller=managerHomestay&action=editHomestay&id=<?= $Homestay->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerHomestay&action=deleteHomestay&id=<?= $Homestay->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Form thêm/sửa người dùng -->
    <div class="Homestay-form">
        <?php if (isset($editHomestay)) { ?>
            <h3>Sửa người dùng</h3>
            <form action="/index.php?controller=managerHomestay&action=updateHomestay" method="POST">
                <input type="hidden" name="id" value="<?= $editHomestay->getId() ?>">
                <input type="text" name="Homestayname" value="<?= $editHomestay->getHomestayname() ?>" required>
                <input type="text" name="password" value="<?= $editHomestay->getPassword() ?>" required>
                <select name="role" required>
                    <option value="admin" <?= $editHomestay->getRole() === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="manager" <?= $editHomestay->getRole() === 'manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="staff" <?= $editHomestay->getRole() === 'staff' ? 'selected' : '' ?>>Staff</option>
                    <option value="customer" <?= $editHomestay->getRole() === 'customer' ? 'selected' : '' ?>>Customer</option>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        <?php } else { ?>
            <h3>Thêm người dùng</h3>
            <form action="/index.php?controller=managerHomestay&action=save" method="POST">
                <input type="text" name="Homestayname" placeholder="Tên đăng nhập" required>
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

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
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        padding: 30px 0 0 0;
        margin: 0;
        background: #f4f8fb;
        min-height: 100vh;
    }
    nav {
        background: #0066cc;
        padding: 15px 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    nav a {
        color: #fff;
        margin-right: 18px;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    nav a:hover, nav a:focus {
        background: #0050a7;
        color: #fff;
    }
    .container {
        max-width: 950px;
        margin: auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 30px 40px 40px 40px;
    }
    h2, h3 {
        color: #055290;
        margin-bottom: 16px;
        letter-spacing: 0.5px;
    }
    form {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    form input[type="text"], form input[type="number"], form select {
        padding: 7px 12px;
        font-size: 15px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #f9fbfd;
        transition: border .2s;
        outline: none;
    }
    form input[type="text"]:focus, form input[type="number"]:focus, form select:focus {
        border: 1.5px solid #4096ee;
    }
    form button {
        background: #0066cc;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 18px;
        font-size: 15px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.2s;
    }
    form button:hover {
        background: #004c9c;
    }
    table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        background: #f9fbfd;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
        margin-top: 10px;
    }
    th, td {
        padding: 11px 10px;
        text-align: center;
    }
    th {
        background: #e6f0fa;
        color: #055290;
        font-size: 16px;
        letter-spacing: 0.5px;
        border-bottom: 1.5px solid #b5d1ea;
    }
    tr:nth-child(even) {
        background: #f1f5f9;
    }
    tr:hover {
        background: #e0ecff;
        transition: background 0.2s;
    }
    td {
        font-size: 15px;
        color: #333;
    }
    a {
        color: #0066cc;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    a:hover {
        color: #003366;
        text-decoration: underline;
    }
    .BookedRoom-form {
        margin-top: 35px;
        padding: 18px 22px 12px 22px;
        background: #f5faff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        max-width: 420px;
    }
    .BookedRoom-form form {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .BookedRoom-form input, .BookedRoom-form select {
        font-size: 15px;
        padding: 7px 12px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #fff;
        margin-bottom: 6px;
        width: 100%;
        box-sizing: border-box;
    }
    .BookedRoom-form button {
        margin-top: 10px;
        min-width: 110px;
    }
    @media (max-width: 700px) {
        .container {
            padding: 15px 4vw;
        }
        .BookedRoom-form {
            padding: 12px 8px;
        }
        th, td {
            font-size: 14px;
            padding: 8px 4px;
        }
    }
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

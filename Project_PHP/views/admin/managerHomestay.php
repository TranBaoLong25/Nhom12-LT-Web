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

// Nếu có biến $editHomestay được controller truyền vào thì không gọi getAll nữa
$homestays = $homestays ?? $homestayService->getAllHomeStay();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Homestay</title>
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
        max-width: 1050px;
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
    form input[type="text"], form input[type="number"] {
        padding: 7px 12px;
        font-size: 15px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #f9fbfd;
        transition: border .2s;
        outline: none;
    }
    form input[type="text"]:focus, form input[type="number"]:focus {
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
        vertical-align: middle;
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
    .homestay-form {
        margin-top: 35px;
        padding: 18px 22px 12px 22px;
        background: #f5faff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        max-width: 450px;
    }
    .homestay-form h3 {
        margin-top: 0;
    }
    .homestay-form form {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .homestay-form input[type="text"],
    .homestay-form input[type="number"] {
        font-size: 15px;
        padding: 7px 12px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #fff;
        margin-bottom: 6px;
        width: 100%;
        box-sizing: border-box;
    }
    .homestay-form label {
        font-size: 14px;
        margin-bottom: 5px;
        color: #055290;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .homestay-form input[type="checkbox"] {
        margin-right: 4px;
        accent-color: #0066cc;
    }
    .homestay-form input[type="file"] {
        font-size: 14px;
    }
    .homestay-form button {
        margin-top: 10px;
        min-width: 110px;
    }
    td img {
        border-radius: 8px;
        margin: 2px 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        max-width: 80px;
        max-height: 50px;
        object-fit: cover;
        background: #e6f0fa;
    }
    @media (max-width: 700px) {
        .container {
            padding: 15px 4vw;
        }
        .homestay-form {
            padding: 12px 8px;
        }
        th, td {
            font-size: 14px;
            padding: 8px 4px;
        }
        td img {
            max-width: 60px;
            max-height: 38px;
        }
    }
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
    <form method="GET" action="/index.php">
        <input type="hidden" name="controller" value="managerHomestay">
        <input type="hidden" name="action" value="searchHomestay">
        <input type="text" name="keyword" placeholder="Tìm theo id">
        <button type="submit">Tìm kiếm</button>
    </form>

    <h2>Danh sách Homestay</h2>
    <?php if (empty($homestays)): ?>
        <p>Không tìm thấy homestay!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Loại phòng</th>
                    <th>Vị trí</th>
                    <th>Giá phòng</th>
                    <th>Đã đặt</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($homestays as $homestay): ?>
                    <tr>
                        <td><?= htmlspecialchars($homestay->getId()) ?></td>
                        <td><?= htmlspecialchars($homestay->getRoomType()) ?></td>
                        <td><?= htmlspecialchars($homestay->getLocation()) ?></td>
                        <td><?= htmlspecialchars($homestay->getRoomPrice()) ?></td>
                        <td><?= $homestay->getBooked() ? 'Đã đặt' : 'Chưa đặt' ?></td>
                        <td>
                            <?php foreach ($homestay->getImage() as $img): ?>
                                <img src="/<?= htmlspecialchars($img) ?>" width="80">
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <a href="/index.php?controller=managerHomestay&action=editHomestay&id=<?= $homestay->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerHomestay&action=deleteHomestay&id=<?= $homestay->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="homestay-form">
        <?php if (isset($editHomestay)) { ?>
            <h3>Sửa Homestay</h3>
            <form action="/index.php?controller=managerHomestay&action=updateHomestay" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $editHomestay->getId() ?>">
                <input type="text" name="room_type" value="<?= $editHomestay->getRoomType() ?>" required>
                <input type="text" name="location" value="<?= $editHomestay->getLocation() ?>" required>
                <input type="number" name="room_price" value="<?= $editHomestay->getRoomPrice() ?>" required>
                <label>Đã đặt: <input type="checkbox" name="booked" <?= $editHomestay->getBooked() ? 'checked' : '' ?>></label>
                <label>Ảnh mới: <input type="file" name="images[]" multiple></label>
                <button type="submit">Cập nhật</button>
            </form>
        <?php } else { ?>
            <h3>Thêm Homestay</h3>
            <form action="/index.php?controller=managerHomestay&action=save" method="POST" enctype="multipart/form-data">
                <input type="text" name="room_type" placeholder="Loại phòng" required>
                <input type="text" name="location" placeholder="Vị trí" required>
                <input type="number" name="room_price" placeholder="Giá phòng" required>
                <label>Đã đặt: <input type="checkbox" name="booked"></label>
                <label>Ảnh: <input type="file" name="images[]" multiple></label>
                <button type="submit">Thêm</button>
            </form>
        <?php } ?>
    </div>
</div>
</body>
</html>

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
        body { font-family: Arial; padding: 20px; }
        nav a { margin-right: 10px; text-decoration: none; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        .homestay-form { margin-top: 30px; }
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

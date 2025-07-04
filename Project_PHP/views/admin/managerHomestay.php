<?php
require_once(__DIR__ . '/../../models/HomeStay.php');
require_once(__DIR__ . '/../../models/Location.php');
require_once(__DIR__ . '/../../models/RoomType.php');
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../repositories/IHomeStayRepository.php');
require_once(__DIR__ . '/../../repositories/HomeStayRepository.php');
require_once(__DIR__ . '/../../services/IHomeStayService.php');
require_once(__DIR__ . '/../../services/HomeStayService.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { 
    if ($_SESSION['user']['role'] === 'customer'){
        header('Location: /views/home.php');
        echo "Không dủ quyền hạn!";
        exit;
    }
    else{
    header('Location: /views/login.php');
    exit;
    }
}
$conn = Database::getConnection();
$homestayRepository = new HomeStayRepository($conn);
$homestayService = new HomeStayService($homestayRepository);

$editHomestay = null;
if (isset($_SESSION['editHomestayId'])) {
    $editHomestay = $homestayService->findById($_SESSION['editHomestayId']);
    unset($_SESSION['editHomestayId']);
}

$homestays = $homestays ?? $homestayService->getAllHomeStay();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Homestay</title>
    <link rel="stylesheet" href="/assets/css/Manager.css">

</head>
<body>
<nav style="text-align: center;">
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

    <h2 style="text-align: center;">Danh sách Homestay</h2>
    <?php if (empty($homestays)): ?>
        <p>Không tìm thấy homestay!</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
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
                            <a href="/index.php?controller=managerHomestay&action=editHomeStay&id=<?= $homestay->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerHomestay&action=deleteHomeStay&id=<?= $homestay->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <p style="text-align: center;"><a href="/views/admin/managerHomestay.php">Thêm HomeStay</a></p>
    <div class="homestay-form">
        <?php if (isset($editHomestay)) { ?>
            <h3>Sửa Homestay</h3>
            <form action="/index.php?controller=managerHomestay&action=updateHomeStay" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $editHomestay->getId() ?>">
                <label>Loại phòng:</label>
                <select name="room_type" required>
                    <?php foreach (RoomType::cases() as $type): ?>
                        <option value="<?= $type->value ?>" <?= $editHomestay->getRoomType() === $type->value ? 'selected' : '' ?>><?= $type->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Vị trí:</label>
                <select name="location" required>
                    <?php foreach (Location::cases() as $loc): ?>
                        <option value="<?= $loc->value ?>" <?= $editHomestay->getLocation() === $loc->value ? 'selected' : '' ?>><?= $loc->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Giá phòng (VNĐ):</label>
                <input type="number" name="room_price" placeholder="Ví dụ: 500000" required>
                <label>Đã đặt: <input type="checkbox" name="booked" <?= $editHomestay->getBooked() ? 'checked' : '' ?>></label>
                <label>Ảnh mới: <input type="file" name="images[]" multiple></label>
                <button style="align-self: center;" type="submit">Cập nhật</button>
            </form>
        <?php } else { ?>
            <h3>Thêm Homestay</h3>
            <form action="/index.php?controller=managerHomestay&action=save" method="POST" enctype="multipart/form-data">
                <label>Loại phòng:</label>
                <select name="room_type" required>
                    <?php foreach (RoomType::cases() as $type): ?>
                        <option value="<?= $type->value ?>"><?= $type->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Vị trí:</label>
                <select name="location" required>
                    <?php foreach (Location::cases() as $loc): ?>
                        <option value="<?= $loc->value ?>"><?= $loc->name ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Giá phòng (VNĐ):</label>
                <input type="number" name="room_price" placeholder="Ví dụ: 500000" required>
                <label>Đã đặt: <input type="checkbox" name="booked"></label>
                <label>Ảnh: <input type="file" name="images[]" multiple></label>
                <button style="align-self: center;" type="submit">Thêm</button>
            </form>
        <?php } ?>
    </div>
</div>
</body>
</html>
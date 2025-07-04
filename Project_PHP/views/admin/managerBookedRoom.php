<?php
if (session_status() == PHP_SESSION_NONE){ session_start();}
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
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../models/BookedRoom.php');
require_once(__DIR__ . '/../../repositories/IBookedRoomRepository.php');
require_once(__DIR__ . '/../../services/IBookedRoomService.php');
require_once(__DIR__ . '/../../repositories/BookedRoomRepository.php');
require_once(__DIR__ . '/../../services/BookedRoomService.php');

$conn = Database::getConnection();
$bookedRoomService = new BookedRoomService(new BookedRoomRepository($conn));
$bookedRooms = $bookedRooms ?? $bookedRoomService->getAllBookedRoom();
?>
<?php
if (defined('VIEW_RENDERED')) return;
define('VIEW_RENDERED', true);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Booked Room</title>
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
        <input type="hidden" name="controller" value="managerBookedRoom">
        <input type="hidden" name="action" value="searchBookedRoom">
        <input type="text" name="keyword" placeholder="Tìm theo user_id">
        <button type="submit">Tìm kiếm</button>
    </form>

    <h2 style="text-align: center;" >Danh sách Phòng đã đặt</h2>
    <?php if (empty($bookedRooms)): ?>
        <p>Không có dữ liệu!</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>Ngày nhận</th>
                    <th>Ngày trả</th>
                    <th>User ID</th>
                    <th>Homestay ID</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookedRooms as $room): ?>
                    <tr>
                        <td><?= $room->getId() ?></td>
                        <td><?= $room->getGuestName() ?></td>
                        <td><?= $room->getGuestPhone() ?></td>
                        <td><?= $room->getCheckIn() ?></td>
                        <td><?= $room->getCheckOut() ?></td>
                        <td><?= $room->getUserId() ?></td>
                        <td><?= $room->getHomeStayId() ?></td>
                        <td>
                            <a href="/index.php?controller=managerBookedRoom&action=editBookedRoom&id=<?= $room->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerBookedRoom&action=deleteById&id=<?= $room->getId() ?>" onclick="return confirm('Xóa phòng đã đặt?')">Xóa</a>

                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
    <p style="text-align: center;"><a href="/views/admin/managerBookedRoom.php">Thêm phòng đã đặt</a></p> 
<div class="booked-form">
    <?php if (isset($editBookedRoom)) { ?>
        <h3>Sửa thông tin</h3>
        <form action="/index.php?controller=managerBookedRoom&action=updateBookedRoom" method="POST">
            <input type="hidden" name="id" value="<?= $editBookedRoom->getId() ?>">
            <input type="text" name="guest_name" value="<?= $editBookedRoom->getGuestName() ?>" required>

            <input type="tel" name="guest_phone" value="<?= $editBookedRoom->getGuestPhone() ?>" 
                pattern="^0[0-9]{9}$" maxlength="10" title="SĐT phải bắt đầu bằng số 0 và có 10 chữ số" 
                required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <input type="date" name="check_in_date" value="<?= $editBookedRoom->getCheckIn() ?>" required>
            <input type="date" name="check_out_date" value="<?= $editBookedRoom->getCheckOut() ?>" required>
            <input type="number" name="user_id" value="<?= $editBookedRoom->getUserId() ?>" required>
            <input type="number" name="homestay_id" value="<?= $editBookedRoom->getHomeStayId() ?>" required>
            <button style="align-self: center;" type="submit">Cập nhật</button>
        </form>

    <?php  
        unset($_SESSION['editBookedRoom']); 
    } else { ?>
        <h3>Thêm Booked Room</h3>
        <form action="/index.php?controller=managerBookedRoom&action=save" method="POST">
            <input type="text" name="guest_name" placeholder="Họ tên" required>

            <input type="tel" name="guest_phone" placeholder="SĐT" 
                pattern="^0[0-9]{9}$" maxlength="10" title="SĐT phải bắt đầu bằng số 0 và có 10 chữ số" 
                required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <input type="date" name="check_in_date" required>
            <input type="date" name="check_out_date" required>
            <input type="number" name="user_id" placeholder="User ID" required>
            <input type="number" name="homestay_id" placeholder="Homestay ID" required>
            <button style="align-self: center;" type="submit">Thêm</button>
        </form>

    <?php } ?>
</div>

</div>
</body>
</html>

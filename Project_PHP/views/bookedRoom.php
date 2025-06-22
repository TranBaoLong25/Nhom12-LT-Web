<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('./fragments/header.php');
require_once __DIR__ . '/../models/BookedRoom.php';
require_once __DIR__ . '/../repositories/IBookedRoomRepository.php';
require_once __DIR__ . '/../repositories/BookedRoomRepository.php';
require_once __DIR__ . '/../services/IBookedRoomService.php';
require_once __DIR__ . '/../services/BookedRoomService.php';
require_once __DIR__ . '/../connection.php';

$conn = Database::getConnection();
$bookedRoomRepository = new BookedRoomRepository($conn);
$bookedRoomService = new BookedRoomService($bookedRoomRepository);

// LẤY DANH SÁCH PHÒNG ĐỘNG TỪ DATABASE
$rooms = [];
try {
    $stmt = $conn->query("SELECT * FROM homestay");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rooms[] = [
            'id' => $row['id'],
            'name' => $row['room_type'],
            'type' => $row['room_type'],
            'price' => $row['room_price'],
            'image' => '/assets/images/img' . $row['id'] . '.jpg',
            'description' => '',
        ];
    }
} catch (Exception $e) {
    $rooms = [];
}

// --- Logic xử lý đặt phòng khi form được gửi (POST request) ---
$booking_status = ''; // 'success', 'error', or ''
$booking_details = ''; // Chứa các thông tin chi tiết để hiển thị trong modal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nếu chưa đăng nhập thì chuyển về trang đăng nhập
    if (empty($_SESSION['user']['id']) && empty($_SESSION['user_id'])) {
        header('Location: /views/login.php');
        exit;
    }

    $current_user_id = $_SESSION['user']['id'] ?? $_SESSION['user_id'];

    $roomName = htmlspecialchars($_POST['room_name'] ?? '');
    $roomPrice = filter_var($_POST['room_price'] ?? '', FILTER_VALIDATE_INT);
    $fullName = htmlspecialchars($_POST['full_name'] ?? '');
    $phoneNumber = htmlspecialchars($_POST['phone_number'] ?? '');
    $checkinDate = htmlspecialchars($_POST['checkin_date'] ?? '');
    $checkoutDate = htmlspecialchars($_POST['checkout_date'] ?? '');

    // Tìm homestay_id tương ứng với roomName để lưu vào DB
    $foundHomestayId = null;
    foreach ($rooms as $r) {
        if ($r['name'] === $roomName) {
            $foundHomestayId = $r['id']; 
            break;
        }
    }

    // Lấy ngày hôm nay và ngày mai từ múi giờ trình duyệt (gửi lên form) sẽ tốt hơn, 
    // nhưng ở đây ta vẫn check phía backend bằng múi giờ server.
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));

    if (empty($roomName) || $roomPrice === false || empty($fullName) || empty($phoneNumber) || empty($checkinDate) || empty($checkoutDate) || $foundHomestayId === null) {
        $booking_status = 'error';
        $booking_details = 'Vui lòng điền đầy đủ các trường thông tin và đảm bảo chọn phòng hợp lệ.';
    } else if ($checkinDate !== $today) {
        $booking_status = 'error';
        $booking_details = 'Ngày nhận phòng (check-in) phải là hôm nay (' . date('d/m/Y') . ').';
    } else if ($checkoutDate !== $tomorrow) {
        $booking_status = 'error';
        $booking_details = 'Ngày trả phòng (check-out) phải là ngày mai (' . date('d/m/Y', strtotime('+1 day')) . ').';
    } else if (strtotime($checkinDate) >= strtotime($checkoutDate)) {
        $booking_status = 'error';
        $booking_details = 'Ngày nhận phòng phải trước ngày trả phòng.';
    } else {
        $newBookedRoom = new BookedRoom(
            null,
            $fullName,
            $phoneNumber,
            $checkinDate,
            $checkoutDate,
            $current_user_id,
            $foundHomestayId
        );
        $bookedRoomService->save($newBookedRoom);

        if ($newBookedRoom->getId()) {
            $booking_status = 'success';
            $booking_details = '
                <p><strong>Phòng:</strong> ' . $roomName . '</p>
                <p><strong>Giá:</strong> ' . number_format($roomPrice, 0, ',', '.') . 'đ</p>
                <p><strong>Họ tên:</strong> ' . $fullName . '</p>
                <p><strong>Số điện thoại:</strong> ' . $phoneNumber . '</p>
                <p><strong>Ngày nhận phòng:</strong> ' . $checkinDate . '</p>
                <p><strong>Ngày trả phòng:</strong> ' . $checkoutDate . '</p>
                <p><strong>Mã đặt phòng:</strong> ' . $newBookedRoom->getId() . '</p>
            ';
        } else {
            $booking_status = 'error';
            $booking_details = 'Đã xảy ra lỗi khi lưu đặt phòng vào hệ thống. Vui lòng thử lại.';
        }
    }
}
?>

<link rel="stylesheet" href="/assets/css/bookedroom.css">
<link rel="stylesheet" href="/assets/css/index.css">
<link rel="stylesheet" href="/assets/css/header.css">


<div class="container">
    <h1>Danh sách phòng có sẵn để đặt</h1>

    <?php if ($booking_status === 'success'): ?>
        <div id="booking-success-modal" class="success-modal">
            <div class="success-content">
                <span class="close-success-btn" onclick="closeSuccessModal()">&times;</span>
                <h2>🎉 Đặt phòng thành công! 🎉</h2>
                <div class="booking-details-summary">
                    <?= $booking_details ?>
                </div>
                <button class="ok-success-btn" onclick="closeSuccessModal()">Đóng</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($booking_status === 'error'): ?>
        <div id="booking-error-modal" class="error-modal">
            <div class="error-content">
                <span class="close-error-btn" onclick="closeErrorModal()">&times;</span>
                <h2>❌ Lỗi đặt phòng! ❌</h2>
                <div class="booking-details-summary error-details">
                    <p><?= $booking_details ?></p> </div>
                <button class="ok-error-btn" onclick="closeErrorModal()">Đóng</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="filter-bar">
        <select id="room-type">
            <option value="">Tất cả loại phòng</option>
            <option value="Standard">Standard</option>
            <option value="Deluxe">Deluxe</option>
            <option value="Suite">Suite</option>
            <option value="VIP">VIP</option>
        </select>

        <select id="price-filter">
            <option value="">Tất cả mức giá</option>
            <option value="500000">500.000đ</option>
            <option value="800000">800.000đ</option>
            <option value="1000000">1.000.000đ</option>
            <option value="1500000">1.500.000đ</option>
            <option value="2000000">2.000.000đ</option>
        </select>

        <button onclick="filterRooms()">Tìm kiếm</button>
    </div>

    <div class="room-list" id="room-list">
        <?php foreach ($rooms as $room): ?>
            <div class="room" data-price="<?= htmlspecialchars($room['price']) ?>" data-type="<?= htmlspecialchars($room['type']) ?>">
                <img src="<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['name']) ?>">
                <h3><?= htmlspecialchars($room['name']) ?></h3>
                <p>Loại: <?= htmlspecialchars($room['type']) ?></p>
                <p>Giá: <?= number_format($room['price'], 0, ',', '.') ?>đ / đêm</p>
                <button onclick="handleBookingClick('<?= htmlspecialchars($room['name']) ?>', <?= htmlspecialchars($room['price']) ?>, <?= htmlspecialchars($room['id']) ?>)">Đặt phòng</button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="booking-form" id="booking-form">
        <div class="form-content">
            <h2>Thông tin đặt phòng</h2>
            <p id="selected-room"></p>
            <form action="" method="POST">
                <input type="hidden" id="booking-room-name" name="room_name">
                <input type="hidden" id="booking-room-price" name="room_price">
                <input type="hidden" id="booking-homestay-id" name="homestay_id">

                <input type="text" name="full_name" placeholder="Họ tên" required>
                <input type="tel" name="phone_number" placeholder="Số điện thoại" required>
                <label for="checkin_date">Ngày nhận phòng:</label>
                <input type="date" id="checkin_date" name="checkin_date" required>
                <label for="checkout_date">Ngày trả phòng:</label>
                <input type="date" id="checkout_date" name="checkout_date" required>
                <button type="submit">Xác nhận đặt</button>
                <button type="button" onclick="closeBookingForm()">Hủy</button>
            </form>
        </div>
    </div>

    <hr>
</div>

<script>
    // Luôn mở form đặt phòng, không kiểm tra login phía client
    function handleBookingClick(roomName, roomPrice, homestayId) {
        openBookingForm(roomName, roomPrice, homestayId);
    }

    function openBookingForm(roomName, roomPrice, homestayId) {
        document.getElementById('selected-room').innerText = `Bạn đã chọn: ${roomName} - Giá: ${roomPrice.toLocaleString('vi-VN')}đ / đêm`;
        document.getElementById('booking-room-name').value = roomName;
        document.getElementById('booking-room-price').value = roomPrice;
        document.getElementById('booking-homestay-id').value = homestayId;
        document.getElementById('booking-form').classList.add('show');

        // Gán mặc định ngày hôm nay cho check-in, ngày mai cho check-out khi mở form
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayStr = `${yyyy}-${mm}-${dd}`;

        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const yyyy2 = tomorrow.getFullYear();
        const mm2 = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd2 = String(tomorrow.getDate()).padStart(2, '0');
        const tomorrowStr = `${yyyy2}-${mm2}-${dd2}`;

        document.getElementById('checkin_date').value = todayStr;
        document.getElementById('checkout_date').value = tomorrowStr;
    }

    function closeBookingForm() {
        document.getElementById('booking-form').classList.remove('show');
    }

    function openSuccessModal() {
        const modal = document.getElementById('booking-success-modal');
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeSuccessModal() {
        const modal = document.getElementById('booking-success-modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function openErrorModal() {
        const modal = document.getElementById('booking-error-modal');
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeErrorModal() {
        const modal = document.getElementById('booking-error-modal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    function filterRooms() {
        const roomType = document.getElementById('room-type').value;
        const priceFilter = document.getElementById('price-filter').value;
        const roomList = document.getElementById('room-list');
        const rooms = roomList.getElementsByClassName('room');

        for (let i = 0; i < rooms.length; i++) {
            const room = rooms[i];
            const roomDataType = room.getAttribute('data-type');
            const roomDataPrice = parseInt(room.getAttribute('data-price'));

            let typeMatch = (roomType === '' || roomDataType === roomType);
            let priceMatch = true;

            if (priceFilter !== '') {
                const filterPrice = parseInt(priceFilter);
                priceMatch = (roomDataPrice <= filterPrice);
            }

            if (typeMatch && priceMatch) {
                room.style.display = '';
            } else {
                room.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($booking_status === 'success'): ?>
            openSuccessModal();
        <?php elseif ($booking_status === 'error'): ?>
            openErrorModal();
        <?php endif; ?>
    });
</script>
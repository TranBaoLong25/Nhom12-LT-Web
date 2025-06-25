<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('./fragments/header.php');
require_once __DIR__ . '/../models/BookedRoom.php';
require_once __DIR__ . '/../models/HomeStay.php';
require_once __DIR__ . '/../repositories/IBookedRoomRepository.php';
require_once __DIR__ . '/../services/IBookedRoomService.php';
require_once __DIR__ . '/../repositories/IHomeStayRepository.php';
require_once __DIR__ . '/../services/IHomeStayService.php';
require_once __DIR__ . '/../repositories/BookedRoomRepository.php';
require_once __DIR__ . '/../services/BookedRoomService.php';
require_once __DIR__ . '/../repositories/HomeStayRepository.php';
require_once __DIR__ . '/../services/HomeStayService.php';
require_once __DIR__ . '/../connection.php'; 
$conn = Database::getConnection();
$bookedRoomService = new BookedRoomService(new BookedRoomRepository($conn));
$homeStayService = new HomeStayService(new HomeStayRepository($conn));

$rooms = $homeStayService->getAllHomeStay();

$booking_status = '';
$booking_details = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $homestayId = intval($_POST['homestay_id'] ?? 0);

    $today = date('Y-m-d');

    if (!$roomName || $roomPrice === false || !$fullName || !$phoneNumber || !$checkinDate || !$checkoutDate || $homestayId === 0) {
        $booking_status = 'error';
        $booking_details = 'Vui lòng điền đầy đủ thông tin và chọn phòng hợp lệ.';
    } elseif ($checkinDate < $today) {
        $booking_status = 'error';
        $booking_details = 'Ngày nhận phòng phải từ hôm nay trở đi.';
    } elseif ($checkoutDate <= $checkinDate) {
        $booking_status = 'error';
        $booking_details = 'Ngày trả phòng phải sau ngày nhận phòng.';
    } else {
        try {
            $newBookedRoom = new BookedRoom(
                null,
                $fullName,
                $phoneNumber,
                $checkinDate,
                $checkoutDate,
                $current_user_id,
                $homestayId
            );
            $bookedRoomService->save($newBookedRoom);

            $booking_status = 'success';
            $booking_details = "
                <p><strong>Phòng:</strong> {$roomName}</p>
                <p><strong>Giá:</strong> " . number_format($roomPrice, 0, ',', '.') . "đ</p>
                <p><strong>Khách:</strong> {$fullName}</p>
                <p><strong>SĐT:</strong> {$phoneNumber}</p>
                <p><strong>Nhận phòng:</strong> {$checkinDate}</p>
                <p><strong>Trả phòng:</strong> {$checkoutDate}</p>
            ";
        } catch (Exception $e) {
            $booking_status = 'error';
            $booking_details = $e->getMessage();
        }
    }
}
?>

<link rel="stylesheet" href="/assets/css/bookedroom.css">
<link rel="stylesheet" href="/assets/css/header.css">

<div class="container">
    <h1>Đặt phòng Homestay</h1>
    <div class="filter-bar">
    <select id="room-type">
        <option value="">Tất cả loại phòng</option>
        <option value="standardRoom">StandardRoom</option>
        <option value="DeluxeRoom">DeluxeRoom</option>
        <option value="SuiteRoom">SuiteRoom</option>
        <option value="DormitoryRoom">DormitoryRoom</option>
        <option value="Bungalow">Bungalow</option>
    </select>
    <select id="location-filter">
        <option value="">Tất cả địa điểm</option>
        <option value="1st">Tầng 1</option>
        <option value="2st">Tầng 2</option>
        <option value="3st">Tầng 3</option>
        <option value="4st">Tầng 4</option> 
    </select>

    <select id="price-filter">
        <option value="">Tất cả mức giá</option>
        <option value="500000">Dưới 500.000đ</option>
        <option value="1000000">Dưới 1.000.000đ</option>
        <option value="1500000">Dưới 1.500.000đ</option>
        <option value="2000000">Dưới 2.000.000đ</option>
    </select>

    <button onclick="filterRooms()">Lọc phòng</button>
</div>

    <div class="room-list" id="room-list">
    <?php foreach ($rooms as $room): ?>
        <?php    
                $imageUrls = $room->getImage();
                $imageUrl = !empty($imageUrls) ? "/{$imageUrls[0]}" : '/assets/images/img1.jpg';

        ?>
        <div class="room-item" 
            data-type="<?= htmlspecialchars($room->getRoomType()) ?>" 
            data-price="<?= htmlspecialchars($room->getRoomPrice()) ?>"
            data-location="<?= htmlspecialchars($room->getLocation()) ?>">
            <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($room->getRoomType()) ?>">
            <h3><?= htmlspecialchars($room->getRoomType()) ?></h3> 
            <p>Địa điểm: <?= htmlspecialchars($room->getLocation()) ?></p>
            <p>Giá: <?= number_format($room->getRoomPrice(), 0, ',', '.') ?>đ / đêm</p>
            <button onclick="openBookingForm(`<?= addslashes($room->getRoomType()) ?>`, <?= $room->getRoomPrice() ?>, <?= $room->getId() ?>)">Đặt phòng</button>
        </div>
    <?php endforeach; ?>
</div>

</div>

<!-- Booking Form Modal -->
<div class="booking-form" id="booking-form">
    <div class="form-content">
        <h2>Thông tin đặt phòng</h2>
        <form method="POST">
            <input type="hidden" name="room_name" id="room_name">
            <input type="hidden" name="room_price" id="room_price">
            <input type="hidden" name="homestay_id" id="homestay_id">

            <label>Họ tên:</label>
            <input type="text" name="full_name" required>

            <label>Số điện thoại:</label>
            <input type="tel" name="phone_number" id="phone_number" required pattern="^0[0-9]{9}$" maxlength="10" title="SĐT phải bắt đầu bằng số 0 và có 10 chữ số" oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <label>Ngày nhận phòng:</label>
            <input type="date" name="checkin_date" id="checkin_date" required>

            <label>Ngày trả phòng:</label>
            <input type="date" name="checkout_date" id="checkout_date" required>

            <button type="submit">Xác nhận đặt</button>
            <button type="button" onclick="closeBookingForm()">Hủy</button>
        </form>
    </div>
</div>

<!-- Modal Thành Công / Lỗi -->
<?php if ($booking_status === 'success'): ?>
    <div class="success-modal show">
        <div class="success-content">
            <span class="close-success-btn" onclick="closeSuccessModal()">&times;</span>
            <h2>🎉 Đặt phòng thành công!</h2>
            <div class="booking-details-summary"><?= $booking_details ?></div>
            <button class="ok-success-btn" onclick="closeSuccessModal()">OK</button>
        </div>
    </div>
<?php elseif ($booking_status === 'error'): ?>
    <div class="error-modal show">
        <div class="error-content">
            <span class="close-error-btn" onclick="closeErrorModal()">&times;</span>
            <h2>❌ Lỗi đặt phòng</h2>
            <div class="booking-details-summary"><?= $booking_details ?></div>
            <button class="ok-error-btn" onclick="closeErrorModal()">OK</button>
        </div>
    </div>
<?php endif; ?>

<script>
function openBookingForm(name, price, id) {
    document.getElementById('room_name').value = name;
    document.getElementById('room_price').value = price;
    document.getElementById('homestay_id').value = id;

    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);

    document.getElementById('checkin_date').value = today.toISOString().split('T')[0];
    document.getElementById('checkout_date').value = tomorrow.toISOString().split('T')[0];

    document.getElementById('booking-form').classList.add('show');
}

function closeBookingForm() {
    document.getElementById('booking-form').classList.remove('show');
}

function closeSuccessModal() {
    document.querySelector('.success-modal')?.classList.remove('show');
}

function closeErrorModal() {
    document.querySelector('.error-modal')?.classList.remove('show');
}
function filterRooms() {
    const roomType = document.getElementById('room-type').value;
    const priceFilter = parseInt(document.getElementById('price-filter').value);
    const locationFilter = document.getElementById('location-filter').value;
    
    const roomList = document.getElementById('room-list');
    const rooms = roomList.getElementsByClassName('room-item');

    for (let i = 0; i < rooms.length; i++) {
        const room = rooms[i];
        const type = room.getAttribute('data-type');
        const price = parseInt(room.getAttribute('data-price'));
        const location = room.getAttribute('data-location');

        const matchType = roomType === '' || type === roomType;
        const matchPrice = isNaN(priceFilter) || price <= priceFilter;
        const matchLocation = locationFilter === '' || location === locationFilter;

        if (matchType && matchPrice && matchLocation) {
            room.style.display = '';
        } else {
            room.style.display = 'none';
        }
    }
}


</script>

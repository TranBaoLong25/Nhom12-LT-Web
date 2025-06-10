<?php

include_once('./fragments/header.php');

$rooms = [
    [
        'id' => 1,
        'name' => 'Phòng Deluxe',
        'type' => 'deluxe',
        'price' => 800000,
        'image' => '/assets/images/img1.jpg',
        'description' => 'Phòng Deluxe tiện nghi và sang trọng.'
    ],
    [
        'id' => 2,
        'name' => 'Phòng Standard',
        'type' => 'standard',
        'price' => 500000,
        'image' => '/assets/images/img2.jpg',
        'description' => 'Phòng Standard thoải mái và giá cả phải chăng.'
    ],
    [
        'id' => 3,
        'name' => 'Phòng Suite',
        'type' => 'suite',
        'price' => 1000000,
        'image' => '/assets/images/img3.jpg',
        'description' => 'Phòng Suite cao cấp với không gian rộng rãi.'
    ],
    [
        'id' => 4,
        'name' => 'Phòng VIP',
        'type' => 'vip',
        'price' => 1500000,
        'image' => '/assets/images/img4.jpg',
        'description' => 'Phòng VIP đẳng cấp với dịch vụ đặc biệt.'
    ],
    [
        'id' => 5,
        'name' => 'Phòng Tổng Thống',
        'type' => 'vip',
        'price' => 2000000,
        'image' => '/assets/images/img5.jpg',
        'description' => 'Phòng Tổng Thống sang trọng nhất.'
    ],
    [
        'id' => 6,
        'name' => 'Phòng Thượng hạng',
        'type' => 'deluxe',
        'price' => 1200000,
        'image' => '/assets/images/img6.jpg',
        'description' => 'Phòng Thượng hạng với ban công đẹp.'
    ],
];

// --- Logic xử lý đặt phòng ---
$booking_status = ''; // 'success', 'error', or ''
$booking_details = ''; // Chứa các thông tin chi tiết để hiển thị trong modal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomName = htmlspecialchars($_POST['room_name'] ?? '');
    $roomPrice = filter_var($_POST['room_price'] ?? '', FILTER_VALIDATE_INT);
    $fullName = htmlspecialchars($_POST['full_name'] ?? '');
    $phoneNumber = htmlspecialchars($_POST['phone_number'] ?? '');
    $checkinDate = htmlspecialchars($_POST['checkin_date'] ?? '');
    $checkoutDate = htmlspecialchars($_POST['checkout_date'] ?? '');

    if (empty($roomName) || $roomPrice === false || empty($fullName) || empty($phoneNumber) || empty($checkinDate) || empty($checkoutDate)) {
        $booking_status = 'error';
        $booking_details = 'Vui lòng điền đầy đủ các trường thông tin.';
    } else if (strtotime($checkinDate) >= strtotime($checkoutDate)) {
        $booking_status = 'error';
        $booking_details = 'Ngày nhận phòng phải trước ngày trả phòng.';
    } else {
        $booking_status = 'success';
        $booking_details = '
            <p><strong>Phòng:</strong> ' . $roomName . '</p>
            <p><strong>Giá:</strong> ' . number_format($roomPrice, 0, ',', '.') . 'đ</p>
            <p><strong>Họ tên:</strong> ' . $fullName . '</p>
            <p><strong>Số điện thoại:</strong> ' . $phoneNumber . '</p>
            <p><strong>Ngày nhận phòng:</strong> ' . $checkinDate . '</p>
            <p><strong>Ngày trả phòng:</strong> ' . $checkoutDate . '</p>
        ';
    }
}
?>

<link rel="stylesheet" href="/assets/css/bookedroom.css">
<link rel="stylesheet" href="/assets/css/index.css">
<link rel="stylesheet" href="/assets/css/header.css">

<div class="container">
    <h1>Danh sách phòng</h1>

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
            <option value="standard">Standard</option>
            <option value="deluxe">Deluxe</option>
            <option value="suite">Suite</option>
            <option value="vip">VIP</option>
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
                <button onclick="openBookingForm('<?= htmlspecialchars($room['name']) ?>', <?= htmlspecialchars($room['price']) ?>)">Đặt phòng</button>
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
</div>

<script>
    function openBookingForm(roomName, roomPrice) {
        document.getElementById('selected-room').innerText = `Bạn đã chọn: ${roomName} - Giá: ${roomPrice.toLocaleString('vi-VN')}đ / đêm`;
        document.getElementById('booking-room-name').value = roomName;
        document.getElementById('booking-room-price').value = roomPrice;
        document.getElementById('booking-form').classList.add('show');
    }

    function closeBookingForm() {
        document.getElementById('booking-form').classList.remove('show');
    }

    // Các hàm mới cho modal thông báo
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

    // Logic để hiển thị modal ngay khi trang tải lại nếu có thông báo
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($booking_status === 'success'): ?>
            openSuccessModal();
        <?php elseif ($booking_status === 'error'): ?>
            openErrorModal();
            // Nếu có lỗi và bạn muốn giữ form đặt phòng hiện ra, hãy thêm dòng này
            // document.getElementById('booking-form').classList.add('show');
        <?php endif; ?>
    });

   
    
</script>

<?php

include_once('./fragments/footer.php');
?>
<?php
// Include phần header của trang
include_once('./fragments/header.php');
$rooms = [
    [
        'id' => 1,
        'name' => 'Phòng Deluxe',
        'type' => 'deluxe',
        'price' => 800000,
        'image' => '/Project_PHP/assets/images/img1.jpg', // Sửa đường dẫn và tên file ảnh
        'description' => 'Phòng Deluxe tiện nghi và sang trọng.'
    ],
    [
        'id' => 2,
        'name' => 'Phòng Standard',
        'type' => 'standard',
        'price' => 500000,
        'image' => '/Project_PHP/assets/images/img2.jpg', // Sửa đường dẫn và tên file ảnh
        'description' => 'Phòng Standard thoải mái và giá cả phải chăng.'
    ],
    [
        'id' => 3,
        'name' => 'Phòng Suite',
        'type' => 'suite',
        'price' => 1000000,
        'image' => '/Project_PHP/assets/images/img3.jpg', // Sửa đường dẫn và tên file ảnh
        'description' => 'Phòng Suite cao cấp với không gian rộng rãi.'
    ],
    [
        'id' => 4,
        'name' => 'Phòng VIP',
        'type' => 'vip',
        'price' => 1500000,
        'image' => '/Project_PHP/assets/images/img4.jpg', // Sửa đường dẫn và tên file ảnh
        'description' => 'Phòng VIP đẳng cấp với dịch vụ đặc biệt.'
    ],
    [
        'id' => 5,
        'name' => 'Phòng Tổng Thống',
        'type' => 'vip',
        'price' => 2000000,
        'image' => '/Project_PHP/assets/images/img5.jpg', // Sửa đường dẫn và tên file ảnh
        'description' => 'Phòng Tổng Thống sang trọng nhất.'
    ],
    // Nếu bạn có img6.jpg, thêm một phòng nữa:
    [
        'id' => 6,
        'name' => 'Phòng Thượng hạng', // Ví dụ tên mới
        'type' => 'deluxe', // Ví dụ loại mới
        'price' => 1200000,
        'image' => '/Project_PHP/assets/images/img6.jpg', // Sửa đường dẫn và tên file ảnh
        'description' => 'Phòng Thượng hạng với ban công đẹp.'
    ],
];

// --- Bắt đầu: Logic xử lý đặt phòng (Được di chuyển từ process_booking.php) ---
$booking_message = ''; // Biến để lưu trữ thông báo đặt phòng

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Làm sạch và xác thực đầu vào
    $roomName = htmlspecialchars($_POST['room_name'] ?? '');
    $roomPrice = filter_var($_POST['room_price'] ?? '', FILTER_VALIDATE_INT);
    $fullName = htmlspecialchars($_POST['full_name'] ?? '');
    $phoneNumber = htmlspecialchars($_POST['phone_number'] ?? '');
    $checkinDate = htmlspecialchars($_POST['checkin_date'] ?? '');
    $checkoutDate = htmlspecialchars($_POST['checkout_date'] ?? '');

    // Xác thực cơ bản
    if (empty($roomName) || $roomPrice === false || empty($fullName) || empty($phoneNumber) || empty($checkinDate) || empty($checkoutDate)) {
        $booking_message = '<p style="color: red;">Lỗi: Vui lòng điền đầy đủ các trường thông tin.</p>';
    }
    // Xác thực thêm cho ngày (ví dụ: ngày nhận phòng phải trước ngày trả phòng)
    else if (strtotime($checkinDate) >= strtotime($checkoutDate)) {
        $booking_message = '<p style="color: red;">Lỗi: Ngày nhận phòng phải trước ngày trả phòng.</p>';
    }
    else {
 
        $booking_message = '
            <p style="color: green;">Đặt phòng thành công!</p>
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
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Phòng</title>
    <link rel="stylesheet" href="/Project_PHP//assets//css//bookedroom.css">
     <link rel="stylesheet" href="/Project_PHP//assets//css//index.css">
    <style>
        /* CSS cơ bản cho overlay biểu mẫu đặt phòng */
        .booking-form {
            display: none; /* Ẩn theo mặc định */
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .booking-form.show {
            display: flex; /* Hiển thị khi có class 'show' */
        }

        .booking-form .form-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        @keyframes animatetop {
            from {top:-300px; opacity:0}
            to {top:0; opacity:1}
        }

        /* Styling cho thông báo đặt phòng */
        .booking-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f0f0f0;
            text-align: center;
            border: 1px solid #ccc;
        }
        .booking-message p {
            margin: 5px 0;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách phòng</h1>

        <?php if (!empty($booking_message)): ?>
            <div class="booking-message">
                <?= $booking_message ?>
                <button onclick="hideBookingMessage()" style="margin-top: 10px;">Đóng</button>
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
                <form action="" method="POST"> <input type="hidden" id="booking-room-name" name="room_name">
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
            // Thêm class 'show' để hiển thị form
            document.getElementById('booking-form').classList.add('show');
        }

        function closeBookingForm() {
            // Loại bỏ class 'show' để ẩn form
            document.getElementById('booking-form').classList.remove('show');
        }

        function hideBookingMessage() {
            const messageDiv = document.querySelector('.booking-message');
            if (messageDiv) {
                messageDiv.style.display = 'none';
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
                    // Hiển thị các phòng có giá nhỏ hơn hoặc bằng giá lọc
                    priceMatch = (roomDataPrice <= filterPrice);
                }

                if (typeMatch && priceMatch) {
                    room.style.display = ''; // Hiển thị phòng
                } else {
                    room.style.display = 'none'; // Ẩn phòng
                }
            }
        }


        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($booking_message) && strpos($booking_message, 'Lỗi:') !== false): ?>
            document.getElementById('booking-form').classList.add('show');
            // Bạn có thể điền lại dữ liệu form tại đây nếu cần
            // document.getElementById('booking-room-name').value = '<?= htmlspecialchars($roomName) ?>';
            // ... và các trường khác
        <?php endif; ?>

    </script>
</body>
</html>
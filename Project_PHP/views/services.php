<?php

include_once('./fragments/header.php');

$services = [
    [
        'id' => 101,
        'name' => 'Massage thư giãn',
        'price' => 300000,
        'image' => '/assets/images/service1.jpg',
        'description' => 'Dịch vụ massage toàn thân giúp thư giãn cơ bắp.'
    ],
    [
        'id' => 102,
        'name' => 'Ăn sáng tại phòng',
        'price' => 150000,
        'image' => '/assets/images/service2.jpg',
        'description' => 'Thưởng thức bữa sáng ngay tại phòng của bạn.'
    ],
    [
        'id' => 103,
        'name' => 'Đưa đón sân bay',
        'price' => 250000,
        'image' => '/assets/images/service3.jpg',
        'description' => 'Dịch vụ đưa đón từ/đến sân bay tiện lợi.'
    ],
    [
        'id' => 104,
        'name' => 'Fitness Center',
        'price' => 0, // Miễn phí
        'image' => '/assets/images/service4.jpg',
        'description' => 'Phòng tập gym hiện đại.'
    ],
    [
        'id' => 105,
        'name' => 'Thuê xe đạp',
        'price' => 80000,
        'image' => '/assets/images/service5.jpg',
        'description' => 'Thuê xe đạp khám phá khu vực xung quanh.'
    ],
    [
        'id' => 106,
        'name' => 'Giặt là',
        'price' => 100000,
        'image' => '/assets/images/service6.jpg',
        'description' => 'Dịch vụ giặt là nhanh chóng và tiện lợi.'
    ],
];

// --- Logic xử lý đặt dịch vụ ---
$booking_status = ''; // 'success', 'error', or ''
$booking_details = ''; // Chứa các thông tin chi tiết để hiển thị trong modal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý đặt dịch vụ
    $serviceName = htmlspecialchars($_POST['service_name'] ?? '');
    $servicePrice = filter_var($_POST['service_price'] ?? '', FILTER_VALIDATE_INT);
    $serviceId = filter_var($_POST['service_id'] ?? '', FILTER_VALIDATE_INT); // Lấy service_id từ form
    $serviceBookingDate = htmlspecialchars($_POST['service_booking_date'] ?? '');
    $userId = filter_var($_POST['user_id'] ?? '', FILTER_VALIDATE_INT); // Giả định user_id được gửi từ form

    // Kiểm tra dữ liệu hợp lệ
    if (empty($serviceName) || $servicePrice === false || $serviceId === false || $serviceId <= 0 || empty($serviceBookingDate) || $userId === false || $userId <= 0) {
        $booking_status = 'error';
        $booking_details = 'Vui lòng điền đầy đủ và chính xác các trường thông tin đặt dịch vụ (Tên dịch vụ, Giá, Ngày, User ID).';
    } else {
        // Đây là nơi bạn sẽ gọi Service Layer để lưu thông tin đặt dịch vụ vào DB
        // Ví dụ:
        // require_once __DIR__ . '/../services/BookedServiceService.php'; // Giả sử có một Service riêng cho việc đặt dịch vụ
// require_once __DIR__ . '/../repositories/BookedServiceRepository.php';
        // $bookedServiceRepository = new BookedServiceRepository();
        // $bookedServiceService = new BookedServiceService($bookedServiceRepository);
        //
        // $newBookedService = $bookedServiceService->bookService(
        //     $serviceName,
        //     $servicePrice,
        //     $serviceBookingDate,
        //     $userId,
        //     $serviceId // Thêm serviceId vào đây nếu cần lưu trữ chi tiết
        // );
        //
        // if ($newBookedService) {
        //     $booking_status = 'success';
        //     $booking_details = '
        //         <p><strong>Dịch vụ:</strong> ' . $newBookedService->getName() . '</p>
        //         <p><strong>Giá:</strong> ' . number_format($newBookedService->getPrice(), 0, ',', '.') . 'đ</p>
        //         <p><strong>Ngày đặt:</strong> ' . $newBookedService->getBookingDate() . '</p>
        //         <p><strong>User ID:</strong> ' . $newBookedService->getUserId() . '</p>
        //         <p><strong>Mã đặt dịch vụ:</strong> ' . $newBookedService->getId() . '</p>
        //     ';
        // } else {
        //     $booking_status = 'error';
        //     $booking_details = 'Đã xảy ra lỗi khi lưu thông tin đặt dịch vụ. Vui lòng thử lại.';
        // }

        // Hiện tại, chúng ta chỉ giả lập thành công
        $booking_status = 'success';
        $booking_details = '
            <p><strong>Dịch vụ:</strong> ' . $serviceName . '</p>
            <p><strong>Giá:</strong> ' . number_format($servicePrice, 0, ',', '.') . 'đ</p>
            <p><strong>Ngày đặt:</strong> ' . $serviceBookingDate . '</p>
            <p><strong>User ID:</strong> ' . $userId . '</p>
            <p><strong>Mã dịch vụ:</strong> ' . $serviceId . '</p>
        ';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ Khách sạn</title>
    <link rel="stylesheet" href="/assets/css/service.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/header.css">
</head>
<body>
<div class="container">
    <h1>Danh sách dịch vụ</h1>

    <?php if ($booking_status === 'success'): ?>
        <div id="booking-success-modal" class="success-modal">
            <div class="success-content">
                <span class="close-btn" onclick="closeSuccessModal()">&times;</span>
                <h2>🎉 Đặt dịch vụ thành công! 🎉</h2>
                <div class="booking-details-summary">
                    <?= $booking_details ?>
                </div>
                <button class="ok-btn" onclick="closeSuccessModal()">Đóng</button>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($booking_status === 'error'): ?>
        <div id="booking-error-modal" class="error-modal">
            <div class="error-content">
                <span class="close-btn" onclick="closeErrorModal()">&times;</span>
                <h2>❌ Lỗi đặt dịch vụ! ❌</h2>
                <div class="booking-details-summary error-details">
                    <p><?= $booking_details ?></p>
                </div>
                <button class="ok-btn" onclick="closeErrorModal()">Đóng</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="service-list" id="service-list">
        <?php foreach ($services as $service): ?>
            <div class="service" data-price="<?= htmlspecialchars($service['price']) ?>" data-id="<?= htmlspecialchars($service['id']) ?>">
                <img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>">
                <h3><?= htmlspecialchars($service['name']) ?></h3>
                <p><?= htmlspecialchars($service['description']) ?></p>
                <p>Giá: <?= number_format($service['price'], 0, ',', '.') ?>đ</p>
                <button onclick="openBookingForm('<?= htmlspecialchars($service['name']) ?>', <?= htmlspecialchars($service['price']) ?>, <?= htmlspecialchars($service['id']) ?>)">Đặt dịch vụ</button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="booking-form" id="booking-form">
        <div class="form-content">
            <span class="close-btn" onclick="closeBookingForm()">&times;</span> <h2>Thông tin đặt dịch vụ</h2>
            <p id="selected-service"></p>

            <form action="" method="POST">
                <input type="hidden" id="booking-service-name" name="service_name">
                <input type="hidden" id="booking-service-price" name="service_price">
                <input type="hidden" id="booking-service-id" name="service_id"> <label for="service_booking_date">Ngày đặt:</label>
                <input type="date" id="service_booking_date" name="service_booking_date" required>

                <label for="user_id">User ID:</label>
                <input type="number" id="user_id" name="user_id" min="1" placeholder="Nhập User ID của bạn" required>

                <button type="submit">Xác nhận đặt</button>
                <button type="button" onclick="closeBookingForm()">Hủy</button>
            </form>
        </div>
    </div>  
</div>

<script src="/assets/js/service.js"></script>
<script>
    // Kiểm tra trạng thái đặt dịch vụ sau khi load trang để hiển thị modal
    window.onload = function() {
        <?php if ($booking_status === 'success'): ?>
            openSuccessModal();
        <?php elseif ($booking_status === 'error'): ?>
            openErrorModal();
        <?php endif; ?>
    };
</script>
</body>
</html>
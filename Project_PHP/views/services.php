<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once('./fragments/header.php');
require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../repositories/IServiceRepository.php';
require_once __DIR__ . '/../repositories/ServiceRepository.php';
require_once __DIR__ . '/../services/IServiceService.php';
require_once __DIR__ . '/../services/ServiceService.php';

require_once __DIR__ . '/../models/BookedService.php';
require_once __DIR__ . '/../repositories/IBookedServiceRepository.php';
require_once __DIR__ . '/../repositories/BookedServiceRepository.php';
require_once __DIR__ . '/../services/IBookedServiceService.php';
require_once __DIR__ . '/../services/BookedServiceService.php';

require_once __DIR__ . '/../connection.php';

// Kết nối DB và tạo service
$conn = Database::getConnection();
$serviceRepository = new ServiceRepository($conn);
$serviceService = new ServiceService($serviceRepository);
$services = $serviceService->findAll(); 

// Tạo repo/service cho BookedService
$bookedServiceRepository = new BookedServiceRepository($conn);
$bookedServiceService = new BookedServiceService($bookedServiceRepository);

$booking_status = '';
$booking_details = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['user']['id']) && empty($_SESSION['user_id'])) {
        header('Location: /views/login.php');
        exit;
    }
    $current_user_id = $_SESSION['user']['id'] ?? $_SESSION['user_id'];

    $serviceId = filter_var($_POST['service_id'] ?? '', FILTER_VALIDATE_INT);
    $bookingDate = htmlspecialchars($_POST['service_booking_date'] ?? '');

    // Validate dữ liệu
    $foundService = null;
    foreach ($services as $s) {
        if ($s->getId() == $serviceId) {
            $foundService = $s;
            break;
        }
    }

    if (!$foundService || !$bookingDate) {
        $booking_status = 'error';
        $booking_details = 'Vui lòng chọn dịch vụ và ngày đặt hợp lệ.';
    } else {
$bookedService = new BookedService(
    null,              
    $bookingDate,      
    $current_user_id,  
    $serviceId         
);
        $saveResult = $bookedServiceService->save($bookedService);

        if ($saveResult) {
            $booking_status = 'success';
            $booking_details = '
                <p><strong>Dịch vụ:</strong> ' . htmlspecialchars($foundService->getServiceName()) . '</p>
                <p><strong>Giá:</strong> ' . number_format($foundService->getServicePrice(), 0, ',', '.') . 'đ</p>
                <p><strong>Ngày đặt:</strong> ' . htmlspecialchars($bookingDate) . '</p>
            ';
        } else {
            $booking_status = 'error';
            $booking_details = 'Có lỗi khi lưu dữ liệu vào hệ thống!';
        }
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
            <div class="service" data-price="<?= htmlspecialchars($service->getServicePrice()) ?>" data-id="<?= htmlspecialchars($service->getId()) ?>">
                <img src="/assets/images/service<?= htmlspecialchars($service->getId()) ?>.jpg" alt="<?= htmlspecialchars($service->getServiceName()) ?>">
                <h3><?= htmlspecialchars($service->getServiceName()) ?></h3>
                <p><?= htmlspecialchars($service->getServiceDescription()) ?></p>
                <p>Giá: <?= number_format($service->getServicePrice(), 0, ',', '.') ?>đ</p>
                <button onclick="openBookingForm('<?= htmlspecialchars($service->getServiceName()) ?>', <?= htmlspecialchars($service->getServicePrice()) ?>, <?= htmlspecialchars($service->getId()) ?>)">Đặt dịch vụ</button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="booking-form" id="booking-form">
        <div class="form-content">
            <span class="close-btn" onclick="closeBookingForm()">&times;</span>
            <h2>Thông tin đặt dịch vụ</h2>
            <p id="selected-service"></p>
            <form action="" method="POST">
                <input type="hidden" id="booking-service-name" name="service_name">
                <input type="hidden" id="booking-service-price" name="service_price">
                <input type="hidden" id="booking-service-id" name="service_id">
                <label for="service_booking_date">Ngày đặt:</label>
                <input type="date" id="service_booking_date" name="service_booking_date" required>
                <button type="submit">Xác nhận đặt</button>
                <button type="button" onclick="closeBookingForm()">Hủy</button>
            </form>
        </div>
    </div>
</div>

<script>
function openBookingForm(serviceName, servicePrice, serviceId) {
    document.getElementById('selected-service').innerText = `Bạn đã chọn: ${serviceName} - Giá: ${servicePrice.toLocaleString('vi-VN')}đ`;
    document.getElementById('booking-service-name').value = serviceName;
    document.getElementById('booking-service-price').value = servicePrice;
    document.getElementById('booking-service-id').value = serviceId;
    document.getElementById('booking-form').classList.add('show');
}
function closeBookingForm() {
    document.getElementById('booking-form').classList.remove('show');
}
function openSuccessModal() {
    document.getElementById('booking-success-modal').classList.add('show');
}
function closeSuccessModal() {
    document.getElementById('booking-success-modal').classList.remove('show');
}
function openErrorModal() {
    document.getElementById('booking-error-modal').classList.add('show');
}
function closeErrorModal() {
    document.getElementById('booking-error-modal').classList.remove('show');
}
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
<?php include_once('./fragments/footer.php'); ?>
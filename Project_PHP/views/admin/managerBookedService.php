<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.php');
    exit;
}
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../models/BookedService.php');
require_once(__DIR__ . '/../../models/Service.php');
require_once(__DIR__ . '/../../repositories/IBookedServiceRepository.php');
require_once(__DIR__ . '/../../repositories/IServiceRepository.php');
require_once(__DIR__ . '/../../repositories/BookedServiceRepository.php');
require_once(__DIR__ . '/../../repositories/ServiceRepository.php');
require_once(__DIR__ . '/../../services/IBookedServiceService.php');
require_once(__DIR__ . '/../../services/BookedServiceService.php');
require_once(__DIR__ . '/../../services/IServiceService.php');
require_once(__DIR__ . '/../../services/ServiceService.php');

$conn = Database::getConnection();
$bookedServiceRepository = new BookedServiceRepository($conn);
$bookedServiceService = new BookedServiceService($bookedServiceRepository);
$services = (new ServiceService(new ServiceRepository($conn)))->getAllServices();

$serviceMap = [];
foreach ($services as $s) {
    $serviceMap[$s->getId()] = $s->getServiceName();
}

$bookedServices = $bookedServices ?? $bookedServiceService->getAllBookedServices();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý dịch vụ đã đặt</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        nav a { margin-right: 10px; text-decoration: none; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        .form-section { margin-top: 30px; }
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
        <input type="hidden" name="controller" value="managerbookedService">
        <input type="hidden" name="action" value="searchBookedService">
        <input type="text" name="keyword" placeholder="Tìm theo ID người dùng">
        <button type="submit">Tìm kiếm</button>
    </form>

    <h2>Danh sách dịch vụ đã đặt</h2>
    <?php if (empty($bookedServices)): ?>
        <p>Không tìm thấy dịch vụ!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Thời gian</th>
                    <th>User ID</th>
                    <th>ID dịch vụ</th>
                    <th>Dịch vụ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookedServices as $bs): ?>
                    <tr>
                        <td><?= htmlspecialchars($bs->getId()) ?></td>
                        <td><?= htmlspecialchars($bs->getTime()) ?></td>
                        <td><?= htmlspecialchars($bs->getUserId()) ?></td>
                        <td><?= htmlspecialchars($bs->getServiceId()) ?></td>
                        <td><?= htmlspecialchars($serviceMap[$bs->getServiceId()] ?? 'Không rõ') ?></td>
                        <td>
                            <a href="/index.php?controller=managerbookedService&action=editBookedService&id=<?= $bs->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerbookedService&action=deleteBookedService&id=<?= $bs->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="form-section">
        <?php if (isset($editBookedService)) { ?>
            <h3>Sửa dịch vụ đã đặt</h3>
            <form action="/index.php?controller=managerbookedService&action=updateBookedService" method="POST">
                <input type="hidden" name="id" value="<?= $editBookedService->getId() ?>">
                <input type="date" name="time" value="<?= $editBookedService->getTime() ?>" required>
                <input type="text" name="user_id" value="<?= $editBookedService->getUserId() ?>" required>
                <select name="service_id" required>
                    <?php foreach ($services as $s): ?>
                        <option value="<?= $s->getId() ?>" <?= $editBookedService->getServiceId() == $s->getId() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s->getServiceName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Cập nhật</button>
            </form>
        <?php } else { ?>
            <h3>Thêm dịch vụ đã đặt</h3>
            <form action="/index.php?controller=managerbookedService&action=save" method="POST">
                <input type="date" name="time" placeholder="Thời gian" required>
                <input type="text" name="user_id" placeholder="ID người dùng" required>
                <select name="service_id" required>
                    <?php foreach ($services as $s): ?>
                        <option value="<?= $s->getId() ?>"><?= htmlspecialchars($s->getServiceName()) ?></option>
                    <?php endforeach; ?>    
                </select>
                <button type="submit">Thêm</button>
            </form>
        <?php } ?>
    </div>
</div>
</body>
</html>

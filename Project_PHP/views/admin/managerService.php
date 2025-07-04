<?php
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
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../models/Service.php');
require_once(__DIR__ . '/../../repositories/IServiceRepository.php');
require_once(__DIR__ . '/../../repositories/ServiceRepository.php');
require_once(__DIR__ . '/../../services/IServiceService.php');
require_once(__DIR__ . '/../../services/ServiceService.php');

$conn = Database::getConnection();
$serviceRepository = new ServiceRepository($conn);
$serviceService = new ServiceService($serviceRepository);

$services = $services ?? $serviceService->getAllServices();
$editService = $editService ?? null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý dịch vụ</title>
    <link rel="stylesheet" href="/assets/css/Manager.css">

</head>
<body>
<nav style="text-align: center;">
    <a href="/">Trang Chủ</a>
    <a href="/views/admin/managerUser.php">User</a>
    <a href="/views/admin/managerService.php">Service</a>
    <a href="/views/admin/managerHomestay.php">Homestay</a>
    <a href="/views/admin/managerBookedRoom.php">BookedRoom</a>
    <a href="/views/admin/managerBookedService.php">BookedService</a>
</nav>
    
<div class="container">
    <form method="GET" action="/index.php">
        <input type="hidden" name="controller" value="managerService">
        <input type="hidden" name="action" value="search">
        <input type="text" name="keyword" placeholder="Tìm theo tên dịch vụ">
        <button type="submit">Tìm kiếm</button>
    </form>

    <h2 style="text-align: center;">Danh sách dịch vụ</h2>
    <?php if (empty($services)): ?>
        <p>Không tìm thấy dịch vụ!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên dịch vụ</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service->getId()) ?></td>
                        <td><?= htmlspecialchars($service->getServiceName()) ?></td>
                        <td><?= htmlspecialchars($service->getServiceDescription()) ?></td>
                        <td><?= htmlspecialchars($service->getServicePrice()) ?></td>
                        <td>
                            <a href="/index.php?controller=managerService&action=edit&id=<?= $service->getId() ?>">Sửa</a> |
                            <a href="/index.php?controller=managerService&action=delete&id=<?= $service->getId() ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
                    
    <p style="text-align: center;"><a href="/views/admin/managerService.php">Thêm dịch vụ</a></p>
    <div class="service-form">
        <?php if ($editService): ?>
            <h3>Sửa dịch vụ</h3>
            <form action="/index.php?controller=managerService&action=update" method="POST">
                <input type="hidden" name="id" value="<?= $editService->getId() ?>">
                <input type="text" name="service_name" value="<?= $editService->getServiceName() ?>" required>
                <textarea name="service_description" required><?= $editService->getServiceDescription() ?></textarea>
                <input type="number" name="service_price" value="<?= $editService->getServicePrice() ?>" required>
                <button style="align-self: center;" type="submit">Cập nhật</button>
            </form>
        <?php else: ?>
            <h3>Thêm dịch vụ</h3>
            <form action="/index.php?controller=managerService&action=save" method="POST">
                <input type="text" name="service_name" placeholder="Tên dịch vụ" required>
                <textarea name="service_description" placeholder="Mô tả dịch vụ" required></textarea>
                <input type="number" name="service_price" placeholder="Giá" required>
                <button style="align-self: center;" type="submit">Thêm</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

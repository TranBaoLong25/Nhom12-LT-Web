<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.php');
    exit;
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
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        padding: 30px 0 0 0;
        margin: 0;
        background: #f4f8fb;
        min-height: 100vh;
    }
    nav {
        background: #0066cc;
        padding: 15px 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    nav a {
        color: #fff;
        margin-right: 18px;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 6px;
        transition: background 0.2s;
    }
    nav a:hover, nav a:focus {
        background: #0050a7;
        color: #fff;
    }
    .container {
        max-width: 950px;
        margin: auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 30px 40px 40px 40px;
    }
    h2, h3 {
        color: #055290;
        margin-bottom: 16px;
        letter-spacing: 0.5px;
    }
    form {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    form input[type="text"], form input[type="number"], form textarea {
        padding: 7px 12px;
        font-size: 15px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #f9fbfd;
        transition: border .2s;
        outline: none;
    }
    form input[type="text"]:focus, form input[type="number"]:focus, form textarea:focus {
        border: 1.5px solid #4096ee;
    }
    form button {
        background: #0066cc;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 7px 18px;
        font-size: 15px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.2s;
    }
    form button:hover {
        background: #004c9c;
    }
    table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        background: #f9fbfd;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
        margin-top: 10px;
    }
    th, td {
        padding: 11px 10px;
        text-align: center;
    }
    th {
        background: #e6f0fa;
        color: #055290;
        font-size: 16px;
        letter-spacing: 0.5px;
        border-bottom: 1.5px solid #b5d1ea;
    }
    tr:nth-child(even) {
        background: #f1f5f9;
    }
    tr:hover {
        background: #e0ecff;
        transition: background 0.2s;
    }
    td {
        font-size: 15px;
        color: #333;
    }
    a {
        color: #0066cc;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    a:hover {
        color: #003366;
        text-decoration: underline;
    }
    .service-form {
        margin-top: 35px;
        padding: 18px 22px 12px 22px;
        background: #f5faff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        max-width: 420px;
    }
    .service-form h3 {
        margin-top: 0;
    }
    .service-form form {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .service-form input, .service-form textarea {
        font-size: 15px;
        padding: 7px 12px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #fff;
        margin-bottom: 6px;
        width: 100%;
        box-sizing: border-box;
    }
    .service-form textarea {
        min-height: 60px;
        resize: vertical;
    }
    .service-form button {
        margin-top: 10px;
        min-width: 110px;
    }
    @media (max-width: 700px) {
        .container {
            padding: 15px 4vw;
        }
        .service-form {
            padding: 12px 8px;
        }
        th, td {
            font-size: 14px;
            padding: 8px 4px;
        }
    }
</style>
</head>
<body>
<nav>
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

    <h2>Danh sách dịch vụ</h2>
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

    <div class="service-form">
        <?php if ($editService): ?>
            <h3>Sửa dịch vụ</h3>
            <form action="/index.php?controller=managerService&action=update" method="POST">
                <input type="hidden" name="id" value="<?= $editService->getId() ?>">
                <input type="text" name="service_name" value="<?= $editService->getServiceName() ?>" required>
                <textarea name="service_description" required><?= $editService->getServiceDescription() ?></textarea>
                <input type="number" name="service_price" value="<?= $editService->getServicePrice() ?>" required>
                <button type="submit">Cập nhật</button>
            </form>
        <?php else: ?>
            <h3>Thêm dịch vụ</h3>
            <form action="/index.php?controller=managerService&action=save" method="POST">
                <input type="text" name="service_name" placeholder="Tên dịch vụ" required>
                <textarea name="service_description" placeholder="Mô tả dịch vụ" required></textarea>
                <input type="number" name="service_price" placeholder="Giá" required>
                <button type="submit">Thêm</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

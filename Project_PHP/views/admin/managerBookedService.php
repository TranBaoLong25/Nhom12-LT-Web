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
    form input[type="text"],
    form input[type="date"],
    form select {
        padding: 7px 12px;
        font-size: 15px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #f9fbfd;
        transition: border .2s;
        outline: none;
    }
    form input[type="text"]:focus,
    form input[type="date"]:focus,
    form select:focus {
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
    .form-section {
        text-align: center;
        margin: 0 auto; 
        margin-top: 35px;
        padding: 18px 22px 12px 22px;
        background: #f5faff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        max-width: 420px;
    }
    .form-section h3 {
        margin-top: 0;
    }
    .form-section form {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .form-section input,
    .form-section select {
        font-size: 15px;
        padding: 7px 12px;
        border: 1px solid #c0d3e0;
        border-radius: 6px;
        background: #fff;
        margin-bottom: 6px;
        width: 100%;
        box-sizing: border-box;
    }
    .form-section button {
        margin-top: 10px;
        min-width: 110px;
    }
    @media (max-width: 700px) {
        .container {
            padding: 15px 4vw;
        }
        .form-section {
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
        <input type="hidden" name="controller" value="managerbookedService">
        <input type="hidden" name="action" value="searchBookedService">
        <input type="text" name="keyword" placeholder="Tìm theo ID người dùng">
        <button type="submit">Tìm kiếm</button>
    </form>

    <h2 style="text-align: center;">Danh sách dịch vụ đã đặt</h2>
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
    <p style="text-align: center;"><a href="/views/admin/managerBookedService.php">Thêm Booked Service</a></p>
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
                <button style="align-self: center;" type="submit">Cập nhật</button>
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
                <button style="align-self: center;" type="submit">Thêm</button>
            </form>
        <?php } ?>
    </div>
</div>
</body>
</html>

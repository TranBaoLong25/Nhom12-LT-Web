<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/BookedRoom.php';
require_once __DIR__ . '/../repositories/IBookedRoomRepository.php';
require_once __DIR__ . '/../repositories/BookedRoomRepository.php';
require_once __DIR__ . '/../services/IBookedRoomService.php';
require_once __DIR__ . '/../services/BookedRoomService.php';

require_once __DIR__ . '/../models/BookedService.php';
require_once __DIR__ . '/../repositories/IBookedServiceRepository.php';
require_once __DIR__ . '/../repositories/BookedServiceRepository.php';
require_once __DIR__ . '/../services/IBookedServiceService.php';
require_once __DIR__ . '/../services/BookedServiceService.php';

require_once __DIR__ . '/../models/Service.php';
require_once __DIR__ . '/../repositories/IServiceRepository.php';
require_once __DIR__ . '/../repositories/ServiceRepository.php';
require_once __DIR__ . '/../services/IServiceService.php';
require_once __DIR__ . '/../services/ServiceService.php';
require_once __DIR__ . '/../connection.php';

$conn = Database::getConnection();
$bookedRoomRepository = new BookedRoomRepository($conn);
$bookedRoomService = new BookedRoomService($bookedRoomRepository);

$bookedServiceRepository = new BookedServiceRepository($conn);
$bookedServiceService = new BookedServiceService($bookedServiceRepository);
$services = (new ServiceService(new ServiceRepository($conn)))->getAllServices();
// Lấy user và danh sách phòng đã đặt
$user = $_SESSION['user'] ?? null;
$user_id = $user['id'] ?? ($_SESSION['user_id'] ?? null);

$bookedRooms = [];
if ($user_id) {
    $bookedRooms = $bookedRoomService->findByUserId($user_id);
}

// Lấy danh sách dịch vụ đã đặt
$bookedServices = [];
if ($user_id) {
    $bookedServices = $bookedServiceService->findByUserId($user_id);
}

// Xử lý hủy đặt phòng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_room_id'])) {
    $deleteId = intval($_POST['delete_room_id']);
    $room = $bookedRoomService->findById($deleteId);
    if ($room && $room->getUserId() == $user_id) {
        $bookedRoomService->delete($deleteId);
        header("Location: profile.php");
        exit;
    }
}

// Xử lý hủy dịch vụ đã đặt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service_id'])) {
    $deleteId = intval($_POST['delete_service_id']);
    // Tìm dịch vụ theo id
    $service = $bookedServiceRepository->findById($deleteId);
    // Nếu findById trả về array, dùng $service['user_id'], nếu trả về object, dùng $service->getUserId()
    $service_user_id = is_array($service) ? ($service['user_id'] ?? null) : ($service && method_exists($service, 'getUserId') ? $service->getUserId() : null);
    if ($service && $service_user_id == $user_id) {
        $bookedServiceRepository->deleteBookedService($deleteId);
        header("Location: profile.php");
        exit;
    }
}
?>
<?php include_once('./fragments/header.php'); ?>
<link rel="stylesheet" href="/assets/css/support.css">
<link rel="stylesheet" href="/assets/css/index.css">
<link rel="stylesheet" href="/assets/css/header.css">
<link rel="stylesheet" href="/assets/css/profile.css">

<div style="margin-top: 80px;">
  <h2 style="text-align: center; color: #333;">Thông Tin Cá Nhân</h2>
  <div class="profile-container">
    <?php if ($user): ?>
      <img class="profile-avatar" src="/assets/images/img1.jpg" alt="Ảnh đại diện">
      <div class="profile-info">
        <p><strong>👤 Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>🎭 Vai trò:</strong> <?= htmlspecialchars($user['role']) ?></p>
      </div>

      <!-- Nút bật form -->
      <label for="toggleEdit" class="btn-edit">Chỉnh sửa thông tin</label>
      <input type="checkbox" id="toggleEdit" hidden>

      <div class="modal">
        <div class="modal-content">
          <label for="toggleEdit" class="close-btn">&times;</label>
          <h3>Chỉnh sửa thông tin</h3>
          <form action="/edit-profile.php" method="post">
            <label><strong>👤 Username:</strong></label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label><strong>🔑 Mật khẩu mới:</strong></label>
            <input type="password" name="password" required>

            <label><strong>🔑 Mật khẩu cũ:</strong></label>
            <input type="password" name="confirmPassword" required>

            <div class="button-group">
              <label for="toggleEdit" class="btn-edit cancel-btn">Hủy</label>
              <button type="submit" class="btn-edit">Lưu thay đổi</button>
            </div>
          </form>
        </div>
      </div>

      <h3>Danh sách Homestay đã đặt:</h3>
      <?php if (empty($bookedRooms)): ?>
        <p style="color: gray;">Chưa có phòng nào được đặt. <a href="/views/bookedRoom.php">Đặt phòng ngay!</a></p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Tên khách</th>
              <th>Số điện thoại</th>
              <th>Ngày đến</th>
              <th>Ngày đi</th>
              <th>Homestay ID</th>
              <th>Thao Tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookedRooms as $room): ?>
              <tr>
                <td><?= htmlspecialchars($room->getGuestName()) ?></td>
                <td><?= htmlspecialchars($room->getGuestPhone()) ?></td>
                <td><?= htmlspecialchars($room->getCheckIn()) ?></td>
                <td><?= htmlspecialchars($room->getCheckOut()) ?></td>
                <td><?= htmlspecialchars($room->getHomeStayId()) ?></td>
                <td>
                  <form method="POST" action="profile.php" onsubmit="return confirm('Bạn chắc chắn muốn hủy đặt phòng này?');">
                    <input type="hidden" name="delete_room_id" value="<?= htmlspecialchars($room->getId()) ?>">
                    <button type="submit" class="cancel-btn">Hủy đặt phòng</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
<h3>Dịch vụ đã đặt:</h3>
<?php if (empty($bookedServices)): ?>
  <p style="color: gray;">Chưa có dịch vụ nào được đặt. <a href="/views/services.php">Đặt dịch vụ ngay!</a></p>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Tên dịch vụ</th>
        <th>Giá</th>
        <th>Mô tả</th>
        <th>Thời gian</th>
        <th>Thao Tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($bookedServices as $booked): ?>
        <?php
          // Tìm dịch vụ tương ứng theo ID
          $matchedService = null;
          foreach ($services as $s) {
              if ($s->getId() == $booked->getServiceId()) {
                  $matchedService = $s;
                  break;
              }
          }
        ?>
        <?php if ($matchedService): ?>
          <tr>
            <td><?= htmlspecialchars($matchedService->getServiceName()) ?></td>
            <td><?= htmlspecialchars($matchedService->getServicePrice()) ?> VNĐ</td>
            <td><?= htmlspecialchars($matchedService->getServiceDescription()) ?></td>
            <td><?= htmlspecialchars($booked->getTime()) ?></td>
            <td>
              <form method="POST" action="profile.php" onsubmit="return confirm('Bạn chắc chắn muốn hủy dịch vụ này?');">
                <input type="hidden" name="delete_service_id" value="<?= htmlspecialchars($booked->getId()) ?>">
                <button type="submit" class="cancel-btn">Hủy dịch vụ</button>
              </form>
            </td>
          </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

      <?php if ($user['role'] === 'admin'): ?>
        <div class="admin-panel">
          <h3><span class="admin-icon">🛠️</span> KHU VỰC QUẢN TRỊ</h3>
          <div class="admin-links">
            <a href="/views/admin/managerBookedRoom.php">📦 Quản lý đặt phòng</a>
            <a href="/views/admin/managerHomestay.php">🏡 Quản lý Homestay</a>
            <a href="/views/admin/managerService.php">🧰 Quản lý dịch vụ</a>
            <a href="/views/admin/managerUser.php">👤 Quản lý người dùng</a>
            <a href="/views/admin/managerBookedService.php">📋 Quản lý đặt dịch vụ</a>
          </div>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <p>Bạn chưa đăng nhập. <a href="/views/login.php">Đăng nhập</a></p>
    <?php endif; ?>
  </div>
</div>
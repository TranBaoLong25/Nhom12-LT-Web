<?php
session_start();

// Lấy thông tin người dùng và dữ liệu đặt phòng/dịch vụ từ session hoặc DB
$user = $_SESSION['user'] ?? null;
$bookedRooms = $_SESSION['bookedRooms'] ?? [];
$bookedServices = $_SESSION['bookedServices'] ?? [];

include_once('./fragments/header.php');
?>

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
              <th>Loại phòng</th>
              <th>Vị trí</th>
              <th>Ngày đến</th>
              <th>Ngày đi</th>
              <th>Giá</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookedRooms as $room): ?>
              <tr>
                <td><?= htmlspecialchars($room['guest_name']) ?></td>
                <td><?= htmlspecialchars($room['guest_phone']) ?></td>
                <td><?= htmlspecialchars($room['homeStay']['room_type']) ?></td>
                <td><?= htmlspecialchars($room['homeStay']['location']) ?></td>
                <td><?= htmlspecialchars($room['check_in_date']) ?></td>
                <td><?= htmlspecialchars($room['check_out_date']) ?></td>
                <td><?= htmlspecialchars($room['homeStay']['room_price']) ?> VNĐ</td>
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
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookedServices as $booked): ?>
              <tr>
                <td><?= htmlspecialchars($booked['service']['service_name']) ?></td>
                <td><?= htmlspecialchars($booked['service']['service_price']) ?> VNĐ</td>
                <td><?= htmlspecialchars($booked['service']['service_description']) ?></td>
                <td><?= htmlspecialchars($booked['time']) ?></td>
              </tr>
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

<?php include_once './fragments/footer.php'; ?>

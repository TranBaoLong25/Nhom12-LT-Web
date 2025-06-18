<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Hotel</title>
    <head>
<body>
    <header class="main-header">
        <div class="header-content container">
            <a href="/index.php" class="logo">Aura Hotel</a>
            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="/views/home.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : '' ?>">Trang Chủ</a></li>
                    <li><a href="/views/bookedRoom.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'bookedRoom.php' || basename($_SERVER['PHP_SELF']) == 'bookedroom.php') ? 'active' : '' ?>">Đặt Phòng</a></li>
                    <li><a href="/views/services.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'services.php' || basename($_SERVER['PHP_SELF']) == 'Services.php') ? 'active' : '' ?>">Dịch Vụ</a></li>
                    <li><a href="/views/support.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'support.php' || basename($_SERVER['PHP_SELF']) == 'Support.php') ? 'active' : '' ?>">Hỗ Trợ</a></li>
                <?php if (isset($_SESSION['user']['id'])): ?>
                        <li><a href="/views/profile.php">Trang Cá Nhân</a></li>
                        <li>
                            <form action="/views/logout.php" method="post" class="logout-form">
                                <button type="submit" class="logout-button">Đăng Xuất</button>
                            </form>
                        </li>
                    <?php else: ?>
                         <li><a href="/views/login.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : '' ?>">Đăng Nhập</a></li>
                        <li><a href="/views/register.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'register.php') ? 'active' : '' ?>">Đăng Ký</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    




<script>
    // Script cho slider ảnh, chỉ khi có slider trên trang
    const sliderImg = document.getElementById("slider-img");
    if (sliderImg) {
        let current = 1;
        setInterval(() => {
            current++;
            sliderImg.src = `https://picsum.photos/1200/400?random=${current}`;
        }, 3000);
    }
</script>


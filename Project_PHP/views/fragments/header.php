<?php
// Bắt đầu session nếu chưa có. Rất quan trọng để nó ở đầu file PHP.
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
    <link rel="stylesheet" href="/Project_PHP/assets/css/index.css">
    </head>
<body>
    <header class="main-header">
        <div class="header-content container">
            <a href="/Project_PHP/index.php" class="logo">Aura Hotel</a>
            <nav class="main-nav">
                <ul class="nav-links">
                    <li><a href="/Project_PHP/index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">Trang Chủ</a></li>
                    <li><a href="/Project_PHP/views/bookedRoom.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'bookedRoom.php' || basename($_SERVER['PHP_SELF']) == 'bookedroom.php') ? 'active' : '' ?>">Đặt Phòng</a></li>
                    <li><a href="/Project_PHP/services.php">Dịch Vụ</a></li>
                    <li><a href="/Project_PHP/support.php">Hỗ Trợ</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/Project_PHP/profile.php">Trang Cá Nhân</a></li>
                        <li>
                            <form action="/Project_PHP/login/logout.php" method="post" class="logout-form">
                                <button type="submit" class="logout-button">Đăng Xuất</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li><a href="/Project_PHP/login.php">Đăng Nhập</a></li>
                        <li><a href="/Project_PHP/register.php">Đăng Ký</a></li>
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

    
<?php
// Include phần header của trang
include_once('views/fragments/header.php');
?>
<link rel="stylesheet" href="./assets//css//index.css">
<link rel="stylesheet" href="./assets//css//header.css">

<section class="slider">
    <section class="slider">
        <div class="slideshow-container">
            <img class="slide active" src="/Project_PHP/assets/images/slide-1.jpg" alt="Ảnh khách sạn 1">
            <img class="slide" src="/Project_PHP/assets/images/slide-2.jpg" alt="Ảnh khách sạn 2">
            <img class="slide" src="/Project_PHP/assets/images/slide-3.jpg" alt="Ảnh khách sạn 3">
            <img class="slide" src="/Project_PHP/assets/images/slide-4.jpg" alt="Ảnh khách sạn 4">
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>

        <div style="text-align:center; margin-top: 10px;">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
            </div>
    </section>
</section>

<script src="/Project_PHP//assets//js//slide.js"></script>

<section class="welcome">
    <div class="welcome-content">
        <h2>Aura Hotel</h2>
        <h3>Đẳng Cấp - Sang Trọng - Quý Phái</h3>
        <p>Tại Aura Hotel, chúng tôi tin rằng mỗi kỳ nghỉ là một tác phẩm nghệ thuật. Tọa lạc tại trung tâm thành phố sôi động, Aura Hotel mang đến không gian nghỉ dưỡng đẳng cấp, nơi kiến trúc tinh tế hòa quyện cùng dịch vụ hoàn hảo, tạo nên trải nghiệm khó quên cho mỗi vị khách.</p>
    </div>
</section>

<div class="container">

    <section class="highlights-section">
        <h2 class="section-title">Khám Phá Sự Khác Biệt Tại Aura Hotel</h2>
        <div class="highlights-grid">
            <div class="highlight-item">
                <img src="./assets//images//slide-3.jpg" alt="Phòng Suite Đẳng Cấp">
                <h3>Phòng & Suite Đẳng Cấp</h3>
                <p>Thư giãn trong không gian riêng tư được thiết kế tinh tế, với tiện nghi hiện đại, giường King-size êm ái và tầm nhìn bao quát thành phố.</p>
                <a href="/Project_PHP/views/bookedRoom.php" class="btn btn-primary <?= (basename($_SERVER['PHP_SELF']) == 'bookedRoom.php' || basename($_SERVER['PHP_SELF']) == 'bookedroom.php') ? 'active' : '' ?>">Xem Chi Tiết Phòng</a>
            </div>

            <div class="highlight-item">
                <img src="./assets//images//Food.jpg" alt="Ẩm Thực Tinh Hoa">
                <h3>Ẩm Thực Tinh Hoa</h3>
<p>Khám phá hành trình vị giác đỉnh cao tại nhà hàng fine-dining sang trọng của chúng tôi, nơi hội tụ tinh hoa ẩm thực Á-Âu.</p>
                <a href="/dining" class="btn btn-primary">Xem Thực Đơn</a>
            </div>

            <div class="highlight-item">
                <img src="./assets//images//Service.jpg" alt="Tiện Ích & Dịch Vụ">
                <h3>Tiện Ích & Dịch Vụ Độc Quyền</h3>
                <p>Tận hưởng sự thư thái tại Aura Spa, đắm mình trong hồ bơi vô cực trên tầng thượng, hoặc rèn luyện tại phòng gym hiện đại.</p>
                <a href="/facilities" class="btn btn-primary">Khám Phá Tiện Ích</a>
            </div>

            
        </div>
    </section>

    <section class="testimonials-section">
        <h2 class="section-title">Những Gì Khách Hàng Nói Về Chúng Tôi</h2>
        <div class="testimonials-grid">
            <div class="testimonial-item">
                <img src="https://i.pravatar.cc/150?img=1" alt="Ảnh khách hàng 1" class="testimonial-avatar">
                <p class="testimonial-text">"Trải nghiệm tuyệt vời tại Aura Hotel! Phòng ốc sang trọng, dịch vụ chuyên nghiệp và ẩm thực không chê vào đâu được. Chắc chắn sẽ quay lại!"</p>
                <p class="testimonial-author"><strong>Nguyễn Thị Thảo</strong> - Khách hàng</p>
            </div>

            <div class="testimonial-item">
                <img src="https://i.pravatar.cc/150?img=2" alt="Ảnh khách hàng 2" class="testimonial-avatar">
                <p class="testimonial-text">"Aura Hotel đã mang đến một kỳ nghỉ dưỡng thực sự đẳng cấp. Hồ bơi trên tầng thượng là điểm nhấn không thể bỏ qua, view cực đẹp!"</p>
                <p class="testimonial-author"><strong>Trần Văn An</strong> - Khách hàng</p>
            </div>

            <div class="testimonial-item">
                <img src="https://i.pravatar.cc/150?img=3" alt="Ảnh khách hàng 3" class="testimonial-avatar">
                <p class="testimonial-text">"Mọi thứ đều hoàn hảo, từ sự đón tiếp nồng hậu đến tiện nghi phòng. Rất ấn tượng với dịch vụ concierge của Aura Hotel. Highly recommend!"</p>
                <p class="testimonial-author"><strong>Lê Minh Tú</strong> - Khách hàng</p>
            </div>
        </div>
    </section>

</div> <?php
// Include phần footer của trang
include_once 'views/fragments/footer.php';
?>
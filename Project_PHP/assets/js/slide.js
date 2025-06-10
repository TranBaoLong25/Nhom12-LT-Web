// assets/js/slider.js

let slideIndex = 1; // Bắt đầu từ slide đầu tiên (index 1 cho người dùng, sẽ chuyển thành 0 khi truy cập mảng)
let slides = [];    // Mảng chứa tất cả các ảnh slide
let dots = [];      // Mảng chứa tất cả các chấm tròn
let timeoutId;      // Biến để lưu trữ ID của setTimeout

document.addEventListener('DOMContentLoaded', (event) => {
    slides = document.querySelectorAll('.slideshow-container .slide'); // Lấy tất cả các slide
    dots = document.querySelectorAll('.dot'); // Lấy tất cả các chấm tròn

    if (slides.length > 0) {
        // Khởi tạo hiển thị slide đầu tiên và bắt đầu tự động chuyển slide
        updateSlideDisplay(); // Hiển thị slide ban đầu
        timeoutId = setTimeout(showSlides, 5000); // Bắt đầu tự động chuyển slide
    }
});

function showSlides() {
    // Tăng slideIndex để chuyển sang ảnh tiếp theo
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1; // Quay lại ảnh đầu tiên nếu đã hết ảnh
    }

    // Cập nhật hiển thị slide và chấm
    updateSlideDisplay();

    // Tự động chuyển slide sau 5 giây (5000 milliseconds)
    timeoutId = setTimeout(showSlides, 5000);
}

// Chức năng chuyển slide bằng nút Next/Prev
function plusSlides(n) {
    clearTimeout(timeoutId); // Xóa timer hiện tại khi người dùng tương tác

    slideIndex += n; // Di chuyển tới slide trước/sau

    if (slideIndex > slides.length) {
        slideIndex = 1; // Vòng lại slide đầu tiên nếu vượt quá giới hạn
    }
    if (slideIndex < 1) {
        slideIndex = slides.length; // Vòng lại slide cuối cùng nếu về dưới giới hạn
    }
    
    // Cập nhật hiển thị slide và chấm
    updateSlideDisplay();

    // Sau khi người dùng click, tiếp tục tự động chuyển slide sau 5 giây
    timeoutId = setTimeout(showSlides, 5000);
}

// Chức năng chuyển slide bằng cách click vào chấm tròn
function currentSlide(n) {
    clearTimeout(timeoutId); // Xóa timer hiện tại

    slideIndex = n; // Đặt slideIndex theo chấm được click

    // Cập nhật hiển thị slide và chấm
    updateSlideDisplay();

    // Sau khi người dùng click, tiếp tục tự động chuyển slide sau 5 giây
    timeoutId = setTimeout(showSlides, 5000);
}

// Hàm hỗ trợ để cập nhật hiển thị slide và chấm
function updateSlideDisplay() {
    // Ẩn tất cả các slide và bỏ active class của chấm
    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove('active');
    }
    for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove('active');
    }

    // Hiển thị slide hiện tại và active chấm tương ứng
    slides[slideIndex - 1].classList.add('active'); // slideIndex là 1-based, mảng là 0-based
    dots[slideIndex - 1].classList.add('active'); // slideIndex là 1-based, mảng là 0-based
}
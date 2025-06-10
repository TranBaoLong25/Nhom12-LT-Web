// assets/js/slider.js

let slideIndex = 0; // Bắt đầu từ ảnh đầu tiên (index 0)
let slides = []; // Mảng chứa tất cả các ảnh slide
let dots = []; // Mảng chứa tất cả các chấm tròn
let timeoutId; // Biến để lưu trữ ID của setTimeout

document.addEventListener('DOMContentLoaded', (event) => {
    slides = document.querySelectorAll('.slideshow-container .slide');
    dots = document.querySelectorAll('.dot');

    if (slides.length > 0) {
        showSlides(); // Gọi hàm hiển thị slide khi DOM đã tải xong
    }
});

function showSlides() {
    // Xóa bất kỳ timeout hiện có nào để tránh chạy đúp
    clearTimeout(timeoutId);

    // Ẩn tất cả các slide và bỏ active class của chấm
    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove('active');
    }
    for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove('active');
    }

    // Tăng slideIndex để chuyển sang ảnh tiếp theo
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1; // Quay lại ảnh đầu tiên nếu đã hết ảnh
    }

    // Hiển thị slide hiện tại và active chấm tương ứng
    slides[slideIndex - 1].classList.add('active');
    dots[slideIndex - 1].classList.add('active');

    // Tự động chuyển slide sau 5 giây (5000 milliseconds)
    timeoutId = setTimeout(showSlides, 5000);
}

// Chức năng chuyển slide bằng nút Next/Prev
function plusSlides(n) {
    clearTimeout(timeoutId); // Xóa timer hiện tại khi người dùng tương tác

    slideIndex += n; // Di chuyển tới slide trước/sau

    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    if (slideIndex < 1) {
        slideIndex = slides.length;
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
    for (let i = 0; i < slides.length; i++) {
        slides[i].classList.remove('active');
    }
    for (let i = 0; i < dots.length; i++) {
        dots[i].classList.remove('active');
    }
    slides[slideIndex - 1].classList.add('active');
    dots[slideIndex - 1].classList.add('active');
}
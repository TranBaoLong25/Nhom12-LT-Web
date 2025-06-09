// service.js

/**
 * Mở form đặt dịch vụ và điền thông tin dịch vụ đã chọn.
 * @param {string} serviceName - Tên của dịch vụ được chọn.
 * @param {number} servicePrice - Giá của dịch vụ được chọn.
 * @param {number} serviceId - ID của dịch vụ được chọn.
 */
function openBookingForm(serviceName, servicePrice, serviceId) {
    document.getElementById('selected-service').innerText = `Bạn đã chọn: ${serviceName} - Giá: ${servicePrice.toLocaleString('vi-VN')}đ`;
    document.getElementById('booking-service-name').value = serviceName;
    document.getElementById('booking-service-price').value = servicePrice;
    document.getElementById('booking-service-id').value = serviceId; // Gán serviceId vào trường ẩn

    // Đặt ngày hiện tại làm giá trị mặc định cho trường ngày đặt
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months start at 0!
    const dd = String(today.getDate()).padStart(2, '0');
    document.getElementById('service_booking_date').value = `${yyyy}-${mm}-${dd}`;

    document.getElementById('booking-form').classList.add('show');
}

/**
 * Đóng form đặt dịch vụ.
 */
function closeBookingForm() {
    document.getElementById('booking-form').classList.remove('show');
}

/**
 * Mở modal thông báo đặt dịch vụ thành công.
 */
function openSuccessModal() {
    const modal = document.getElementById('booking-success-modal');
    if (modal) {
        modal.classList.add('show');
    }
}

/**
 * Đóng modal thông báo đặt dịch vụ thành công.
 */
function closeSuccessModal() {
    const modal = document.getElementById('booking-success-modal');
    if (modal) {
        modal.classList.remove('show');
    }
    // Tùy chọn: Làm mới trang hoặc chuyển hướng sau khi đóng modal thành công
    // window.location.reload(); 
}

/**
 * Mở modal thông báo đặt dịch vụ thất bại (lỗi).
 */
function openErrorModal() {
    const modal = document.getElementById('booking-error-modal');
    if (modal) {
        modal.classList.add('show');
    }
}

/**
 * Đóng modal thông báo đặt dịch vụ thất bại (lỗi).
 */
function closeErrorModal() {
    const modal = document.getElementById('booking-error-modal');
    if (modal) {
        modal.classList.remove('show');
    }
}

// Đảm bảo rằng form đặt dịch vụ và modal đóng khi click bên ngoài nội dung của chúng
window.onclick = function(event) {
    const bookingForm = document.getElementById('booking-form');
    const successModal = document.getElementById('booking-success-modal');
    const errorModal = document.getElementById('booking-error-modal');

    if (event.target === bookingForm) {
        closeBookingForm();
    }
    if (event.target === successModal) {
        closeSuccessModal();
    }
    if (event.target === errorModal) {
closeErrorModal();
    }
};
<?php
include_once('./fragments/header.php');

$message_sent = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu từ form liên hệ
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    $category = htmlspecialchars($_POST['category']); // Lấy giá trị category

    // Giữ nguyên phần hiển thị thông báo thành công.
    // Trong một ứng dụng thực tế, bạn sẽ gửi email hoặc lưu vào CSDL ở đây.
    echo "<div class='container form-feedback-container'>"; // Thêm container cho thông báo
    echo "<div class='form-feedback success-message'>";
    echo "<h3><i class='fas fa-check-circle'></i> Cảm ơn bạn đã liên hệ!</h3>";
    echo "<p>Yêu cầu hỗ trợ của bạn đã được gửi thành công. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>";
    echo "<p><strong>Tên:</strong> " . $name . "</p>";
    echo "<p><strong>Email:</strong> " . $email . "</p>";
    echo "<p><strong>Chủ đề:</strong> " . $subject . "</p>";
    echo "<p><strong>Danh mục hỗ trợ:</strong> " . $category . "</p>";
    echo "<p><strong>Nội dung:</strong> " . $message . "</p>";
    echo "</div>";
    echo "</div>"; // Đóng container cho thông báo

    $message_sent = true;
}
?>

<link rel="stylesheet" href="/assets//css//support.css">
<link rel="stylesheet" href="/assets//css//home.css">
<link rel="stylesheet" href="/assets/css/header.css">


<div class="container">
    <h1 class="page-title">Trung Tâm Hỗ Trợ Khách Hàng</h1>

    <section class="support-section intro-section">
        <p>Chào mừng bạn đến với Trung tâm hỗ trợ của chúng tôi. Tại đây, bạn có thể tìm thấy câu trả lời cho các câu hỏi thường gặp, hướng dẫn sử dụng chi tiết, và cách liên hệ với đội ngũ hỗ trợ của chúng tôi.</p>
    </section>

    <section class="support-section faq-section">
        <h2><i class="fas fa-question-circle icon"></i> Câu Hỏi Thường Gặp (FAQ)</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>Làm thế nào để đặt phòng trên website?</h3>
                <p>Để đặt phòng, bạn cần truy cập trang <a href="/index.php">"Trang Chủ"</a> hoặc <a href="/views/bookedRoom.php">"Phòng Đã Đặt"</a>, chọn ngày đến và ngày đi, số lượng khách, sau đó chọn loại phòng và tiến hành thanh toán. Bạn cần có tài khoản để hoàn tất việc đặt phòng.</p>
            </div>
            <div class="faq-item">
                <h3>Tôi có thể thanh toán bằng những phương thức nào?</h3>
                <p>Chúng tôi chấp nhận thanh toán qua thẻ tín dụng/ghi nợ (Visa, Mastercard), chuyển khoản ngân hàng, và ví điện tử (MoMo, ZaloPay - nếu có tích hợp).</p>
            </div>
            <div class="faq-item">
                <h3>Làm sao để đặt thêm dịch vụ (ăn uống, spa, thuê xe)?</h3>
                <p>Bạn có thể đặt thêm dịch vụ tại trang <a href="/services.php">"Dịch Vụ"</a> hoặc trong quá trình đặt phòng. Sau khi đặt phòng thành công, bạn cũng có thể quản lý và thêm dịch vụ thông qua trang <a href="/views/bookedRoom.php">"Phòng Đã Đặt"</a> của mình.</p>
            </div>
            <div class="faq-item">
                <h3>Tôi phải làm gì nếu gặp sự cố khi thanh toán?</h3>
                <p>Nếu bạn gặp sự cố khi thanh toán, vui lòng kiểm tra lại thông tin thẻ/tài khoản. Nếu vẫn không được, hãy thử phương thức thanh toán khác hoặc liên hệ ngay với chúng tôi qua <a href="#contact-form">form liên hệ</a> hoặc số điện thoại hỗ trợ.</p>
            </div>
            <div class="faq-item">
                <h3>Làm cách nào để hủy hoặc thay đổi đặt phòng?</h3>
                <p>Để hủy hoặc thay đổi đặt phòng, vui lòng <b><a href="/login.php">đăng nhập</a></b> vào tài khoản của bạn, truy cập trang <a href="/views/bookedRoom.php">"Phòng Đã Đặt"</a> và chọn đặt phòng muốn thay đổi. Lưu ý các chính sách hủy/thay đổi có thể áp dụng phí hoặc giới hạn thời gian.</p>
            </div>
        </div>
    </section>



    <section class="support-section contact-form-section" id="contact-form"> <h2><i class="fas fa-envelope icon"></i> Gửi Yêu Cầu Hỗ Trợ</h2>
        <?php if (!$message_sent): // Chỉ hiển thị form nếu chưa gửi tin nhắn ?>
            <form action="support.php" method="POST" class="contact-form">
                <div class="form-group">
                    <label for="name">Họ và Tên:</label>
                    <input type="text" id="name" name="name" required placeholder="Nhập tên của bạn">
                </div>
                <div class="form-group">
                    <label for="email">Email của bạn:</label>
                    <input type="email" id="email" name="email" required placeholder="Nhập email liên hệ">
                </div>
                <div class="form-group">
                    <label for="category">Danh mục hỗ trợ:</label>
                    <select id="category" name="category" required>
                        <option value="">-- Chọn danh mục --</option>
                        <option value="Đặt phòng">Hỗ trợ Đặt phòng</option>
                        <option value="Thanh toán">Hỗ trợ Thanh toán</option>
                        <option value="Đặt dịch vụ">Hỗ trợ Đặt dịch vụ</option>
                        <option value="Kỹ thuật">Sự cố kỹ thuật</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subject">Chủ đề:</label>
                    <input type="text" id="subject" name="subject" required placeholder="Tóm tắt vấn đề của bạn">
                </div>
                <div class="form-group">
                    <label for="message">Nội dung yêu cầu:</label>
                    <textarea id="message" name="message" rows="6" required placeholder="Mô tả chi tiết vấn đề bạn đang gặp phải..."></textarea>
                </div>
                <button type="submit" class="submit-button">Gửi Yêu Cầu</button>
            </form>
        <?php endif; ?>
    </section>

 

</div> <?php
include_once './fragments/footer.php';
?>
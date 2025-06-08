<?php
class BaseController
{
    protected $folder; // Thư mục con trong views/ chứa các file view

    // Hàm hiển thị view với layout
    function render($file, $data = array(), $layout = 'main')
    {
        // Tạo đường dẫn tới file view, cho phép folder rỗng
        $view_file = 'views/' . ($this->folder ? $this->folder . '/' : '') . $file . '.php';

        // Kiểm tra file view có tồn tại không
        if (is_file($view_file)) {
            // Tạo biến từ mảng dữ liệu truyền vào
            extract($data);

            // Bắt đầu lưu output vào bộ đệm
            ob_start();
            require_once($view_file);
            $content = ob_get_clean();

            // Gọi layout để hiển thị nội dung view
            require_once('views/layouts/' . $layout . '.php');
        } else {
            // Nếu không tìm thấy file, chuyển hướng đến trang lỗi
            header('Location: index.php?controller=home&action=error');
        }
    }
}
?>

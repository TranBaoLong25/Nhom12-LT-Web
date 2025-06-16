<?php
include_once(__DIR__ . '/../../services/BookedServiceService.php');
include_once(__DIR__ . '/../../repositories/BookedServiceRepository.php');
include_once(__DIR__ . '/../../models/BookedService.php');

class ManagerBookedServiceController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Trang chính hiển thị danh sách người dùng
    public function showManagerBookedServicePage() {
        $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
        $bookedServices = $bookedServiceSer->getAllBookedServices();
        $newBookedService = new BookedService(null, '', '', '');
        include(__DIR__ . '/../../views/admin/managerBookedService.php');

    }
    public function editBookedService($id) {
    $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
    $bookedService = $bookedServiceSer->findById($id);

    if ($bookedService) {
        $editBookedService = $bookedService;
        $bookedServices = $bookedServiceSer->getAllBookedServices();
        include(__DIR__ . '/../../views/admin/managerBookedService.php');
    } else {
        echo "Không tìm phòng đặt.";
    }
}
    // Thêm người dùng mới
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $time = $_POST['time'];
            $user_id  = $_POST['user_id'];
            $service_id  = $_POST['service_id'];

            $bookedService = new BookedService(null, $time, $user_id, $service_id);
            $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
            $bookedServiceSer->save($bookedService);

            header('Location: /index.php?controller=managerbookedService');
            exit();
        }
    }

    // Xóa người dùng
    public function deleteBookedService($id) {
        $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
        $bookedServiceSer->deleteBookedService($id);
        header('Location: /index.php?controller=managerbookedService');
        exit();
    }

    // Sửa người dùng
    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $time = $_POST['time'] ?? '';
            $user_id  = $_POST['user_id'] ?? '';
            $service_id  = $_POST['service_id'] ?? '';

            if ($id === null) {
                echo "ID không được để trống";
                return;
            }
            $updateBookedService = new BookedService(null, $time, $user_id, $service_id);
            $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
            try {
                $bookedServiceSer->updateBookedService($id, $updateBookedService);
                header('Location: /index.php?controller=managerbookedService');
                exit();
            } catch(Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ";
        }
    }

    // Tìm kiếm người dùng
    public function searchBookedService() {
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
            try {
                $bookedService = $bookedServiceSer->findById($keyword);
                $bookedServices = $bookedService ? [$bookedService] : [];
            } catch(Exception $e) {
                $users = [];
            }

            $newBookedService = new BookedService(null, '', '', '');
            include(__DIR__ . '/../../views/admin/managerbookedService.php');
        } else {
            echo "Thiếu từ khóa tìm kiếm.";
        }
    }
}
?>

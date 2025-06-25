<?php
include_once(__DIR__ . '/../../services/IBookedRoomService.php');
include_once(__DIR__ . '/../../repositories/IBookedRoomRepository.php');
include_once(__DIR__ . '/../../services/BookedRoomService.php');
include_once(__DIR__ . '/../../repositories/BookedRoomRepository.php');
include_once(__DIR__ . '/../../models/BookedRoom.php'); 

class ManagerBookedRoomController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    } 
    
    public function index() {
        $this->showManagerBookedRoomPage();
    }

    public function showManagerBookedRoomPage() {
        // Tránh render view nếu đã gửi header (thêm/sửa/xóa)
        if (headers_sent()) {
            return;
        }

        // Tạo service
        $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn)); 

        // Lấy kết quả tìm kiếm nếu có
        if (isset($_SESSION['searchKeyword']) && $_SESSION['searchKeyword'] !== '') {
            $bookedRooms = $bookedRoomSer->findByUserId($_SESSION['searchKeyword']);
            unset($_SESSION['searchKeyword']);
        } else {
            $bookedRooms = $bookedRoomSer->getAllBookedRoom();
        }

        // Lấy dữ liệu edit nếu có
        $editBookedRoom = $_SESSION['editBookedRoom'] ?? null;
        unset($_SESSION['editBookedRoom']);

        include(__DIR__ . '/../../views/admin/managerBookedRoom.php');
    }

    public function editBookedRoom($id) {
        $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
        $bookedRoom = $bookedRoomSer->findById($id);

        if ($bookedRoom) {
            $_SESSION['editBookedRoom'] = $bookedRoom;
            // ⚠️ Giữ nguyên theo yêu cầu
            $this->showManagerBookedRoomPage();
        } else {
            echo "Không tìm thấy phòng đã đặt.";
        }
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guest_name = $_POST['guest_name'] ?? '';
            $guest_phone = $_POST['guest_phone'] ?? '';
            $check_in_date = $_POST['check_in_date'] ?? '';
            $check_out_date = $_POST['check_out_date'] ?? '';
            $user_id = $_POST['user_id'] ?? '';
            $homestay_id = $_POST['homestay_id'] ?? '';

            $bookedRoom = new BookedRoom(null, $guest_name, $guest_phone, $check_in_date, $check_out_date, $user_id, $homestay_id);
            $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
            $bookedRoomSer->save($bookedRoom);

            // Không render nữa, chuyển hướng về trang chính
            header('Location: /index.php?controller=managerBookedRoom');
            exit();
        }
    }

    public function updateBookedRoom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $guest_name = $_POST['guest_name'] ?? '';
            $guest_phone = $_POST['guest_phone'] ?? '';
            $check_in_date = $_POST['check_in_date'] ?? '';
            $check_out_date = $_POST['check_out_date'] ?? '';
            $user_id = $_POST['user_id'] ?? '';
            $homestay_id = $_POST['homestay_id'] ?? '';

            $bookedRoom = new BookedRoom($id, $guest_name, $guest_phone, $check_in_date, $check_out_date, $user_id, $homestay_id);
            $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
            $bookedRoomSer->updateBookedRoom($id, $bookedRoom);

            header('Location: /index.php?controller=managerBookedRoom');
            exit();
        }
    }
    public function deleteById($id) {
        $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
        $bookedRoomSer->deleteById($id);

        if (!headers_sent()) {
            header('Location: /index.php?controller=managerBookedRoom');
            exit();
        } else {
            // fallback: tự render lại nhưng rõ ràng (không gây lặp)
            $_SESSION['deleted'] = true;
            echo "<script>window.location.href = '/index.php?controller=managerBookedRoom';</script>";
            exit();
        }
    }

    public function searchBookedRoom() {
        $keyword = $_GET['keyword'] ?? '';
        $_SESSION['searchKeyword'] = $keyword;

        // Chuyển hướng lại trang chính
        header('Location: /index.php?controller=managerBookedRoom');
        exit();
    }
}

<?php
include_once(__DIR__ . '/../../services/BookedRoomService.php');
include_once(__DIR__ . '/../../repositories/BookedRoomRepository.php');
include_once(__DIR__ . '/../../models/BookedRoom.php');

class ManagerBookedRoomController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Trang chính hiển thị danh sách người dùng
    public function showManagerBookedRoomPage() {
        $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
        $bookedRooms = $bookedRoomSer->getAllBookedRoom();
        $newBookedRoom = new BookedRoom(null, '', '', '', '', '', '');
        include(__DIR__ . '/../../views/admin/managerBookedRoom.php');

    }
    public function editBookedRoom($id) {
    $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
    $bookedRoom = $bookedRoomSer->findById($id);

    if ($bookedRoom) {
        $editBookedRoom = $bookedRoom;
        $bookedRooms = $bookedRoomSer->getAllBookedRoom();
        include(__DIR__ . '/../../views/admin/managerBookedRoom.php');
    } else {
        echo "Không tìm phòng đặt.";
    }
}
    public function editBookedRoom2($phone) {
    $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
    $bookedRoom = $bookedRoomSer->findByPhone($phone);

    if ($bookedRoom) {
        $editBookedRoom = $bookedRoom;
        $bookedRooms = $bookedRoomSer->getAllBookedRoom();
        include(__DIR__ . '/../../views/admin/managerBookedRoom.php');
    } else {
        echo "Không tìm phòng đặt.";
    }
}
    // Thêm người dùng mới
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $guest_name = $_POST['guest_name'];
            $guest_phone = $_POST['guest_phone'];
            $check_in_date  = $_POST['check_in_date'];
            $check_out_date  = $_POST['check_out_date'];
            $user_id  = $_POST['user_id'];
            $homestay_id  = $_POST['homestay_id'];

            $bookedRoom = new BookedRoom(null, $guest_name, $guest_phone, $check_in_date, $check_out_date, $user_id, $homestay_id);
            $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
            $bookedRoomSer->save($bookedRoom);

            header('Location: /index.php?controller=managerbookedroom');
            exit();
        }
    }

    // Xóa người dùng
    public function deleteBookedRoom($id) {
        $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
        $bookedRoomSer->deleteBookedRoom($phone);
        header('Location: /index.php?controller=managerbookedroom');
        exit();
    }

    // Sửa người dùng
    public function updateBookedRoom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $guest_name = $_POST['guest_name'] ?? '';
            $guest_phone = $_POST['guest_phone'] ?? '';
            $check_in_date  = $_POST['check_in_date'] ?? '';
            $check_out_date  = $_POST['check_out_date'] ?? '';
            $user_id  = $_POST['user_id'] ?? '';
            $homestay_id  = $_POST['homestay_id'] ?? '';
            if ($id === null) {
                echo "ID không được để trống";
                return;
            }
            $updateBookedRoom = new BookedRoom(null, $guest_name, $guest_phone, $check_in_date, $check_out_date, $user_id, $homestay_id);
            $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
            try {
                $bookedRoomSer->updateBookedRoom($phone, $updateBookedRoom);
                header('Location: /index.php?controller=managerbookedroom');
                exit();
            } catch(Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ";
        }
    }

    // Tìm kiếm người dùng
    public function searchBookedRoom() {
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
            try {
                $bookedRoom = $bookedRoomSer->findByPhone($keyword);
                $bookedRooms = $bookedRoom ? [$bookedRoom] : [];
            } catch(Exception $e) {
                $users = [];
            }

            $newBookedRoom = new BookedRoom(null, '', '', '', '', '', '');
            include(__DIR__ . '/../../views/admin/managerbookedroom.php');
        } else {
            echo "Thiếu từ khóa tìm kiếm.";
        }
    }

    public function findByPhone($phone) {
        $bookedRoomSer = new BookedRoomService(new BookedRoomRepository($this->conn));
        return $bookedRoomSer->findByPhone($phone);
    }
}
?>

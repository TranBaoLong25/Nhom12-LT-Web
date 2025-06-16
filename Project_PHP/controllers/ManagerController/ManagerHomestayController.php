<?php
include_once(__DIR__ . '/../../services/HomeStayService.php');
include_once(__DIR__ . '/../../repositories/HomeStayRepository.php');
include_once(__DIR__ . '/../../models/HomeStay.php');

class ManagerHomestayController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function showManagerHomestayPage() {
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        $homeStays = $homeStaySer->getAllHomeStay();
        $newHomeStay = new HomeStay(null, '', '', 0, false, []);
        include(__DIR__ . '/../../views/admin/managerHomestay.php');
    }

    public function editHomeStay($id) {
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        $homeStay = $homeStaySer->findById($id);

        if ($homeStay) {
            $editHomeStay = $homeStay;
            $homeStays = $homeStaySer->getAllHomeStay();
            include(__DIR__ . '/../../views/admin/managerHomestay.php');
        } else {
            echo "Không tìm thấy homestay.";
        }
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_type = $_POST['room_type'];
            $location = $_POST['location'];
            $room_price = $_POST['room_price'];
            $booked = isset($_POST['booked']) ? 1 : 0;

            $image_urls = [];

            // Tạo thư mục uploads nếu chưa có
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($_FILES['images']['name'][$key]);
                    $targetPath = 'uploads/' . $fileName;
                    $absolutePath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmp_name, $absolutePath)) {
                        $image_urls[] = $targetPath;
                    }
                }
            }

            $homeStay = new HomeStay(null, $room_type, $location, $room_price, $booked, $image_urls);
            $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
            $homeStaySer->save($homeStay);

            header('Location: /index.php?controller=managerHomestay');
            exit();
        }
    }

    public function deleteHomeStay($id) {
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        $homeStaySer->deleteRoom($id);

        header('Location: /index.php?controller=managerHomestay');
        exit();
    }

    public function updateHomeStay() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $room_type = $_POST['room_type'] ?? '';
            $location = $_POST['location'] ?? '';
            $room_price = $_POST['room_price'] ?? 0;
            $booked = isset($_POST['booked']) ? 1 : 0;

            if ($id === null) {
                echo "ID không được để trống";
                return;
            }

            $image_urls = [];

            // Tạo thư mục uploads nếu chưa có
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($_FILES['images']['name'][$key]);
                    $targetPath = 'uploads/' . $fileName;
                    $absolutePath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmp_name, $absolutePath)) {
                        $image_urls[] = $targetPath;
                    }
                }
            }

            $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));

            try {
                $homeStaySer->updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls);
                header('Location: /index.php?controller=managerHomestay');
                exit();
            } catch (Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ";
        }
    }
    public function findById($id) {
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        return $homeStaySer->findById($id);
    }
}

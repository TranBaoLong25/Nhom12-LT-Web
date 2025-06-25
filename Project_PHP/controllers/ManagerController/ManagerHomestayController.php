<?php
require_once(__DIR__ . '/../../services/IHomeStayService.php');
require_once(__DIR__ . '/../../repositories/IHomeStayRepository.php');
require_once(__DIR__ . '/../../services/HomeStayService.php');
require_once(__DIR__ . '/../../repositories/HomeStayRepository.php');
require_once(__DIR__ . '/../../models/HomeStay.php');

class ManagerHomestayController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function index() {
        $this->showManagerHomestayPage();
    }

    public function showManagerHomestayPage() {
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        $homestays = $homeStaySer->getAllHomeStay();
        $editHomestay = $_SESSION['editHomestay'] ?? null;
        unset($_SESSION['editHomestay']);
        include(__DIR__ . '/../../views/admin/managerHomestay.php');
        exit(); 
    }

    public function editHomeStay($id) {
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        $homeStay = $homeStaySer->findById($id);
        if ($homeStay) {
            $_SESSION['editHomestayId'] = $homeStay->getId();
        }
        header('Location: /index.php?controller=managerHomestay');
        exit();
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_type = $_POST['room_type'];
            $location = $_POST['location'];
            $room_price = $_POST['room_price'];
            $booked = isset($_POST['booked']) ? 1 : 0;

            $image_urls = [];
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
            $id = $_POST['id'];
            $room_type = $_POST['room_type'];
            $location = $_POST['location'];
            $room_price = $_POST['room_price'];
            $booked = isset($_POST['booked']) ? 1 : 0;

            $image_urls = [];
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
            $homeStaySer->updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls);

            header('Location: /index.php?controller=managerHomestay');
            exit();
        }
    }

    public function searchHomestay() {
        $keyword = $_GET['keyword'] ?? '';
        $homeStaySer = new HomeStayService(new HomeStayRepository($this->conn));
        $homestays = (!empty($keyword) && $homeStaySer->findById($keyword)) ? [$homeStaySer->findById($keyword)] : $homeStaySer->getAllHomeStay();
        $editHomestay = $_SESSION['editHomestay'] ?? null;
        include(__DIR__ . '/../../views/admin/managerHomestay.php'); 
        unset($_SESSION['editHomestay']);    
    }
}

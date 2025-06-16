<?php
include_once('services/HomeStayService.php');

class HomeStayController extends BaseController {
    private $conn;
    private $homeStayService;

    public function __construct($conn) {
        session_start();
        $this->conn = $conn;
        $this->folder = 'homestay';
        $this->homeStayService = new HomeStayService(new HomeStayRepository($this->conn));
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_type = $_POST['room_type'] ?? '';
            $location = $_POST['location'] ?? '';
            $room_price = $_POST['room_price'] ?? 0;
            $booked = $_POST['booked'] ?? 0;

            $homeStay = new HomeStay(null, $room_type, $location, $room_price, $booked);
            $this->homeStayService->save($homeStay);

            header('Location: /admin/managerHomeStay.php');
            exit();
        }
    }

    public function deleteRoom($id) {
        $this->homeStayService->deleteRoom($id);
        header('Location: /admin/managerHomeStay.php');
        exit();
    }

    public function updateRoom() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $room_type = $_POST['room_type'] ?? '';
            $location = $_POST['location'] ?? '';
            $room_price = $_POST['room_price'] ?? 0;
            $booked = $_POST['booked'] ?? 0;

            $image_urls = $_POST['image_urls'] ?? [];

            if ($id === null) {
                echo "ID không được để trống!";
                return;
            }

            try {
                $this->homeStayService->updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls);
                header('Location: /admin/managerHomeStay.php');
                exit();
            } catch (Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ!";
        }
    }

    public function addImage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $homeStay_id = $_POST['homeStay_id'] ?? null;
            $url = $_POST['url'] ?? '';

            if ($homeStay_id !== null && $url !== '') {
                $this->homeStayService->addImage($homeStay_id, $url);
                header('Location: /admin/managerHomeStay.php');
                exit();
            } else {
                echo "Thiếu thông tin ảnh!";
            }
        }
    }

    public function findById($id) {
        return $this->homeStayService->findById($id);
    }

    public function getAllHomeStay() {
        return $this->homeStayService->getAllHomeStay();
    }

    public function getImagesByHomeStayId($id) {
        return $this->homeStayService->getImagesByHomeStayId($id);
    }
    public function findByLocation($location) {
        return $this->homeStayService->findByLocation($location);
    }

    public function findByType($room_type) {
        return $this->homeStayService->findByType($room_type);
    }

    public function findByPrice($min, $max) {
        return $this->homeStayService->findByPrice($min, $max);
    }
    public function searchHomeStay() {
        $room_type = $_GET['room_type'] ?? null;
        $location = $_GET['location'] ?? null;
        $price = $_GET['price'] ?? null;

        $minPrice = null;
        $maxPrice = null;

        if ($price !== null && strpos($price, '-') !== false) {
            [$minPrice, $maxPrice] = explode('-', $price);
        } elseif ($price !== null && str_ends_with($price, '+')) {
            $minPrice = (double)str_replace('+', '', $price);
            $maxPrice = INF;
        }

        $results = $this->homeStayService->searchHomeStays($room_type, $location, $minPrice, $maxPrice);
        include_once("views/bookedRoom.php");
    }
}
?>

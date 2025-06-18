<?php
require_once(__DIR__ . '/../../models/BookedService.php');
require_once(__DIR__ . '/../../models/Service.php');
require_once(__DIR__ . '/../../repositories/IBookedServiceRepository.php');
require_once(__DIR__ . '/../../repositories/IServiceRepository.php');
require_once(__DIR__ . '/../../repositories/BookedServiceRepository.php');
require_once(__DIR__ . '/../../repositories/ServiceRepository.php');
require_once(__DIR__ . '/../../services/IBookedServiceService.php');
require_once(__DIR__ . '/../../services/BookedServiceService.php');
require_once(__DIR__ . '/../../services/IServiceService.php');
require_once(__DIR__ . '/../../services/ServiceService.php');

class ManagerBookedServiceController {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function index() {
        $this->showManagerBookedServicePage();
    }

    public function showManagerBookedServicePage() {
        $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
        $bookedServices = $bookedServiceSer->getAllBookedServices();

        $serviceSer = new ServiceService(new ServiceRepository($this->conn));
        $services = $serviceSer->getAllServices();
        $serviceMap = [];
        foreach ($services as $s) {
            $serviceMap[$s->getId()] = $s->getServiceName();
        }

        $editBookedService = $_SESSION['editBookedService'] ?? null;
        include(__DIR__ . '/../../views/admin/managerBookedService.php'); 
    }

    public function editBookedService($id) {
        $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
        $bookedService = $bookedServiceSer->findById($id);
        
        if ($bookedService) {
            $_SESSION['editBookedService'] = $bookedService;
            $this->showManagerBookedServicePage();
            exit();
        } else {
            echo "Không tìm thấy dịch vụ đã đặt.";
        }
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $time = $_POST['time'] ?? '';
            $user_id  = $_POST['user_id'] ?? '';
            $service_id  = $_POST['service_id'] ?? '';
            $bookedService = new BookedService(null, $time, $user_id, $service_id);
            $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
            $bookedServiceSer->save($bookedService);

            header('Location: /index.php?controller=managerbookedService');
            exit();
        }
    }

    public function updateBookedService() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $time = $_POST['time'] ?? '';
            $user_id  = $_POST['user_id'] ?? '';
            $service_id  = $_POST['service_id'] ?? '';

            if (!$id) {
                echo "ID không hợp lệ!";
                return;
            }

            $updateBookedService = new BookedService($id, $time, $user_id, $service_id);
            $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
            try {
                $bookedServiceSer->updateBookedService($id, $updateBookedService);
                header('Location: /index.php?controller=managerbookedService');
                exit();
            } catch (Exception $e) {
                echo "Lỗi cập nhật: " . $e->getMessage();
            }
        }
    }

    public function deleteBookedService($id) {
        $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));
        $bookedServiceSer->deleteBookedService($id);
        header('Location: /index.php?controller=managerbookedService');
        exit();
    }
    public function searchBookedService() {
        $keyword = $_GET['keyword'] ?? '';
        $bookedServiceSer = new BookedServiceService(new BookedServiceRepository($this->conn));

        try {
            $bookedServices = (!empty($keyword)) 
                ? $bookedServiceSer->findByUserId($keyword) 
                : $bookedServiceSer->getAllBookedServices();
        } catch (Exception $e) {
            $bookedServices = []; // Không tìm thấy => hiện thông báo trong view
        }

        $editBookedService = $_SESSION['editBookedService'] ?? null;
        include(__DIR__ . '/../../views/admin/managerBookedService.php');
        unset($_SESSION['editBookedService']);
        exit();
    }
}
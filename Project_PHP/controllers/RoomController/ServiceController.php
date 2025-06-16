<?php
include_once('services/ServiceService.php');

class ServiceController extends BaseController {
    private $conn;
    private $serviceService;

    public function __construct($conn) {
        session_start();
        $this->conn = $conn;
        $this->folder = 'service';
        $this->serviceService = new ServiceService(new ServiceRepository($this->conn));
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $service_name = $_POST['service_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;

            $service = new Service(null, $service_name, $description, $price);
            $this->serviceService->save($service);

            header('Location: /admin/managerService.php');
            exit();
        }
    }

    public function deleteService($id) {
        $this->serviceService->deleteService($id);
        header('Location: /admin/managerService.php');
        exit();
    }

    public function updateService() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $service_name = $_POST['service_name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;

            if ($id === null) {
                echo "ID không được để trống!";
                return;
            }

            $newData = new Service($id, $service_name, $description, $price);

            try {
                $this->serviceService->updateService($id, $newData);
                header('Location: /admin/managerService.php');
                exit();
            } catch (Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ!";
        }
    }

    public function findById($id) {
        return $this->serviceService->findById($id);
    }

    public function findByServiceName($service_name) {
        return $this->serviceService->findByServiceName($service_name);
    }

    public function getAllServices() {
        return $this->serviceService->getAllServices();
    }
    public function search() {
    $service_name = $_GET['service_name'] ?? '';
    $services = [];
    $newService = new Service(null, '', '', 0);  

    try {
        $service = $this->serviceService->findByServiceName($service_name);
        $services[] = $service;
    } catch (Exception $e) {
        $services = null; 
    }

    include_once __DIR__ . '/../views/services.php';
}

}
?>

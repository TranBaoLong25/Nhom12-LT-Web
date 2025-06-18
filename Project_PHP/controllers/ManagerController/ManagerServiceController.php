<?php
include_once(__DIR__ . '/../../services/IServiceService.php');
include_once(__DIR__ . '/../../repositories/IServiceRepository.php');
include_once(__DIR__ . '/../../services/ServiceService.php');
include_once(__DIR__ . '/../../repositories/ServiceRepository.php');
include_once(__DIR__ . '/../../models/Service.php');

class ManagerServiceController {
    private $conn;

    public function __construct($conn) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->conn = $conn;
    }

    public function showManagerServicePage() {
        $serviceSer = new ServiceService(new ServiceRepository($this->conn));
        $services = $serviceSer->getAllServices();
        $editService = $_SESSION['editService'] ?? null;
        include(__DIR__ . '/../../views/admin/managerService.php');
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['service_name'] ?? '';
            $desc = $_POST['service_description'] ?? '';
            $price = $_POST['service_price'] ?? 0;

            $service = new Service(null, $name, $desc, $price);
            $serviceSer = new ServiceService(new ServiceRepository($this->conn));
            $serviceSer->save($service);

            header('Location: /index.php?controller=managerService');
            exit();
        }
    }

    public function edit($id) {
        $serviceSer = new ServiceService(new ServiceRepository($this->conn));
        $service = $serviceSer->findById($id);

        if ($service) {
            $_SESSION['editService'] = $service;
            $this->showManagerServicePage();
            exit();
        } else {
            echo "Không tìm thấy dịch vụ.";
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['service_name'] ?? '';
            $desc = $_POST['service_description'] ?? '';
            $price = $_POST['service_price'] ?? 0;

            if ($id === null) {
                echo "ID không được để trống";
                return;
            }

            $service = new Service($id, $name, $desc, $price);
            $serviceSer = new ServiceService(new ServiceRepository($this->conn));
            try {
                $serviceSer->updateService($id, $service);
                header('Location: /index.php?controller=managerService');
                exit();
            } catch(Exception $e) {
                echo "Lỗi cập nhật: " . $e->getMessage();
            }
        } else {
            echo "Yêu cầu không hợp lệ";
        }
    }

    public function delete($id) {
        $serviceSer = new ServiceService(new ServiceRepository($this->conn));
        $serviceSer->deleteService($id);

        header('Location: /index.php?controller=managerService');
        exit();
    }

    public function search() {
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $serviceSer = new ServiceService(new ServiceRepository($this->conn));
            try {
                $service = $serviceSer->findByServiceName($keyword);
                $services = $service ? [$service] : [];
            } catch(Exception $e) {
                $services = [];
            }

            $editService = null;
            include(__DIR__ . '/../../views/admin/managerService.php');
        } else {
            echo "Thiếu từ khóa tìm kiếm.";
        }
    }
}

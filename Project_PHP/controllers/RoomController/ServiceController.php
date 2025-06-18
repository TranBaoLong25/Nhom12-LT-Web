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

    public function showAll() {
        $services = $this->serviceService->getAllServices();
        $newService = new Service(null, '', '', 0); 
        include_once __DIR__ . '/../views/services.php';
    }

    public function search() {
        $name = $_GET['name'] ?? '';
        $services = [];
        $newService = new Service(null, '', '', 0);

        try {
            $service = $this->serviceService->findByServiceName($name);
            $services[] = $service;
        } catch (Exception $e) {
            $services = null;
        }

        include_once __DIR__ . '/../views/services.php';
    }
}

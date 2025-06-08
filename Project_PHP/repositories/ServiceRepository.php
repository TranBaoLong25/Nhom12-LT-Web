<?php
class ServiceRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save(Service $service) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO services (service_name, service_description, service_price, user_id) VALUES (?, ?, ?, ?)");
            return $stmt->execute([
                $service->getServiceName(),
                $service->getServiceDescription(),
                $service->getServicePrice(),
                $service->getUserId()
            ]);
        } catch (PDOException $e) {
            echo "Lỗi khi lưu service: " . $e->getMessage();
            return false;
        }
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByServiceName($service_name) {
        $stmt = $this->conn->prepare("SELECT * FROM services WHERE service_name = ?");
        $stmt->execute([$service_name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllServices() {
        $stmt = $this->conn->query("SELECT * FROM services");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteService($id) {
        $stmt = $this->conn->prepare("DELETE FROM services WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateService($id, $newData) {
        $service = $this->findById($id);
        if ($service) {
            $stmt = $this->conn->prepare("UPDATE services SET service_name = ?, service_description = ?, service_price = ?, user_id = ? WHERE id = ?");
            $result = $stmt->execute([
                $newData->getServiceName(),
                $newData->getServiceDescription(),
                $newData->getServicePrice(),
                $newData->getUserId()
            ]);
            if (!$result) {
                throw new Exception("Lỗi khi cập nhật service.");
            }
            return $result;
        } else {
            throw new Exception("Service với ID này không tồn tại.");
        }
    }
}
?>
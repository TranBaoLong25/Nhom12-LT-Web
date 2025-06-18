<?php
class ServiceRepository implements IServiceRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save(Service $service) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO services (service_name, service_description, service_price) VALUES (?, ?, ?)");
            return $stmt->execute([
                $service->getServiceName(),
                $service->getServiceDescription(),
                $service->getServicePrice()
            ]);
        } catch (PDOException $e) {
            echo "Lỗi khi lưu service: " . $e->getMessage();
            return false;
        }
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Service(
                $row['id'],
                $row['service_name'],
                $row['service_description'],
                $row['service_price']
            );
        }

        return null;
    }

    public function findByServiceName($service_name) {
        $stmt = $this->conn->prepare("SELECT * FROM services WHERE service_name = ?");
        $stmt->execute([$service_name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Service(
                $row['id'],
                $row['service_name'],
                $row['service_description'],
                $row['service_price']
            );
        }

        return null;
    }

    public function getAllServices() {
        $stmt = $this->conn->query("SELECT * FROM services");
        $services = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $services[] = new Service(
                $row['id'],
                $row['service_name'],
                $row['service_description'],
                $row['service_price']
            );
        }

        return $services;
    }

    public function deleteService($id) {
        $stmt = $this->conn->prepare("DELETE FROM services WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateService($id, $newData) {
        $service = $this->findById($id);
        if ($service) {
            $stmt = $this->conn->prepare("UPDATE services SET service_name = ?, service_description = ?, service_price = ? WHERE id = ?");
            $result = $stmt->execute([
                $newData->getServiceName(),
                $newData->getServiceDescription(),
                $newData->getServicePrice(),
                $id
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
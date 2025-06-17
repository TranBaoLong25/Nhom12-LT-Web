<?php

class BookedServiceRepository implements IBookedServiceRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

public function save(BookedService $bookedService) {
    try {
        $stmt = $this->conn->prepare("INSERT INTO booked_service (time, user_id, service_id) VALUES (?, ?, ?)");
        $result = $stmt->execute([
            $bookedService->getTime(),
            $bookedService->getUserId(),
            $bookedService->getServiceId()
        ]);
        if (!$result) {
            // In lỗi SQL chi tiết ra màn hình (debug)
            $errorInfo = $stmt->errorInfo();
            echo "<pre>Lỗi SQL: ";
            print_r($errorInfo);
            echo "</pre>";
        }
        return $result;
    } catch (PDOException $e) {
        echo "Lỗi khi lưu BookedService: " . $e->getMessage();
        return false;
    }
}

    public function findByUserId($user_id){
        // SAI: booked_services => ĐÚNG: booked_service
        $stmt = $this->conn->prepare("
            SELECT bs.*, s.service_name, s.service_description, s.service_price
            FROM booked_service bs
            JOIN services s ON bs.service_id = s.id
            WHERE bs.user_id = ?
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM booked_service WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function getAllBookedServices() {
        $stmt = $this->conn->query("SELECT * FROM booked_service");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteBookedService($id) {
        $stmt = $this->conn->prepare("DELETE FROM booked_service WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateBookedService($id, $newData) {
        $existing = $this->findById($id);
        if ($existing) {
            $stmt = $this->conn->prepare("UPDATE booked_service SET time = ?, user_id = ?, service_id = ? WHERE id = ?");
            $result = $stmt->execute([
                $newData->getTime(),
                $newData->getUserId(),
                $newData->getServiceId(),
                $id
            ]);
            if (!$result) {
                throw new Exception("Lỗi khi cập nhật BookedService.");
            }
            return $result;
        } else {
            throw new Exception("BookedService với ID này không tồn tại.");
        }
    }
}
?>
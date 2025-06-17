<?php
require_once __DIR__ . '/../models/BookedService.php';
require_once __DIR__ . '/IBookedServiceRepository.php';

class BookedServiceRepository implements IBookedServiceRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save(BookedService $bookedService) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO booked_services (time, user_id, service_id) VALUES (?, ?, ?)");
            return $stmt->execute([
                $bookedService->getTime(),
                $bookedService->getUserId(),
                $bookedService->getServiceId()
            ]);
        } catch (PDOException $e) {
            echo "Lỗi khi lưu BookedService: " . $e->getMessage();
            return false;
        }
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM booked_services WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function getAllBookedServices() {
        $stmt = $this->conn->query("SELECT * FROM booked_services");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteBookedService($id) {
        $stmt = $this->conn->prepare("DELETE FROM booked_services WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateBookedService($id, $newData) {
        $existing = $this->findById($id);
        if ($existing) {
            $stmt = $this->conn->prepare("UPDATE booked_services SET time = ?, user_id = ?, service_id = ? WHERE id = ?");
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
    public function findByUserId($user_id){
        $stmt = $this->conn->prepare("SELECT * FROM booked_service WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach($rows as $row){
            $result[] = new BookedService(
                $row['id'],
                $row['time'],
                $row['user_id'],
                $row['service_id']
            );
        }
        if(empty($result)){
            throw new Exception("UserId: $user_id chưa đặt dịch vụ nào.");
        }
    }
}

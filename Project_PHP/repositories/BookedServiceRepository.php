<?php

class BookedServiceRepository implements IBookedServiceRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save(BookedService $bookedService) {
        $now = new DateTime();
        $time = new DateTime($bookedService->getTime());

        if ($now > $time) {
            throw new Exception("Ngày đặt dịch vụ phải sau ngày hôm nay.");
        }
        else{
        try {
            $stmt = $this->conn->prepare("INSERT INTO booked_service (time, user_id, service_id) VALUES (?, ?, ?)");
            return $stmt->execute([
                $bookedService->getTime(),
                $bookedService->getUserId(),
                $bookedService->getServiceId()
            ]);
        } catch (PDOException $e) {
            echo "Lỗi khi lưu BookedService: " . $e->getMessage();
            return false;
        }
        return $result;
    }
    }
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM booked_service WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new BookedService(
                $row['id'],
                $row['time'],
                $row['user_id'],
                $row['service_id']
            );
        }
        return null;
    }

    public function getAllBookedServices() {
        $stmt = $this->conn->query("SELECT * FROM booked_service");
        $bookedService = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $bookedService[] = new BookedService(
            $row['id'],
            $row['time'],
            $row['user_id'],
            $row['service_id']
        );
    }
        return $bookedService;
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
            return null;
        }
        else{ return $result; }
       
    }
}
?>
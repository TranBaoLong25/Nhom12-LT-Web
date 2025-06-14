<?php
class HomeStayRepository implements IHomeStayRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function save(HomeStay $homeStay) {
        $query = "INSERT INTO homestay (room_type, location, room_price, booked) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            $homeStay->getRoomType(),
            $homeStay->getLocation(),
            $homeStay->getRoomPrice(),
            $homeStay->getBooked() ? 1 : 0
        ]);

        if ($result) {
            $homeStay->setId($this->conn->lastInsertId());

            // Nếu có ảnh thì thêm
            if (is_array($homeStay->getImage())) {
                foreach ($homeStay->getImage() as $url) {
                    // Chỉ lưu tên file hoặc đường dẫn tương đối (uploads/filename.jpg)
                    $this->addImage($homeStay->getId(), $url);
                }
            }

            return true;
        }

        return false;
    }

    public function findById($id) {
        $query = "SELECT * FROM homestay WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $homeStay = new HomeStay();
        $homeStay->setId($row['id']);
        $homeStay->setRoomType($row['room_type']);
        $homeStay->setLocation($row['location']);
        $homeStay->setRoomPrice($row['room_price']);
        $homeStay->setBooked((bool)$row['booked']);
        $homeStay->setImage($this->getImagesByHomeStayId($id));

        return $homeStay;
    }

    public function getAllHomeStay() {
        $query = "SELECT * FROM homestay";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $list = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $hs = new HomeStay();
            $hs->setId($row['id']);
            $hs->setRoomType($row['room_type']);
            $hs->setLocation($row['location']);
            $hs->setRoomPrice($row['room_price']);
            $hs->setBooked((bool)$row['booked']);
            $hs->setImage($this->getImagesByHomeStayId($hs->getId()));
            $list[] = $hs;
        }
        return $list;
    }

    public function deleteRoom($id) {
        $queryImg = "DELETE FROM homestay_images WHERE homestay_id = ?";
        $stmtImg = $this->conn->prepare($queryImg);
        $stmtImg->execute([$id]);

        $query = "DELETE FROM homestay WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls = []) {
        $query = "UPDATE homestay SET room_type = ?, location = ?, room_price = ?, booked = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([$room_type, $location, $room_price, $booked ? 1 : 0, $id]);

        if ($result) {
            // Xóa ảnh cũ
            $queryDelImg = "DELETE FROM homestay_images WHERE homestay_id = ?";
            $stmtDelImg = $this->conn->prepare($queryDelImg);
            $stmtDelImg->execute([$id]);

            // Thêm ảnh mới
            foreach ($image_urls as $url) {
                $this->addImage($id, $url);
            }
        }

        return $result;
    }

    public function addImage($homeStay_id, $url) {
        $query = "INSERT INTO homestay_images (homestay_id, image_url) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$homeStay_id, $url]);
    }

    public function getImagesByHomeStayId($homeStay_id) {
        $query = "SELECT image_url FROM homestay_images WHERE homestay_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$homeStay_id]);

        $images = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $images[] = $row['image_url'];
        }
        return $images;
    }
}
?>

<?php
require_once 'BookedRoom.php';
require_once 'IBookedRoomRepository.php';

class BookedRoomRepository implements IBookedRoomRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllBookedRooms() {
        $stmt = $this->conn->query("SELECT * FROM booked_rooms");
        $bookedRooms = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bookedRooms[] = new BookedRoom(
                $row['id'],
                $row['room_name'],
                $row['customer_name'],
                $row['checkin_date'],
                $row['checkout_date'],
                $row['status']
            );
        }
        return $bookedRooms;
    }

    public function findBookedRoomById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM booked_rooms WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new BookedRoom(
                $row['id'],
                $row['room_name'],
                $row['customer_name'],
                $row['checkin_date'],
                $row['checkout_date'],
                $row['status']
            );
        }
        return null;
    }
}
?>

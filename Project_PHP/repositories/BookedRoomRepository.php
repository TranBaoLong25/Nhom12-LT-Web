<?php


class BookedRoomRepository implements IBookedRoomRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; // PDO connection
    }

    public function findById(int $id): ?BookedRoom {
        $stmt = $this->conn->prepare("SELECT * FROM booked_room WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new BookedRoom(
                $row['id'], $row['guest_name'], $row['guest_phone'],
                $row['check_in_date'], $row['check_out_date'],
                $row['user_id'], $row['homestay_id']
            );
        }
        return null;
    }

    public function findAll(): array {
        $stmt = $this->conn->query("SELECT * FROM booked_room");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[] = new BookedRoom(
                $row['id'], $row['guest_name'], $row['guest_phone'],
                $row['check_in_date'], $row['check_out_date'],
                $row['user_id'], $row['homestay_id']
            );
        }
        return $result;
    }

    public function findByUserId(int $userId): array {
        $stmt = $this->conn->prepare("SELECT * FROM booked_room WHERE user_id = ?");
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[] = new BookedRoom(
                $row['id'], $row['guest_name'], $row['guest_phone'],
                $row['check_in_date'], $row['check_out_date'],
                $row['user_id'], $row['homestay_id']
            );
        }
        return $result;
    }

    public function save(BookedRoom $bookedRoom) {
        $stmt = $this->conn->prepare(
            "INSERT INTO booked_room (guest_name, guest_phone, check_in_date, check_out_date, user_id, homestay_id) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $bookedRoom->getGuestName(),
            $bookedRoom->getGuestPhone(),
            $bookedRoom->getCheckIn(),
            $bookedRoom->getCheckOut(),
            $bookedRoom->getUserId(),
            $bookedRoom->getHomeStayId()
        ]);
        // Gán lại ID mới tạo cho bookedRoom (nếu cần)
        $bookedRoom->setId($this->conn->lastInsertId());
    }

    public function update(BookedRoom $bookedRoom): bool {
        $stmt = $this->conn->prepare(
            "UPDATE booked_room SET guest_name=?, guest_phone=?, check_in_date=?, check_out_date=?, user_id=?, homestay_id=? WHERE id=?"
        );
        return $stmt->execute([
            $bookedRoom->getGuestName(),
            $bookedRoom->getGuestPhone(),
            $bookedRoom->getCheckIn(),
            $bookedRoom->getCheckOut(),
            $bookedRoom->getUserId(),
            $bookedRoom->getHomeStayId(),
            $bookedRoom->getId()
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM booked_room WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
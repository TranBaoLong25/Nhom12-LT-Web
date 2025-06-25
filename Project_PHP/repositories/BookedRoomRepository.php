<?php

class BookedRoomRepository implements IBookedRoomRepository {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; 
    }
public function save(BookedRoom $bookedRoom) {
    $now = new DateTime(); // thời gian hiện tại
    $checkIn = new DateTime($bookedRoom->getCheckIn());
    $checkOut = new DateTime($bookedRoom->getCheckOut());

    // Kiểm tra logic ngày tháng
    if ($checkIn >= $checkOut) {
        throw new Exception("Ngày trả phòng phải sau ngày nhận phòng.");
    } elseif ($checkIn < (clone $now)->setTime(0, 0)) {
        throw new Exception("Ngày nhận phòng không được nhỏ hơn hôm nay.");
    }

    $bookedRooms = $this->getAllBookedRoom();  
    foreach ($bookedRooms as $bkr) {
        if ($bookedRoom->getHomeStayId() == $bkr->getHomeStayId()) {
            $bkCheckIn = new DateTime($bkr->getCheckIn());
            $bkCheckOut = new DateTime($bkr->getCheckOut());

            $overlap = $checkIn <= $bkCheckOut && $checkOut >= $bkCheckIn;
            if ($overlap) {
                throw new Exception("Phòng đã bị đặt trong khoảng thời gian từ " . $bkCheckIn->format('Y-m-d') . " đến " . $bkCheckOut->format('Y-m-d') . ".");
            }
        }
    }
    $query = "INSERT INTO booked_room (guest_name, guest_phone, check_in_date, check_out_date, user_id, homestay_id)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $bookedRoom->getGuestName(),
        $bookedRoom->getGuestPhone(),
        $bookedRoom->getCheckIn(),
        $bookedRoom->getCheckOut(),
        $bookedRoom->getUserId(),
        $bookedRoom->getHomeStayId()
    ]);
}

    public function findByPhone($phone) {
        $query = "SELECT * FROM booked_room WHERE guest_phone = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$phone]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new BookedRoom(
                $row['id'],
                $row['guest_name'],
                $row['guest_phone'],
                $row['check_in_date'],
                $row['check_out_date'],
                $row['user_id'],
                $row['homestay_id']
            );
        }

        return null;
    }

    public function findById($id) {
        $query = "SELECT * FROM booked_room WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new BookedRoom(
                $row['id'],
                $row['guest_name'],
                $row['guest_phone'],
                $row['check_in_date'],
                $row['check_out_date'],
                $row['user_id'],
                $row['homestay_id']
            );
        }

        return null;
    }
    public function getAllBookedRoom() {
        $query = "SELECT * FROM booked_room";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new BookedRoom(
                $row['id'],
                $row['guest_name'],
                $row['guest_phone'],
                $row['check_in_date'],
                $row['check_out_date'],
                $row['user_id'],
                $row['homestay_id']
            );
        }
        return $result;
    }

    public function deleteByPhone($phone) {
        $query = "DELETE FROM booked_room WHERE guest_phone = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$phone]);
    }

    public function deleteById($id) {
        $query = "DELETE FROM booked_room WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function updateBookedRoom($id, $newData) {
    $now = new DateTime();
    $checkIn = new DateTime($newData->getCheckIn());
    $checkOut = new DateTime($newData->getCheckOut());
    if ($checkIn >= $checkOut) {
        throw new Exception("Ngày trả phòng phải sau ngày nhận phòng.");
    } elseif ($checkIn < (clone $now)->setTime(0, 0)) {
        throw new Exception("Ngày nhận phòng không được nhỏ hơn hôm nay.");
    }
    $bookedRooms = $this->getAllBookedRoom();
    foreach ($bookedRooms as $bkr) {
        if (
            $bkr->getId() != $id &&  
            $bkr->getHomeStayId() == $newData->getHomeStayId()
        ) {
            $bkCheckIn = new DateTime($bkr->getCheckIn());
            $bkCheckOut = new DateTime($bkr->getCheckOut());

            $overlap = $checkIn <= $bkCheckOut && $checkOut >= $bkCheckIn;
            if ($overlap) {
                throw new Exception("Phòng đã bị đặt trong khoảng thời gian từ " . $bkCheckIn->format('Y-m-d') . " đến " . $bkCheckOut->format('Y-m-d') . ".");
            }
        }
    }
    $query = "UPDATE booked_room 
              SET guest_name = ?, guest_phone = ?, check_in_date = ?, check_out_date = ?, user_id = ?, homestay_id = ?
              WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([
        $newData->getGuestName(),
        $newData->getGuestPhone(),
        $newData->getCheckIn(),
        $newData->getCheckOut(),
        $newData->getUserId(),
        $newData->getHomeStayId(),
        $id
    ]);
}


    public function findByUserId($userId) {
        $query = "SELECT * FROM booked_room WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);

        $bookedRooms = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bookedRooms[] = new BookedRoom(
                $row['id'],
                $row['guest_name'],
                $row['guest_phone'],
                $row['check_in_date'],
                $row['check_out_date'],
                $row['user_id'],
                $row['homestay_id']
            );
        }
        if(empty($bookedRooms)){
            return null;
        }
        else{ return $bookedRooms;}
       
    }
}
?>

<?php

// Đảm bảo các file cần thiết được nạp
require_once __DIR__ . '/../models/BookedRoom.php';
require_once __DIR__ . '/../repositories/IBookedRoomRepository.php';

class BookedRoomRepository implements IBookedRoomRepository {
    private $bookedRooms = [];
    private $nextId = 1;

    public function __construct() {
        $this->bookedRooms[1] = new BookedRoom(1, 'Nguyen Van A', '0987654321', '2025-07-10', '2025-07-15', 101, 1);
        $this->bookedRooms[2] = new BookedRoom(2, 'Tran Thi B', '0123456789', '2025-07-12', '2025-07-14', 102, 2);
        $this->bookedRooms[3] = new BookedRoom(3, 'Le Van C', '0987654321', '2025-07-20', '2025-07-22', 101, 3);
        $this->nextId = 4;
    }

    public function save(BookedRoom $bookedRoom) { // Loại bỏ type hinting nếu interface của bạn không có
        if ($bookedRoom->getId() === null) {
            $bookedRoom->setId($this->nextId);
            $this->bookedRooms[$this->nextId] = $bookedRoom;
            $this->nextId++;
            return true;
        } else {
            if (isset($this->bookedRooms[$bookedRoom->getId()])) {
                $this->bookedRooms[$bookedRoom->getId()] = $bookedRoom;
                return true;
            }
        }
        return false;
    }

    public function findById($id) { // Loại bỏ type hinting nếu interface của bạn không có
        return $this->bookedRooms[$id] ?? null;
    }

    public function findByPhone($phone) { // Loại bỏ type hinting nếu interface của bạn không có
        $found = [];
        foreach ($this->bookedRooms as $booking) {
            if ($booking->getGuestPhone() === $phone) {
                $found[] = $booking;
            }
        }
        return $found;
    }

    public function findByUserId(int $userId): array { // Giữ nguyên nếu interface của bạn có
        $found = [];
        foreach ($this->bookedRooms as $booking) {
            if ($booking->getUserId() === $userId) {
                $found[] = $booking;
            }
        }
        return $found;
    }

    // --- CÁC THAY ĐỔI ĐỂ KHỚP VỚI INTERFACE CỦA BẠN ---

    public function getAllServices() { // Tên phương thức khớp
        return array_values($this->bookedRooms);
    }

    public function deleteService($phone) { // Tên phương thức và tham số khớp
        $deleted = false;
        foreach ($this->bookedRooms as $id => $booking) {
            if ($booking->getGuestPhone() === $phone) {
                unset($this->bookedRooms[$id]);
                $deleted = true;
                // Nếu bạn chỉ muốn xóa một cái đầu tiên tìm thấy, thêm 'break;'
                // break;
            }
        }
        return $deleted;
    }

    public function updateService($phone, $newData) { // Tên phương thức và tham số khớp
        $updated = false;
        foreach ($this->bookedRooms as $id => $booking) {
            if ($booking->getGuestPhone() === $phone) {
                // Giả định $newData là một mảng chứa dữ liệu cần cập nhật
                // Hoặc bạn có thể truyền vào một đối tượng BookedRoom đã cập nhật
                if (is_array($newData)) {
                    if (isset($newData['guest_name'])) $booking->setGuestName($newData['guest_name']);
                    if (isset($newData['check_in_date'])) $booking->setCheckIn($newData['check_in_date']);
                    if (isset($newData['check_out_date'])) $booking->setCheckOut($newData['check_out_date']);
                    if (isset($newData['user_id'])) $booking->setUserId($newData['user_id']);
                    if (isset($newData['homestay_id'])) $booking->setHomeStayId($newData['homestay_id']);
                } elseif ($newData instanceof BookedRoom) {
                    $booking->setGuestName($newData->getGuestName());
                    $booking->setGuestPhone($newData->getGuestPhone());
                    $booking->setCheckIn($newData->getCheckIn());
                    $booking->setCheckOut($newData->getCheckOut());
                    $booking->setUserId($newData->getUserId());
                    $booking->setHomeStayId($newData->getHomeStayId());
                }
                $this->bookedRooms[$id] = $booking; // Cập nhật trong mảng
                $updated = true;
                // Nếu bạn chỉ muốn cập nhật một cái đầu tiên tìm thấy, thêm 'break;'
                // break;
            }
        }
        return $updated;
    }
}
?>
<?php

require_once __DIR__ . '/../models/BookedRoom.php';
require_once __DIR__ . '/../repositories/IBookedRoomRepository.php';
require_once __DIR__ . '/../services/IBookedRoomService.php'; // Đảm bảo nạp interface của chính service

class BookedRoomService implements IBookedRoomService {
    private IBookedRoomRepository $bookedRoomRepository;

    public function __construct(IBookedRoomRepository $bookedRoomRepository) {
        $this->bookedRoomRepository = $bookedRoomRepository;
    }

    public function bookRoom(string $guestName, string $guestPhone, string $checkInDate, string $checkOutDate, int $userId, int $homestayId): ?BookedRoom {
        if (strtotime($checkInDate) >= strtotime($checkOutDate)) {
            return null;
        }
        if (strtotime($checkInDate) < strtotime(date('Y-m-d'))) {
            return null;
        }

        // Logic kiểm tra tính khả dụng của phòng nếu có HomestayRepository
        // $isRoomAvailable = $this->homestayRepository->checkAvailability($homestayId, $checkInDate, $checkOutDate);
        // if (!$isRoomAvailable) {
        //     return null;
        // }

        $newBooking = new BookedRoom(null, $guestName, $guestPhone, $checkInDate, $checkOutDate, $userId, $homestayId);

        // Gọi phương thức 'save' từ Repository (đã có trong IBookedRoomRepository của bạn)
        if ($this->bookedRoomRepository->save($newBooking)) {
            return $newBooking;
        }
        return null;
    }

    public function getBookingDetails(int $bookingId): ?BookedRoom {
        // Gọi phương thức 'findById' từ Repository (đã có trong IBookedRoomRepository của bạn)
        return $this->bookedRoomRepository->findById($bookingId);
    }

    // THÊM PHƯƠNG THỨC getUserBookings VÀO ĐÂY
    public function getUserBookings(int $userId): array {
        // Phương thức này sẽ gọi findByUserId từ repository.
        // Đảm bảo IBookedRoomRepository và BookedRoomRepository cũng có phương thức này.
        return $this->bookedRoomRepository->findByUserId($userId);
    }

    public function updateBooking(BookedRoom $bookedRoom): bool {
        // Lỗi "Undefined method 'update'" trước đó.
        // Trong IBookedRoomRepository của bạn là 'updateService($phone, $newData)'.
        // Điều này có nghĩa là bạn không thể truyền trực tiếp đối tượng BookedRoom vào phương thức update của repository.
        // Bạn cần trích xuất phone và các dữ liệu cần cập nhật.
        $guestPhone = $bookedRoom->getGuestPhone();
        $newData = [
            'guest_name' => $bookedRoom->getGuestName(),
            'check_in_date' => $bookedRoom->getCheckIn(),
            'check_out_date' => $bookedRoom->getCheckOut(),
            'user_id' => $bookedRoom->getUserId(),
            'homestay_id' => $bookedRoom->getHomeStayId(),
        ];
        return $this->bookedRoomRepository->updateService($guestPhone, $newData); // Gọi updateService
    }

    public function cancelBooking(int $bookingId): bool {
        // Để sử dụng deleteService($phone), bạn cần lấy số điện thoại từ bookingId.
        $bookingToCancel = $this->bookedRoomRepository->findById($bookingId);

        if ($bookingToCancel === null) {
            return false;
        }

        $guestPhone = $bookingToCancel->getGuestPhone();
        return $this->bookedRoomRepository->deleteService($guestPhone); // Gọi deleteService
    }

    public function getAllBookings(): array {
        // Lỗi "Undefined method 'getAllBookedRooms'" trước đó.
        // Trong IBookedRoomRepository của bạn là 'getAllServices()'.
        return $this->bookedRoomRepository->getAllServices(); // Gọi getAllServices
    }
}

?>
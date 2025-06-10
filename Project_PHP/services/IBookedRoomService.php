<?php
require_once __DIR__ . '/../models/BookedRoom.php'; 

interface IBookedRoomService {
    public function bookRoom(string $guestName, string $guestPhone, string $checkInDate, string $checkOutDate, int $userId, int $homestayId): ?BookedRoom;
    public function getBookingDetails(int $bookingId): ?BookedRoom;
    public function getUserBookings(int $userId): array;
    public function updateBooking(BookedRoom $bookedRoom): bool;
    public function cancelBooking(int $bookingId): bool;
    public function getAllBookings(): array;
}

?>
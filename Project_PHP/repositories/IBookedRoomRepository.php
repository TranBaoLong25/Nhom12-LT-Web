<?php

    interface IBookedRoomRepository{
    public function save(BookedRoom $bookedRoom );
    public function findById($id);
    public function findByPhone($phone);
    public function getAllBookedRoom();
    public function deleteBookedRoom($phone);
    public function updateBookedRoom($phone, $newData);
    }

interface IBookedRoomRepository {
    public function findById(int $id): ?BookedRoom;
    public function findAll(): array;
    public function findByUserId(int $userId): array;
    public function save(BookedRoom $bookedRoom);
    public function update(BookedRoom $bookedRoom): bool;
    public function delete(int $id): bool;
}
?>


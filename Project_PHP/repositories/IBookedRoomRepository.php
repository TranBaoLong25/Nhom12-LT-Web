<?php

interface IBookedRoomRepository {
    public function findById(int $id): ?BookedRoom;
    public function findAll(): array;
    public function findByUserId(int $userId): array;
    public function save(BookedRoom $bookedRoom);
    public function update(BookedRoom $bookedRoom): bool;
    public function delete(int $id): bool;
}
?>

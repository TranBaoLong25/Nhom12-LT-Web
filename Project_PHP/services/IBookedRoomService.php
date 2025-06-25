<?php
interface IBookedRoomService {
    public function save(BookedRoom $bookedRoom );
    public function findByPhone($phone);
    public function findById($id);
    public function getAllBookedRoom();
    public function deleteByPhone($phone);
    public function deleteById($id);
    public function updateBookedRoom($id, $newData);
    public function findByUserId($userId);
}
?>
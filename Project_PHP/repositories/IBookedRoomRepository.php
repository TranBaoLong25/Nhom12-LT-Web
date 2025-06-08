<?php
interface IBookedRoomRepository {
    public function getAllBookedRooms();
    public function findBookedRoomById($id);
}
?>

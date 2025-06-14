<?php
    interface IBookedRoomRepository{
    public function save(BookedRoom $bookedRoom );
    public function findById($id);
    public function findByPhone($phone);
    public function getAllBookedRoom();
    public function deleteBookedRoom($phone);
    public function updateBookedRoom($phone, $newData);
    }
<?php
    interface IBookedRoomRepository{
    public function save(BookedRoom $bookedRoom );
    public function findById($id);
    public function findByPhone($phone);
    public function getAllServices();
    public function deleteService($phone);
    public function updateService($phone, $newData);
    }
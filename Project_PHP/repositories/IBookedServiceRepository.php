<?php
    interface IBookedServiceRepository{
    public function save(BookedService $bookedService );
    public function findById($id);
    public function getAllBookedServices();
    public function deleteBookedService($id);
    public function updateBookedService($id, $newData);
    }
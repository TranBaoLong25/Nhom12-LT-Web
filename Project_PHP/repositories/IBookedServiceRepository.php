<?php
    interface IBookedServiceRepository{
    public function save(BookedService $bookedService );
    public function findById($id);
    public function getAllServices();
    public function deleteService($id);
    public function updateService($id, $newData);
    }
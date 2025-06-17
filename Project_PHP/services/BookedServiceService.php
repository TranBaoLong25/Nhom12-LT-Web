<?php

class BookedServiceService implements IBookedServiceService {
    private $repository;

    public function __construct(BookedServiceRepository $repository) {
        $this->repository = $repository;
    }

    public function createBookedService($time, $userId, $serviceId) {
        $bookedService = new BookedService(null, $time, $userId, $serviceId);
        return $this->repository->save($bookedService);
    }

    public function getBookedServiceById($id) {
        return $this->repository->findById($id);
    }

    public function getAllBookedServices() {
        return $this->repository->getAllServices();
    }

    public function deleteBookedService($id) {
        return $this->repository->deleteService($id);
    }

    public function updateBookedService($id, $newData) {
        return $this->repository->updateService($id, $newData);
    }
}
?>

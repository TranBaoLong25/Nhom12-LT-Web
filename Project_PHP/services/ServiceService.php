<?php
class ServiceService implements IServiceService {
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository) {
        $this->serviceRepository = $serviceRepository;
    }

    public function save(Service $service) {
        return $this->serviceRepository->save($service);
    }

    public function findById($id) {
        return $this->serviceRepository->findById($id);
    }
    public function findAll() {
        return $this->serviceRepository->findAll();
    }
    public function findByServiceName($service_name) {
        return $this->serviceRepository->findByServiceName($service_name);
    }

    public function getAllServices() {
        return $this->serviceRepository->getAllServices();
    }

    public function deleteService($id) {
        return $this->serviceRepository->deleteService($id);
    }

    public function updateService($id, $newData) {
        return $this->serviceRepository->updateService($id, $newData);
    }
}
?>
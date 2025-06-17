<?php
interface IServiceService {
    public function save(Service $service);
    public function findById($id);
    public function findAll();
    public function findByServiceName($service_name);
    public function getAllServices();
    public function deleteService($id);
    public function updateService($id, Service $newData);
}
?>
<?php
interface IServiceRepository {
    public function save(Services $service);
    public function findById($id);
    public function findAll();
    public function findByServiceName($service_name);
    public function getAllServices();
    public function deleteService($id);
    public function updateService($id, $newData);
}
?>
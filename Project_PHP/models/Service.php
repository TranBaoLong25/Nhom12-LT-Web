<?php
class Service {
    private $id; 
    private $service_name;
    private $service_description;
    private $service_price;
    private $user_id;

    public static function createEmptyService($db) {
        return new Service(null, null, null, null, null);
    }

    public function __construct($id, $service_name, $service_description, $service_price, $user_id) {
        $this->id = $id;
        $this->service_name = $service_name;
        $this->service_description = $service_description;
        $this->service_price = $service_price;
        $this->user_id = $user_id;
    }

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getServiceName() {
        return $this->service_name;
    }
    public function setServiceName($service_name) {
        $this->service_name = $service_name;
    }

    public function getServiceDescription() {
        return $this->service_description;
    }
    public function setServiceDescription($service_description) {
        $this->service_description = $service_description;
    }

    public function getServicePrice() {
        return $this->service_price;
    }
    public function setServicePrice($service_price) {
        $this->service_price = $service_price;
    }

    public function getUserId() {
        return $this->user_id;
    }
    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }
}
?>
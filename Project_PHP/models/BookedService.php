<?php
    class BookedService{
        private $id;
        private $time;
        private $user_id;
        private $service_id;
        public static function createEmptyBookedService(){
            return new BookedService(null, null, null, null);
        }
        public function __construct($id, $time, $user_id, $service_id){
            $this->id = $id;
            $this->time = $time;
            $this->user_id = $user_id;
            $this->service_id = $service_id;
        }
        public function getId(){ return $this->id;}
        public function getTime(){ return $this->time;}
        public function getUserId(){return $this->user_id;}
        public function getServiceId(){return $this->service_id;}
        public function setId($id){$this->id = $id;}
        public function setTime($time){$this->time = $time;}
        public function setUserId($user_id){$this->user_id = $user_id;}
        public function setServiceId($service_id){$this->service_id = $service_id;}
    }
?>

<?php
    class BookedRoom{
        private $id;
        private $guest_name;
        private $guest_phone;
        private $check_in_date;
        private $check_out_date;
        private $user_id;
        private $homestay_id;                 
        public static function createEmptyBookedRoom(){
            return new BookedRoom(null, null, null, null, null, null, null);
        }
        public function __construct($id, $guest_name, $guest_phone, $check_in_date, $check_out_date, $user_id, $homestay_id){
            $this->id = $id;
            $this->guest_name = $guest_name;
            $this->guest_phone = $guest_phone;
            $this->check_in_date = $check_in_date;
            $this->check_out_date = $check_out_date;
            $this->user_id = $user_id;
            $this->homestay_id = $homestay_id;
        }
        public function getId(){ return $this->id;}
        public function getGuestName(){ return $this->guest_name;}
        public function getGuestPhone(){return $this->guest_phone;}
        public function getCheckIn(){ return $this->check_in_date;}
        public function getCheckOut(){ return $this->check_out_date;}
        public function getUserId(){return $this->user_id;}
        public function getHomeStayId(){return $this->homestay_id;}
        public function setId($id){$this->id = $id;}
        public function setGuestName($guest_name){ $this->guest_name = $guest_name;}
        public function setGuestPhone($guest_phone){$this->guest_phone = $guest_phone;}
        public function setCheckIn($check_in_date){$this->check_in_date = $check_in_date;}
        public function setCheckOut($check_out_date){$this->check_out_date = $check_out_date;}
        public function setUserId($user_id){$this->user_id = $user_id;}
        public function setHomeStayId($homestay_id){$this->homestay_id = $homestay_id;}
    }
?>

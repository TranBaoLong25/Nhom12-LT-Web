<?php
    class HomeStay{
        private $id;
        private $room_type;
        private $location;
        private $room_price;
        private $booked;
        private $image_urls = [];
        public static function createEmptyService(){
            return new HomeStay(null, null, null, null, null, null);
        }
        public function __construct(
            $id = null,
            $room_type = null,
            $location = null,
            $room_price = null,
            $booked = false,
            $image_urls = []
        ) {
            $this->id = $id;
            $this->room_type = $room_type;
            $this->location = $location;
            $this->room_price = $room_price;
            $this->booked = $booked;
            $this->image_urls = $image_urls;
        }

        public function setId($id){
            $this->id = $id;
        }
        public function getId(){
            return $this->id;
        }
        public function setRoomType($room_type){
            $this->room_type = $room_type;
        }
        public function getRoomType(){
            return $this->room_type;
        }
        public function setRoomPrice($room_price){
            $this->room_price = $room_price;
        }
        public function getRoomPrice(){
            return $this->room_price;
        }
        public function setLocation($location){
            $this->location = $location;
        }
        public function getLocation(){
            return $this->location;
        }
        public function setImage($image_urls){
            $this->image_urls = $image_urls;
        }
        public function getImage(){
            return $this->image_urls;
        }
        public function getBooked(){
            return $this->booked;
        }
        public function setBooked($booked){
            $this->booked = $booked;
        }
    }
?>
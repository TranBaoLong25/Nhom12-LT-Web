<?php
    interface IHomeStayService{
        public function save(HomeStay $homeStay);
        public function findById($id);
        public function getAllHomeStay();
        public function deleteRoom($id);
        public function updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls = []);
        public function addImage($homeStay_id, $url);
        public function getImagesByHomeStayId($homeStay_id); 
        public function findByLocation($location);
        public function findByType($room_type);
        public function findByPrice($min, $max);
        public function searchHomeStays($room_type, $location, $minPrice, $maxPrice);
    }
?>
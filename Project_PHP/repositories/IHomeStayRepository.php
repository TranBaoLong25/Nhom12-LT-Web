<?php
    interface IHomeStayRepository{
        public function save(HomeStay $homeStay);
        public function findById($id);
        public function getAllHomeStay();
        public function deleteRoom($id);
        public function updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls = []);
        public function addImage($homeStay_id, $url);
        public function getImagesByHomeStayId($homeStay_id);
    }
?>
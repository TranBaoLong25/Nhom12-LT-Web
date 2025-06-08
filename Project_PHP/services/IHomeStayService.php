<?php
    interface IHomeStayService{
        public function save(HomeStay $homeStay);
        public function findById($id);
        public function getAllServices();
        public function deleteRoom($id);
        public function updateRoom($id, $newData);
    }
?>
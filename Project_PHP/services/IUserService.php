<?php
    interface IUserService{
        public function save(User $user);
        public function findById($id);
        public function findByUsername($username);
        public function getAllUsers();
        public function deleteUser($id);
        public function updateUser($id, $newData);
    }
?>
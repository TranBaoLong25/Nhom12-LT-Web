<?php
    interface IUserRepository{
        public function save(User $user);
        public function findById($id);
        public function findByUsername($username);
        public function getAllUsers();
        public function deleteUser($id);
        public function updateUser($id, $newData);
        public function login($username, $password);
        public function register($username, $password, $role);
        public function changePassword($username, $password, $newPassword);
    }
?>
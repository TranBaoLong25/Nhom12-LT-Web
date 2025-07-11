<?php
    class User{
        private $id; 
        private $username;
        private $password;
        private $role;
        public static function createEmptyUser() {
        return new User(null, null, null, null);
        }
        public function __construct($id, $username, $password, $role){
            $this->id = $id;
            $this->username = $username;
            $this->password = $password;
            $this->role = $role;
        }
        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id = $id;
        }
        public function getUsername(){
            return $this->username;
        }
        public function setUsername($username){
            $this->username = $username;
        }
        public function getPassword(){
            return $this->password;
        }
        public function setPassword($password){
            $this->password = $password;
        }
        public function getRole(){
            return $this->role;
        }
        public function setRole($role){
            $this->role = $role;
        }
        
    }
?>
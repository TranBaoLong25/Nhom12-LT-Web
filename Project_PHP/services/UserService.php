<?php
    class UserService implements IUserService{
        private $userRepository;
        public function __construct(UserRepository $userRepository){
            $this->userRepository = $userRepository;
        }
        public function save(User $user){
            $this->userRepository->save($user);
        }
        public function findById($id){
            return $this->userRepository->findById($id);
        }
        public function findByUsername($username){
            return $this->userRepository->findByUsername($username);
        }
        public function getAllUsers(){
            return $this->userRepository->getAllUsers();
        }
        public function deleteUser($id){
            $this->userRepository->deleteUser($id);
        }
        public function updateUser($id, $newData){
            return $this->userRepository->updateUser($id, $newData);
        }
    }
?>
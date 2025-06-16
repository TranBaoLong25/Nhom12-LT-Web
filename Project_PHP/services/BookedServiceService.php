<?php
    class BookedServiceService implements IBookedServiceService{
        private $bookedServiceRepository;
        public function __construct(BookedServiceRepository $bookedServiceRepository){
            $this->bookedServiceRepository= $bookedServiceRepository;
        }
        public function save(BookedService $bookedService){
            $this->bookedServiceRepository->save($bookedService);
        }
        public function findById($id){
            return $this->bookedServiceRepository->findById($id);
        }
        public function getAllBookedServices(){
            return $this->bookedServiceRepository->getAllBookedServices();
        }
        public function deleteBookedService($id){
            $this->bookedServiceRepository->deleteBookedService($id);
        }
        public function updateBookedService($id, $newData){
            return $this->bookedServiceRepository->updateBookedService($id, $newData);
        } 
        public function findByUserId($user_id){
            return $this->bookedServiceRepository->findByUserId($user_id);
        }
    }
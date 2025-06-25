
<?php 
    class HomeStayService implements IHomeStayService{
        private $homeStayRepository;
        public function __construct(HomeStayRepository $homeStayRepository){
            $this->homeStayRepository = $homeStayRepository;
        }
        public function save(HomeStay $homeStay){
            $this->homeStayRepository->save($homeStay);
        }
        public function findById($id){
            return $this->homeStayRepository->findById($id);
        }
        public function getAllHomeStay(){
            return $this->homeStayRepository->getAllHomeStay();
        }
        public function deleteRoom($id){
            $this->homeStayRepository->deleteRoom($id);
        }
        public function updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls = []){
            return $this->homeStayRepository->updateRoom($id, $room_type, $location, $room_price, $booked, $image_urls);
        }
        public function addImage($homeStay_id, $url){
            $this->homeStayRepository->addImage($homeStay_id, $url);
        }
        public function getImagesByHomeStayId($homeStay_id){
            return $this->homeStayRepository->getImagesByHomeStayId($homeStay_id);
        }
        
        public function findByLocation($location){
            return $this->homeStayRepository->findByLocation($location);
        }
        public function findByType($room_type){
            return $this->homeStayRepository->findByType($room_type);
        }
        public function findByPrice($min, $max){
            return $this->homeStayRepository->findByPrice($min, $max);
        }
        public function searchHomeStays($room_type, $location, $minPrice, $maxPrice){
            return $this->homeStayRepository->searchHomeStays($room_type, $location, $minPrice, $maxPrice);
        }
    }
<?php

class BookedRoomService implements IBookedRoomService {
    private $repository;

    public function __construct(IBookedRoomRepository $repository) {
        $this->repository = $repository;
    }
    public function save(BookedRoom $bookedRoom ){
        $this->repository->save($bookedRoom);
    }
    public function findByPhone($phone){
        return $this->repository->findByPhone($phone);
    }
    public function findById($id){
        return $this->repository->findById($id);
    }
    public function getAllBookedRoom(){
        return $this->repository->getAllBookedRoom();
    }
    public function deleteByPhone($phone){
        $this->repository->deleteByPhone($phone);
    }
    public function deleteById($id){
        $this->repository->deleteById($id);
    }
    public function updateBookedRoom($id, $newData){
        $this->repository->updateBookedRoom($id, $newData);
    }
    public function findByUserId($userId){
        return $this->repository->findByUserId($userId);
    }
}
?>
<?php
require_once 'IBookedRoomService.php';
require_once 'BookedRoomRepository.php';

class BookedRoomService implements IBookedRoomService {
    private $repository;

    public function __construct(IBookedRoomRepository $repository) {
        $this->repository = $repository;
    }

    public function getAllBookedRooms() {
        return $this->repository->getAllBookedRooms();
    }

    public function findBookedRoomById($id) {
        return $this->repository->findBookedRoomById($id);
    }
}
?>

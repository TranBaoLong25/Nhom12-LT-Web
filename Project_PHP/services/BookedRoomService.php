<?php

class BookedRoomService implements IBookedRoomService {
    private $repository;

    public function __construct(IBookedRoomRepository $repository) {
        $this->repository = $repository;
    }

    public function findById(int $id): ?BookedRoom {
        return $this->repository->findById($id);
    }
    public function findByPhone($phone)
    {
        return $this->repository->findByPhone($phone);
    }

    public function findAll(): array {
        return $this->repository->findAll();
    }

    public function findByUserId(int $userId): array {
        return $this->repository->findByUserId($userId);
    }

    public function save(BookedRoom $bookedRoom) {
        // Ở đây bạn có thể thêm kiểm tra logic nghiệp vụ nếu cần
        $this->repository->save($bookedRoom);
    }

    public function update(BookedRoom $bookedRoom): bool {
        // Thêm validate nếu cần
        return $this->repository->update($bookedRoom);
    }

    public function delete(int $id): bool {
        // Có thể kiểm tra điều kiện trước khi xóa
        return $this->repository->delete($id);
    }
}
?>
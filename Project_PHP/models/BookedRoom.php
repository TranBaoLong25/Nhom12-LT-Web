<?php
// your_project/models/Room.php

class Room {
    public $id;
    public $name;
    public $type;
    public $price;
    public $description;
    public $capacity;
    public $image;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->price = $data['price'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->capacity = $data['capacity'] ?? null;
        $this->image = $data['image'] ?? null;
    }

    // Các phương thức getter (tùy chọn, có thể truy cập public properties trực tiếp)
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getType() { return $this->type; }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getCapacity() { return $this->capacity; }
    public function getImage() { return $this->image; }

    // Có thể thêm các phương thức xử lý logic liên quan đến Room nếu cần
}
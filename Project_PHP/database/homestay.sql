-- Tạo database nếu chưa có
CREATE DATABASE IF NOT EXISTS homestay_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE homestay_db;

-- Bảng users
CREATE TABLE `users` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng homestay
CREATE TABLE `homestay` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `room_type` VARCHAR(50) NOT NULL,
  `location` VARCHAR(50) NOT NULL,
  `room_price` DOUBLE NOT NULL CHECK (`room_price` >= 1),
  `booked` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng homestay_images
CREATE TABLE `homestay_images` (
  `homestay_id` BIGINT(20) DEFAULT NULL,
  `image_url` VARCHAR(500) DEFAULT NULL,
  KEY (`homestay_id`),
  CONSTRAINT `homestay_images_ibfk_1` FOREIGN KEY (`homestay_id`) REFERENCES `homestay` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng services
CREATE TABLE `services` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `service_name` VARCHAR(255) NOT NULL,
  `service_description` TEXT DEFAULT NULL,
  `service_price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng booked_room
CREATE TABLE `booked_room` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `guest_name` VARCHAR(255) DEFAULT NULL,
  `guest_phone` VARCHAR(20) DEFAULT NULL,
  `check_in_date` DATE DEFAULT NULL,
  `check_out_date` DATE DEFAULT NULL,
  `homestay_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `homestay_id` (`homestay_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `booked_room_ibfk_1` FOREIGN KEY (`homestay_id`) REFERENCES `homestay` (`id`),
  CONSTRAINT `booked_room_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Bảng booked_service
CREATE TABLE `booked_service` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `time` DATE DEFAULT NULL,
  `service_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `booked_service_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  CONSTRAINT `booked_service_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO homestay (room_type, location, room_price, booked) VALUES
('standardRoom', '1st', 500000, 0),
('DeluxeRoom', '2st', 800000, 0),
('SuiteRoom', '3st', 1200000, 0),
('DormitoryRoom', '4st', 300000, 0),
('Bungalow', '5st', 1000000, 0),
('standardRoom', '2st', 550000, 0),
('DeluxeRoom', '3st', 820000, 0),
('SuiteRoom', '4st', 1250000, 0),
('DormitoryRoom', '5st', 320000, 0),
('Bungalow', '1st', 950000, 0),
('standardRoom', '3st', 530000, 0),
('DeluxeRoom', '4st', 780000, 0),
('SuiteRoom', '5st', 1150000, 0),
('DormitoryRoom', '1st', 310000, 0),
('Bungalow', '2st', 980000, 0),
('standardRoom', '4st', 520000, 0),
('DeluxeRoom', '5st', 830000, 0),
('SuiteRoom', '1st', 1230000, 0),
('DormitoryRoom', '2st', 340000, 0),
('Bungalow', '3st', 970000, 0),
('standardRoom', '5st', 560000, 0),
('DeluxeRoom', '1st', 850000, 0),
('SuiteRoom', '2st', 1100000, 0),
('DormitoryRoom', '3st', 300000, 0),
('Bungalow', '4st', 1010000, 0),
('standardRoom', '1st', 590000, 0),
('DeluxeRoom', '2st', 840000, 0),
('SuiteRoom', '3st', 1300000, 0),
('DormitoryRoom', '4st', 350000, 0),
('Bungalow', '5st', 940000, 0),
('standardRoom', '2st', 505000, 0),
('DeluxeRoom', '3st', 800000, 0),
('SuiteRoom', '4st', 1180000, 0),
('DormitoryRoom', '5st', 330000, 0),
('Bungalow', '1st', 1000000, 0),
('standardRoom', '3st', 510000, 0),
('DeluxeRoom', '4st', 790000, 0),
('SuiteRoom', '5st', 1260000, 0),
('DormitoryRoom', '1st', 325000, 0),
('Bungalow', '2st', 930000, 0),
('standardRoom', '4st', 540000, 0),
('DeluxeRoom', '5st', 870000, 0),
('SuiteRoom', '1st', 1190000, 0),
('DormitoryRoom', '2st', 360000, 0),
('Bungalow', '3st', 990000, 0),
('standardRoom', '5st', 570000, 0),
('DeluxeRoom', '1st', 890000, 0),
('SuiteRoom', '2st', 1280000, 0),
('DormitoryRoom', '3st', 345000, 0),
('Bungalow', '4st', 960000, 0);

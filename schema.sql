-- Database schema for StyleHub AI Shopping Assistant
CREATE DATABASE IF NOT EXISTS `stylehub`;
USE `stylehub`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `order` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00
);

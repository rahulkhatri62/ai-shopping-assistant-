-- Database schema for StyleHub AI Shopping Assistant
CREATE DATABASE IF NOT EXISTS `stylehub`;
USE `stylehub`;

-- 1. Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Products table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `original_price` DECIMAL(10,2) NOT NULL,
    `discount_percent` INT NOT NULL DEFAULT 0,
    `image` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `fabric` VARCHAR(100) DEFAULT 'Pure Cotton',
    `sizes` VARCHAR(100) DEFAULT 'S, M, L, XL, XXL',
    `rating` DECIMAL(2,1) DEFAULT 4.8,
    `reviews_count` INT DEFAULT 120,
    `badge` VARCHAR(50) DEFAULT 'Bestseller',
    `stock` INT DEFAULT 50,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Enhanced Order table
CREATE TABLE IF NOT EXISTS `order` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `customer_name` VARCHAR(255) DEFAULT '',
    `phone` VARCHAR(30) DEFAULT '',
    `shipping_address` TEXT,
    `city` VARCHAR(100) DEFAULT '',
    `pincode` VARCHAR(20) DEFAULT '',
    `product_name` TEXT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `coupon_code` VARCHAR(50) DEFAULT '',
    `payment_method` VARCHAR(50) DEFAULT 'Cash On Delivery',
    `status` VARCHAR(50) DEFAULT 'Pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Coupons table
CREATE TABLE IF NOT EXISTS `coupons` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('percent', 'fixed') NOT NULL,
    `discount_value` DECIMAL(10,2) NOT NULL,
    `min_order_amount` DECIMAL(10,2) DEFAULT 0.00,
    `description` VARCHAR(255),
    `is_active` TINYINT(1) DEFAULT 1
);

-- 5. Wishlist table
CREATE TABLE IF NOT EXISTS `wishlist` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `user_product` (`user_id`, `product_id`)
);

-- Seed Initial Products
TRUNCATE TABLE `products`;
INSERT INTO `products` (`id`, `name`, `category`, `price`, `original_price`, `discount_percent`, `image`, `description`, `fabric`, `sizes`, `rating`, `reviews_count`, `badge`, `stock`) VALUES
(1, 'Casual Daily Cotton Kurti', 'Daily Wear', 999.00, 1499.00, 33, 'images/kurti1.jpeg', 'Breathable pure cotton straight kurti featuring elegant neck piping. Ideal for daily office, college, and casual outings.', '100% Pure Cotton', 'S, M, L, XL, XXL', 4.8, 142, 'Best Value', 45),
(2, 'Royal Party Silk Kurti', 'Party Wear', 1499.00, 2299.00, 35, 'images/kurti2.jpeg', 'Luxurious chanderi silk kurti adorned with delicate zari embroidery. Designed for receptions, evening soirees, and weddings.', 'Chanderi Silk', 'S, M, L, XL', 4.9, 230, 'Bestseller', 30),
(3, 'Festive Embroidered Kurti', 'Festive', 1199.00, 1899.00, 37, 'images/kurti3.jpeg', 'Vibrant festive flared kurti with intricate hand-thread needlework. Perfect for Diwali, Puja ceremonies, and family gatherings.', 'Rayon Silk Blend', 'M, L, XL, XXL', 4.7, 98, 'Trending', 25),
(4, 'Printed Floral Anarkali Kurti', 'Anarkali', 899.00, 1299.00, 31, 'images/kurti4.jpeg', 'Flowy floral printed Anarkali kurti crafted from soft modal fabric. Lightweight, stylish, and comfortable for all-day wear.', 'Modal Cotton', 'S, M, L, XL, XXL', 4.6, 64, 'New Arrival', 50);

-- Seed Active Coupons
TRUNCATE TABLE `coupons`;
INSERT INTO `coupons` (`code`, `discount_type`, `discount_value`, `min_order_amount`, `description`, `is_active`) VALUES
('STYLE20', 'percent', 20.00, 500.00, 'Flat 20% OFF on all orders above ₹500', 1),
('FESTIVE100', 'fixed', 100.00, 999.00, 'Instant ₹100 Flat Discount on orders above ₹999', 1),
('WELCOME10', 'percent', 10.00, 0.00, '10% Welcome Discount for new shoppers', 1);

-- Ensure test users exist
INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES 
(1, 'test', 'test@example.com', '1234'),
(2, 'nandani', 'nandani@example.com', 'pass123')
ON DUPLICATE KEY UPDATE id=id;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2025 at 03:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `date_of_birth`, `phone_number`, `image`) VALUES
(1, 'John Doe', 'john.doe@example.com', '19857890', '1985-05-12', '1234567890', 'profile.jpg');

--
-- Triggers `admin`
--
DELIMITER $$
CREATE TRIGGER `before_admin_insert` BEFORE INSERT ON `admin` FOR EACH ROW BEGIN
    DECLARE year_of_birth CHAR(4);
    DECLARE last_four_digits CHAR(4);

    -- Extract the year from the date_of_birth
    SET year_of_birth = YEAR(NEW.date_of_birth);

    -- Extract the last 4 digits of the phone_number
    SET last_four_digits = RIGHT(NEW.phone_number, 4);

    -- Concatenate year_of_birth and last_four_digits to form the password
    SET NEW.password = CONCAT(year_of_birth, last_four_digits);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `name`, `image`, `category_id`) VALUES
(1, 'Apple', 'image1.jpg', 1),
(2, 'Samsung', 'image1.jpg', 1),
(3, 'OnePlus', 'image1.jpg', 1),
(4, 'Xiaomi', 'image1.jpg', 1),
(5, 'Huawei', 'image1.jpg', 1),
(6, 'Sony', 'image1.jpg', 2),
(7, 'LG', 'image1.jpg', 2),
(8, 'Panasonic', 'image1.jpg', 2),
(9, 'Philips', 'image1.jpg', 2),
(10, 'Toshiba', 'image1.jpg', 2),
(11, 'Intel', 'image1.jpg', 3),
(12, 'AMD', 'image1.jpg', 3),
(13, 'Dell', 'image1.jpg', 3),
(14, 'HP', 'image1.jpg', 3),
(15, 'Lenovo', 'image1.jpg', 3),
(16, 'Nike', 'image1.jpg', 4),
(17, 'Adidas', 'image1.jpg', 4),
(18, 'Puma', 'image1.jpg', 4),
(19, 'Reebok', 'image1.jpg', 4),
(20, 'Under Armour', 'image1.jpg', 4),
(21, 'L\'Oreal', 'image1.jpg', 5),
(22, 'Maybelline', 'image1.jpg', 5),
(23, 'Nivea', 'image1.jpg', 5),
(24, 'Olay', 'image1.jpg', 5),
(25, 'Neutrogena', 'image1.jpg', 5),
(26, 'Whirlpool', 'image1.jpg', 6),
(27, 'KitchenAid', 'image1.jpg', 6),
(28, 'Hamilton Beach', 'image1.jpg', 6),
(29, 'Cuisinart', 'image1.jpg', 6),
(30, 'Instant Pot', 'image1.jpg', 6),
(31, 'Ikea', 'image1.jpg', 7),
(32, 'Ashley Furniture', 'image1.jpg', 7),
(33, 'Wayfair', 'image1.jpg', 7),
(34, 'La-Z-Boy', 'image1.jpg', 7),
(35, 'West Elm', 'image1.jpg', 7),
(36, 'Samsonite', 'image1.jpg', 8),
(37, 'American Tourister', 'image1.jpg', 8),
(38, 'Delsey', 'image1.jpg', 8),
(39, 'TUMI', 'image1.jpg', 8),
(40, 'SwissGear', 'image1.jpg', 8),
(41, 'Kraft', 'image1.jpg', 9),
(42, 'Nestle', 'image1.jpg', 9),
(43, 'Heinz', 'image1.jpg', 9),
(44, 'PepsiCo', 'image1.jpg', 9),
(45, 'Unilever', 'image1.jpg', 9),
(46, 'Cadbury', 'image1.jpg', 10),
(47, 'Ferrero', 'image1.jpg', 10),
(48, 'Hershey\'s', 'image1.jpg', 10),
(49, 'Lindt', 'image1.jpg', 10),
(50, 'Mars', 'image1.jpg', 10);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 30, 5, 1, '2025-02-27 05:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'Mobiles & Tablets', 'mobiles_tablets_image.jpg'),
(2, 'TVs & Appliances', 'tvs_appliances_image.jpg'),
(3, 'Electronics', 'electronics_image.jpg'),
(4, 'Fashion', 'fashion_image.jpg'),
(5, 'Beauty', 'beauty_image.jpg'),
(6, 'Home & Kitchen', 'home_kitchen_image.jpg'),
(7, 'Furniture', 'furniture_image.jpg'),
(8, 'Travel', 'travel_image.jpg'),
(9, 'Grocery', 'grocery_image.jpg'),
(10, 'Sweet', 'sweets_image.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `mobile` int(11) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `quantity`, `mobile`, `delivery_address`, `order_date`, `status`, `total_price`, `payment_id`) VALUES
(4, 30, 5, 1, 2147483647, 'khallingi', '2025-02-27 05:12:14', 'payment not confirmed', 499.99, '');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_id` text DEFAULT NULL,
  `payer_id` text DEFAULT NULL,
  `payer_name` text DEFAULT NULL,
  `payer_email` text DEFAULT NULL,
  `item_id` text DEFAULT NULL,
  `item_name` text DEFAULT NULL,
  `currency` text DEFAULT NULL,
  `amount` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_id`, `payer_id`, `payer_name`, `payer_email`, `item_id`, `item_name`, `currency`, `amount`, `status`, `created_at`) VALUES
(0, '2ES184537P465344K', '7VJWYHPLSRJCW', 'John Doe', 'sb-10blp34003018@personal.example.com', NULL, '', 'USD', '15.99', 'Completed', '2024-11-16 22:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(50) NOT NULL,
  `category` int(11) NOT NULL,
  `brand` int(11) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `price` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `keywords`, `category`, `brand`, `image1`, `image2`, `image3`, `price`, `date`, `status`) VALUES
(1, 'iPhone 13', 'Apple iPhone 13 with A15 Bionic chip', 'smartphone, iOS, Apple', 1, 1, 'image1.jpg', 'image2.jpg', 'image3.jpg', '999.99', '2024-10-27 16:27:14', 'Available'),
(2, 'iPhone 14', 'Apple iPhone 14 with improved camera and battery life', 'smartphone, iOS, Apple', 1, 1, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1099.99', '2024-10-27 16:27:14', 'Available'),
(3, 'Sony Bravia 55 Inch', 'Sony 4K Ultra HD Smart LED TV', 'TV, 4K, Sony', 2, 6, 'image1.jpg', 'image2.jpg', 'image3.jpg', '699.99', '2024-10-27 16:27:14', 'Available'),
(4, 'Sony Bravia 65 Inch', 'Sony Bravia 65 Inch 4K HDR LED Smart TV', 'TV, 4K, HDR, Sony', 2, 6, 'image1.jpg', 'image2.jpg', 'image3.jpg', '899.99', '2024-10-27 16:27:14', 'Available'),
(5, 'Intel Core i9', '10th Gen Intel Core i9 processor', 'processor, Intel, Core i9', 3, 11, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(6, 'Intel Core i7', '10th Gen Intel Core i7 processor', 'processor, Intel, Core i7', 3, 11, 'image1.jpg', 'image2.jpg', 'image3.jpg', '399.99', '2024-10-27 16:27:14', 'Available'),
(7, 'Nike Air Max', 'Nike Air Max running shoes', 'shoes, Nike, Air Max', 4, 16, 'image1.jpg', 'image2.jpg', 'image3.jpg', '149.99', '2024-10-27 16:27:14', 'Available'),
(8, 'Nike Free Run', 'Nike Free Run shoes for running', 'shoes, Nike, Free Run', 4, 16, 'image1.jpg', 'image2.jpg', 'image3.jpg', '129.99', '2024-10-27 16:27:14', 'Available'),
(9, 'L\'Oreal Face Cream', 'Moisturizing face cream by L\'Oreal', 'face cream, skincare, L\'Oreal', 5, 21, 'image1.jpg', 'image2.jpg', 'image3.jpg', '19.99', '2024-10-27 16:27:14', 'Available'),
(10, 'L\'Oreal Shampoo', 'L\'Oreal hair care shampoo', 'shampoo, hair care, L\'Oreal', 5, 21, 'image1.jpg', 'image2.jpg', 'image3.jpg', '15.99', '2024-10-27 16:27:14', 'Available'),
(11, 'LG OLED55', 'LG 55-inch OLED Smart TV with AI ThinQ', 'TV, OLED, LG', 2, 7, 'image1.jpg', 'image2.jpg', 'image3.jpg', '999.99', '2024-10-27 16:27:14', 'Available'),
(12, 'LG Refrigerator', 'LG double door refrigerator with smart inverter compressor', 'refrigerator, LG, kitchen', 2, 7, 'image1.jpg', 'image2.jpg', 'image3.jpg', '899.99', '2024-10-27 16:27:14', 'Available'),
(13, 'AMD Ryzen 5', 'AMD Ryzen 5 processor with integrated graphics', 'processor, AMD, Ryzen 5', 3, 12, 'image1.jpg', 'image2.jpg', 'image3.jpg', '249.99', '2024-10-27 16:27:14', 'Available'),
(14, 'AMD Ryzen 7', 'AMD Ryzen 7 processor for gaming and productivity', 'processor, AMD, Ryzen 7', 3, 12, 'image1.jpg', 'image2.jpg', 'image3.jpg', '349.99', '2024-10-27 16:27:14', 'Available'),
(15, 'Adidas Ultraboost', 'Adidas Ultraboost running shoes for comfort and support', 'shoes, Adidas, Ultraboost', 4, 17, 'image1.jpg', 'image2.jpg', 'image3.jpg', '180.00', '2024-10-27 16:27:14', 'Available'),
(16, 'Adidas T-shirt', 'Adidas athletic T-shirt for sports and casual wear', 'T-shirt, Adidas, athletic', 4, 17, 'image1.jpg', 'image2.jpg', 'image3.jpg', '29.99', '2024-10-27 16:27:14', 'Available'),
(17, 'Maybelline Mascara', 'Maybelline waterproof mascara for long lashes', 'mascara, makeup, Maybelline', 5, 22, 'image1.jpg', 'image2.jpg', 'image3.jpg', '9.99', '2024-10-27 16:27:14', 'Available'),
(18, 'Maybelline Lipstick', 'Maybelline matte finish lipstick in various shades', 'lipstick, makeup, Maybelline', 5, 22, 'image1.jpg', 'image2.jpg', 'image3.jpg', '7.99', '2024-10-27 16:27:14', 'Available'),
(19, 'Whirlpool Microwave', 'Whirlpool countertop microwave oven with sensor cooking', 'microwave, kitchen, Whirlpool', 6, 26, 'image1.jpg', 'image2.jpg', 'image3.jpg', '199.99', '2024-10-27 16:27:14', 'Available'),
(20, 'Whirlpool Washing Machine', 'Whirlpool top load washing machine with 6th sense technology', 'washing machine, laundry, Whirlpool', 6, 26, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(21, 'Ikea Sofa', 'Ikea 3-seater sofa with removable covers', 'sofa, furniture, Ikea', 7, 31, 'image1.jpg', 'image2.jpg', 'image3.jpg', '249.99', '2024-10-27 16:27:14', 'Available'),
(22, 'Ikea Bed Frame', 'Ikea queen-sized bed frame with storage', 'bed frame, furniture, Ikea', 7, 31, 'image1.jpg', 'image2.jpg', 'image3.jpg', '299.99', '2024-10-27 16:27:14', 'Available'),
(23, 'Samsonite Suitcase', 'Samsonite hard-shell suitcase with TSA locks', 'suitcase, travel, Samsonite', 8, 36, 'image1.jpg', 'image2.jpg', 'image3.jpg', '129.99', '2024-10-27 16:27:14', 'Available'),
(24, 'Samsonite Backpack', 'Samsonite backpack with multiple compartments and water resistance', 'backpack, travel, Samsonite', 8, 36, 'image1.jpg', 'image2.jpg', 'image3.jpg', '79.99', '2024-10-27 16:27:14', 'Available'),
(25, 'Kraft Cheese', 'Kraft American cheese slices', 'cheese, food, Kraft', 9, 41, 'image1.jpg', 'image2.jpg', 'image3.jpg', '4.99', '2024-10-27 16:27:14', 'Available'),
(26, 'Kraft Mac & Cheese', 'Kraft classic macaroni and cheese dinner', 'macaroni, food, Kraft', 9, 41, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.99', '2024-10-27 16:27:14', 'Available'),
(27, 'Cadbury Dairy Milk', 'Classic Cadbury milk chocolate bar', 'chocolate, sweets, Cadbury', 10, 46, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.49', '2024-10-27 16:27:14', 'Available'),
(28, 'Cadbury Creme Egg', 'Cadbury Creme Egg with creamy filling', 'chocolate, sweets, Cadbury', 10, 46, 'image1.jpg', 'image2.jpg', 'image3.jpg', '0.99', '2024-10-27 16:27:14', 'Available'),
(29, 'Ikea Coffee Table', 'Modern coffee table with storage', 'table, furniture, Ikea', 7, 31, 'image1.jpg', 'image2.jpg', 'image3.jpg', '59.99', '2024-10-27 16:27:14', 'Available'),
(30, 'Ikea Dining Set', 'Wooden dining table with 4 chairs', 'dining, table, Ikea', 7, 31, 'image1.jpg', 'image2.jpg', 'image3.jpg', '199.99', '2024-10-27 16:27:14', 'Available'),
(31, 'Samsung Galaxy S21', 'Samsung smartphone with 128GB storage', 'smartphone, Samsung, Galaxy', 3, 13, 'image1.jpg', 'image2.jpg', 'image3.jpg', '799.99', '2024-10-27 16:27:14', 'Available'),
(32, 'Samsung 4K TV', 'Samsung 55-inch 4K UHD Smart TV', 'TV, Samsung, 4K', 3, 13, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(33, 'Philips Air Fryer', 'Healthy air fryer with rapid air technology', 'air fryer, Philips, kitchen', 6, 28, 'image1.jpg', 'image2.jpg', 'image3.jpg', '149.99', '2024-10-27 16:27:14', 'Available'),
(34, 'Philips Blender', 'Multi-purpose blender with 700W motor', 'blender, Philips, kitchen', 6, 28, 'image1.jpg', 'image2.jpg', 'image3.jpg', '79.99', '2024-10-27 16:27:14', 'Available'),
(35, 'Nike Air Max', 'Comfortable and stylish sneakers', 'sneakers, Nike, Air Max', 4, 16, 'image1.jpg', 'image2.jpg', 'image3.jpg', '120.00', '2024-10-27 16:27:14', 'Available'),
(36, 'Nike Dri-FIT T-shirt', 'Moisture-wicking T-shirt for sports', 'T-shirt, Nike, Dri-FIT', 4, 16, 'image1.jpg', 'image2.jpg', 'image3.jpg', '30.00', '2024-10-27 16:27:14', 'Available'),
(37, 'L\'Oréal Foundation', 'Long-lasting foundation for all-day coverage', 'foundation, makeup, L\'Oréal', 5, 23, 'image1.jpg', 'image2.jpg', 'image3.jpg', '15.99', '2024-10-27 16:27:14', 'Available'),
(38, 'L\'Oréal Shampoo', 'Smooth and nourishing shampoo', 'shampoo, haircare, L\'Oréal', 5, 23, 'image1.jpg', 'image2.jpg', 'image3.jpg', '8.99', '2024-10-27 16:27:14', 'Available'),
(39, 'Sony 65-inch 4K TV', 'Sony 4K HDR TV with X-Motion Clarity', 'TV, Sony, 4K HDR', 2, 8, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1099.99', '2024-10-27 16:27:14', 'Available'),
(40, 'Sony Soundbar', 'Sony soundbar with wireless subwoofer', 'soundbar, Sony, audio', 2, 8, 'image1.jpg', 'image2.jpg', 'image3.jpg', '299.99', '2024-10-27 16:27:14', 'Available'),
(41, 'iPhone 14', 'Latest Apple iPhone 14 with A15 chip', 'smartphone, Apple, iPhone', 1, 1, 'image1.jpg', 'image2.jpg', 'image3.jpg', '999.99', '2024-10-27 16:27:14', 'Available'),
(42, 'Samsung Galaxy S21', 'Samsung Galaxy S21 with 5G support', 'smartphone, Samsung, Galaxy', 1, 2, 'image1.jpg', 'image2.jpg', 'image3.jpg', '799.99', '2024-10-27 16:27:14', 'Available'),
(43, 'OnePlus 9', 'OnePlus 9 with Hasselblad Camera', 'smartphone, OnePlus, 5G', 1, 3, 'image1.jpg', 'image2.jpg', 'image3.jpg', '729.99', '2024-10-27 16:27:14', 'Available'),
(44, 'Xiaomi Mi 11', 'Xiaomi Mi 11 with Snapdragon 888', 'smartphone, Xiaomi, flagship', 1, 4, 'image1.jpg', 'image2.jpg', 'image3.jpg', '749.99', '2024-10-27 16:27:14', 'Available'),
(45, 'Huawei P40 Pro', 'Huawei P40 Pro with 50 MP camera', 'smartphone, Huawei, P40', 1, 5, 'image1.jpg', 'image2.jpg', 'image3.jpg', '899.99', '2024-10-27 16:27:14', 'Available'),
(46, 'Sony Bravia 55-inch', '4K UHD Smart TV from Sony', 'TV, Sony, 4K', 2, 6, 'image1.jpg', 'image2.jpg', 'image3.jpg', '699.99', '2024-10-27 16:27:14', 'Available'),
(47, 'LG Refrigerator', 'LG double-door refrigerator with freezer', 'refrigerator, LG, appliance', 2, 7, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1200.00', '2024-10-27 16:27:14', 'Available'),
(48, 'Panasonic 65-inch TV', 'Panasonic 65-inch OLED TV', 'TV, Panasonic, OLED', 2, 8, 'image1.jpg', 'image2.jpg', 'image3.jpg', '999.99', '2024-10-27 16:27:14', 'Available'),
(49, 'Philips Air Fryer', 'Philips air fryer with rapid air technology', 'air fryer, Philips, kitchen', 2, 9, 'image1.jpg', 'image2.jpg', 'image3.jpg', '199.99', '2024-10-27 16:27:14', 'Available'),
(50, 'Toshiba Smart TV', 'Toshiba 50-inch Smart TV with Alexa', 'TV, Toshiba, Smart', 2, 10, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(51, 'Intel Core i9 Processor', '10th Gen Intel i9 processor', 'processor, Intel, CPU', 3, 11, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(52, 'Dell XPS Laptop', 'Dell XPS 13, lightweight and powerful', 'laptop, Dell, XPS', 3, 13, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1199.99', '2024-10-27 16:27:14', 'Available'),
(53, 'AMD Ryzen 7', 'AMD Ryzen 7 5800X processor', 'processor, AMD, CPU', 3, 12, 'image1.jpg', 'image2.jpg', 'image3.jpg', '399.99', '2024-10-27 16:27:14', 'Available'),
(54, 'HP Spectre x360', 'HP Spectre x360 convertible laptop', 'laptop, HP, convertible', 3, 14, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1399.99', '2024-10-27 16:27:14', 'Available'),
(55, 'Lenovo ThinkPad', 'Lenovo ThinkPad X1 Carbon', 'laptop, Lenovo, business', 3, 15, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1299.99', '2024-10-27 16:27:14', 'Available'),
(56, 'Nike Air Max', 'Nike Air Max running shoes', 'shoes, Nike, running', 4, 16, 'image1.jpg', 'image2.jpg', 'image3.jpg', '129.99', '2024-10-27 16:27:14', 'Available'),
(57, 'Adidas Originals', 'Adidas Originals classic sneakers', 'shoes, Adidas, sneakers', 4, 17, 'image1.jpg', 'image2.jpg', 'image3.jpg', '89.99', '2024-10-27 16:27:14', 'Available'),
(58, 'Puma Sports T-Shirt', 'Puma sports t-shirt, breathable fabric', 't-shirt, Puma, sportswear', 4, 18, 'image1.jpg', 'image2.jpg', 'image3.jpg', '29.99', '2024-10-27 16:27:14', 'Available'),
(59, 'Reebok Classic Shoes', 'Reebok Classic sneakers, timeless design', 'shoes, Reebok, classic', 4, 19, 'image1.jpg', 'image2.jpg', 'image3.jpg', '74.99', '2024-10-27 16:27:14', 'Available'),
(60, 'Under Armour Shorts', 'Under Armour training shorts, lightweight', 'shorts, Under Armour, sports', 4, 20, 'image1.jpg', 'image2.jpg', 'image3.jpg', '39.99', '2024-10-27 16:27:14', 'Available'),
(61, 'L\'Oreal Shampoo', 'L\'Oreal Paris Elvive shampoo', 'shampoo, L\'Oreal, beauty', 5, 21, 'image1.jpg', 'image2.jpg', 'image3.jpg', '9.99', '2024-10-27 16:27:14', 'Available'),
(62, 'Maybelline Mascara', 'Maybelline New York Lash Sensational', 'mascara, Maybelline, beauty', 5, 22, 'image1.jpg', 'image2.jpg', 'image3.jpg', '6.99', '2024-10-27 16:27:14', 'Available'),
(63, 'Nivea Cream', 'Nivea moisturizing cream for dry skin', 'cream, Nivea, skincare', 5, 23, 'image1.jpg', 'image2.jpg', 'image3.jpg', '4.99', '2024-10-27 16:27:14', 'Available'),
(64, 'Olay Regenerist', 'Olay Regenerist anti-aging serum', 'serum, Olay, skincare', 5, 24, 'image1.jpg', 'image2.jpg', 'image3.jpg', '29.99', '2024-10-27 16:27:14', 'Available'),
(65, 'Neutrogena Face Wash', 'Neutrogena Oil-Free Acne Wash', 'face wash, Neutrogena, skincare', 5, 25, 'image1.jpg', 'image2.jpg', 'image3.jpg', '8.49', '2024-10-27 16:27:14', 'Available'),
(66, 'Whirlpool Washing Machine', 'Whirlpool 7kg front load washing machine', 'washing machine, Whirlpool, home appliance', 6, 26, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(67, 'KitchenAid Mixer', 'KitchenAid stand mixer, red', 'mixer, KitchenAid, kitchen appliance', 6, 27, 'image1.jpg', 'image2.jpg', 'image3.jpg', '249.99', '2024-10-27 16:27:14', 'Available'),
(68, 'Instant Pot', 'Instant Pot Duo 7-in-1 Electric Pressure Cooker', 'pressure cooker, Instant Pot, kitchen', 6, 28, 'image1.jpg', 'image2.jpg', 'image3.jpg', '89.99', '2024-10-27 16:27:14', 'Available'),
(69, 'Cuisinart Coffee Maker', 'Cuisinart 12-Cup Programmable Coffee Maker', 'coffee maker, Cuisinart, kitchen', 6, 29, 'image1.jpg', 'image2.jpg', 'image3.jpg', '79.99', '2024-10-27 16:27:14', 'Available'),
(70, 'Dyson Vacuum Cleaner', 'Dyson V11 Torque Drive cordless vacuum cleaner', 'vacuum cleaner, Dyson, home', 6, 30, 'image1.jpg', 'image2.jpg', 'image3.jpg', '599.99', '2024-10-27 16:27:14', 'Available'),
(71, 'Ikea Sofa', 'Ikea modern 3-seater sofa', 'sofa, Ikea, furniture', 7, 31, 'image1.jpg', 'image2.jpg', 'image3.jpg', '299.99', '2024-10-27 16:27:14', 'Available'),
(72, 'Ashley Furniture Bed', 'Ashley queen-sized wooden bed', 'bed, Ashley, furniture', 7, 32, 'image1.jpg', 'image2.jpg', 'image3.jpg', '399.99', '2024-10-27 16:27:14', 'Available'),
(73, 'Dining Table Set', '6-piece dining table set with chairs', 'dining table, furniture', 7, 33, 'image1.jpg', 'image2.jpg', 'image3.jpg', '599.99', '2024-10-27 16:27:14', 'Available'),
(74, 'Accent Chair', 'Contemporary accent chair for living room', 'chair, accent, furniture', 7, 34, 'image1.jpg', 'image2.jpg', 'image3.jpg', '179.99', '2024-10-27 16:27:14', 'Available'),
(75, 'Storage Ottoman', 'Storage ottoman, stylish and functional', 'ottoman, furniture', 7, 35, 'image1.jpg', 'image2.jpg', 'image3.jpg', '89.99', '2024-10-27 16:27:14', 'Available'),
(76, 'Samsonite Suitcase', 'Samsonite large rolling suitcase', 'suitcase, Samsonite, luggage', 8, 36, 'image1.jpg', 'image2.jpg', 'image3.jpg', '149.99', '2024-10-27 16:27:14', 'Available'),
(77, 'American Tourister Backpack', 'American Tourister travel backpack', 'backpack, American Tourister, travel', 8, 37, 'image1.jpg', 'image2.jpg', 'image3.jpg', '59.99', '2024-10-27 16:27:14', 'Available'),
(78, 'Travelon Anti-Theft Bag', 'Travelon anti-theft crossbody bag', 'bag, Travelon, travel', 8, 38, 'image1.jpg', 'image2.jpg', 'image3.jpg', '39.99', '2024-10-27 16:27:14', 'Available'),
(79, 'Tumi Laptop Backpack', 'Tumi laptop backpack with USB port', 'backpack, Tumi, travel', 8, 39, 'image1.jpg', 'image2.jpg', 'image3.jpg', '299.99', '2024-10-27 16:27:14', 'Available'),
(80, 'Packing Cubes', 'Packing cubes set for organized travel', 'packing cubes, travel', 8, 40, 'image1.jpg', 'image2.jpg', 'image3.jpg', '19.99', '2024-10-27 16:27:14', 'Available'),
(81, 'Kraft Mac & Cheese', 'Original Kraft macaroni & cheese', 'macaroni, cheese, Kraft', 9, 41, 'image1.jpg', 'image2.jpg', 'image3.jpg', '4.99', '2024-10-27 16:27:14', 'Available'),
(82, 'Nestle Coffee', 'Nestle instant coffee, 200g', 'coffee, Nestle, beverage', 9, 42, 'image1.jpg', 'image2.jpg', 'image3.jpg', '6.49', '2024-10-27 16:27:14', 'Available'),
(83, 'Coca-Cola 12-Pack', 'Coca-Cola soda, 12-pack cans', 'soda, Coca-Cola, beverage', 9, 43, 'image1.jpg', 'image2.jpg', 'image3.jpg', '8.99', '2024-10-27 16:27:14', 'Available'),
(84, 'Peanut Butter', 'Creamy peanut butter, 16 oz', 'peanut butter, spread', 9, 44, 'image1.jpg', 'image2.jpg', 'image3.jpg', '3.49', '2024-10-27 16:27:14', 'Available'),
(85, 'Organic Almonds', 'Organic raw almonds, 1 lb', 'almonds, nuts, organic', 9, 45, 'image1.jpg', 'image2.jpg', 'image3.jpg', '10.99', '2024-10-27 16:27:14', 'Available'),
(86, 'iPhone 14', 'Latest Apple iPhone 14 with A15 chip', 'smartphone, Apple, iPhone', 1, 1, 'image1.jpg', 'image2.jpg', 'image3.jpg', '999.99', '2024-10-27 16:27:14', 'Available'),
(87, 'Samsung Galaxy S21', 'Samsung Galaxy S21 with 5G support', 'smartphone, Samsung, Galaxy', 1, 2, 'image1.jpg', 'image2.jpg', 'image3.jpg', '799.99', '2024-10-27 16:27:14', 'Available'),
(88, 'Sony Bravia 55-inch', '4K UHD Smart TV from Sony', 'TV, Sony, 4K', 2, 6, 'image1.jpg', 'image2.jpg', 'image3.jpg', '699.99', '2024-10-27 16:27:14', 'Available'),
(89, 'LG Refrigerator', 'LG double-door refrigerator with freezer', 'refrigerator, LG, appliance', 2, 7, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1200.00', '2024-10-27 16:27:14', 'Available'),
(90, 'Intel Core i9 Processor', '10th Gen Intel i9 processor', 'processor, Intel, CPU', 3, 11, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(91, 'Dell XPS Laptop', 'Dell XPS 13, lightweight and powerful', 'laptop, Dell, XPS', 3, 13, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1199.99', '2024-10-27 16:27:14', 'Available'),
(92, 'Nike Air Max', 'Nike Air Max running shoes', 'shoes, Nike, running', 4, 16, 'image1.jpg', 'image2.jpg', 'image3.jpg', '129.99', '2024-10-27 16:27:14', 'Available'),
(93, 'Adidas Originals', 'Adidas Originals classic sneakers', 'shoes, Adidas, sneakers', 4, 17, 'image1.jpg', 'image2.jpg', 'image3.jpg', '89.99', '2024-10-27 16:27:14', 'Available'),
(94, 'L\'Oreal Shampoo', 'L\'Oreal Paris Elvive shampoo', 'shampoo, L\'Oreal, beauty', 5, 21, 'image1.jpg', 'image2.jpg', 'image3.jpg', '9.99', '2024-10-27 16:27:14', 'Available'),
(95, 'Maybelline Mascara', 'Maybelline New York Lash Sensational', 'mascara, Maybelline, beauty', 5, 22, 'image1.jpg', 'image2.jpg', 'image3.jpg', '6.99', '2024-10-27 16:27:14', 'Available'),
(96, 'Whirlpool Washing Machine', 'Whirlpool 7kg front load washing machine', 'washing machine, Whirlpool, home appliance', 6, 26, 'image1.jpg', 'image2.jpg', 'image3.jpg', '499.99', '2024-10-27 16:27:14', 'Available'),
(97, 'KitchenAid Mixer', 'KitchenAid stand mixer, red', 'mixer, KitchenAid, kitchen appliance', 6, 27, 'image1.jpg', 'image2.jpg', 'image3.jpg', '249.99', '2024-10-27 16:27:14', 'Available'),
(98, 'Ikea Sofa', 'Ikea modern 3-seater sofa', 'sofa, Ikea, furniture', 7, 31, 'image1.jpg', 'image2.jpg', 'image3.jpg', '299.99', '2024-10-27 16:27:14', 'Available'),
(99, 'Ashley Furniture Bed', 'Ashley queen-sized wooden bed', 'bed, Ashley, furniture', 7, 32, 'image1.jpg', 'image2.jpg', 'image3.jpg', '399.99', '2024-10-27 16:27:14', 'Available'),
(100, 'Samsonite Suitcase', 'Samsonite large rolling suitcase', 'suitcase, Samsonite, luggage', 8, 36, 'image1.jpg', 'image2.jpg', 'image3.jpg', '149.99', '2024-10-27 16:27:14', 'Available'),
(101, 'American Tourister Backpack', 'American Tourister travel backpack', 'backpack, American Tourister, travel', 8, 37, 'image1.jpg', 'image2.jpg', 'image3.jpg', '59.99', '2024-10-27 16:27:14', 'Available'),
(102, 'Kraft Mac & Cheese', 'Original Kraft macaroni & cheese', 'macaroni, cheese, Kraft', 9, 41, 'image1.jpg', 'image2.jpg', 'image3.jpg', '4.99', '2024-10-27 16:27:14', 'Available'),
(103, 'Nestle Coffee', 'Nestle instant coffee, 200g', 'coffee, Nestle, beverage', 9, 42, 'image1.jpg', 'image2.jpg', 'image3.jpg', '6.49', '2024-10-27 16:27:14', 'Available'),
(104, 'Milk Chocolate Bar', 'Rich and creamy milk chocolate', 'chocolate,milk,sweet', 10, 46, 'image1.jpg', 'image2.jpg', 'image3.jpg', '2.99', '2024-10-27 16:27:14', 'Available'),
(105, 'Dark Chocolate Bar', 'Bitter-sweet dark chocolate', 'chocolate,dark,sweet', 10, 47, 'image1.jpg', 'image2.jpg', 'image3.jpg', '3.49', '2024-10-27 16:27:14', 'Available'),
(106, 'Strawberry Lollipop', 'Sweet and fruity strawberry lollipop', 'lollipop,candy,strawberry', 10, 48, 'image1.jpg', 'image2.jpg', 'image3.jpg', '0.99', '2024-10-27 16:27:14', 'Available'),
(107, 'Mint Candy', 'Refreshing mint candies', 'mint,candy,fresh', 10, 49, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.29', '2024-10-27 16:27:14', 'Available'),
(108, 'Fruit Gummies', 'Assorted fruit-flavored gummies', 'gummies,fruit,candy', 10, 50, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.99', '2024-10-27 16:27:14', 'Available'),
(109, 'Sour Gummies', 'Sour and sweet gummy treats', 'gummies,sour,sweet', 10, 46, 'image1.jpg', 'image2.jpg', 'image3.jpg', '2.49', '2024-10-27 16:27:14', 'Available'),
(110, 'Peppermint Mints', 'Refreshing peppermint mints', 'mint,peppermint,fresh', 10, 47, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.49', '2024-10-27 16:27:14', 'Available'),
(111, 'Spearmint Gum', 'Sugar-free spearmint gum', 'gum,spearmint,fresh', 10, 48, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.99', '2024-10-27 16:27:14', 'Available'),
(112, 'Black Licorice', 'Traditional black licorice', 'licorice,black,classic', 10, 49, 'image1.jpg', 'image2.jpg', 'image3.jpg', '2.49', '2024-10-27 16:27:14', 'Available'),
(113, 'Red Licorice', 'Sweet red licorice', 'licorice,red,sweet', 10, 50, 'image1.jpg', 'image2.jpg', 'image3.jpg', '2.49', '2024-10-27 16:27:14', 'Available'),
(114, 'Classic Marshmallows', 'Soft and fluffy marshmallows', 'marshmallow,soft,sweet', 10, 46, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.99', '2024-10-27 16:27:14', 'Available'),
(115, 'Mini Marshmallows', 'Miniature marshmallows for baking', 'marshmallow,mini,baking', 10, 47, 'image1.jpg', 'image2.jpg', 'image3.jpg', '1.79', '2024-10-27 16:27:14', 'Available'),
(116, 'Salted Caramels', 'Sweet and salty caramel candies', 'caramel,salted,sweet', 10, 48, 'image1.jpg', 'image2.jpg', 'image3.jpg', '2.99', '2024-10-27 16:27:14', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `review_image` varchar(255) DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_data`
--

CREATE TABLE `search_data` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `search_data`
--

INSERT INTO `search_data` (`id`, `title`, `description`) VALUES
(1, 'Google Search', 'Search the world\'s information, including webpages, images, and videos.'),
(2, 'PHP Tutorials', 'Learn PHP with examples and tutorials.'),
(3, 'MySQL Documentation', 'Comprehensive guide to MySQL database management.');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `mobile`, `email`, `profile_image`, `address`, `date_of_birth`, `password`, `created_at`) VALUES
(29, 'Sumeet panigrahy', '07894473241', 'sumeetpanigrahy494@gmail.com', 'smiley.png', 'Bakilikona, Nuasahi, Dengapadar', '2024-11-20', '$2y$10$kXAxSxFjALcyzFXk/jRTReiDonfOtzUfP4W2xFRihyksDxUUQfmCm', '2024-11-29 17:01:13'),
(30, 'uddhab suansia', '7377908018', 'uddhab.suansia123@gmail.com', 'Cost.png', 'khallingi', '2005-02-03', '$2y$10$HOJBnVwCVMhaJExkzaSs.uzkHz2lIM4U29SU./paoUYwCQBD/aCza', '2025-02-27 05:10:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `search_data`
--
ALTER TABLE `search_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `search_data`
--
ALTER TABLE `search_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2025 at 10:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homeland`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mypassword` varchar(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `mypassword`, `timestamp`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$10$yb7Ew4vasY7WIhyig9xspuzNcF7mOZaiP00TSF5saZRKA4p9Gwj.G', '2025-01-10 09:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `time_stamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `time_stamp`) VALUES
(1, 'Condo', '2024-12-31 04:35:39'),
(2, 'Land Property', '2024-12-31 04:35:39'),
(3, 'Commercial Building', '2024-12-31 04:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `fav`
--

CREATE TABLE `fav` (
  `id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fav`
--

INSERT INTO `fav` (`id`, `prop_id`, `user_id`, `created_at`) VALUES
(12, 1, 1, '2025-01-13 08:39:33'),
(13, 3, 1, '2025-01-13 08:40:39'),
(14, 16, 1, '2025-01-14 17:56:43');

-- --------------------------------------------------------

--
-- Table structure for table `props`
--

CREATE TABLE `props` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `beds` int(10) NOT NULL,
  `baths` int(20) NOT NULL,
  `sqft` varchar(30) NOT NULL,
  `home_type` varchar(200) NOT NULL,
  `year_built` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `price_sqft` float NOT NULL,
  `description` text NOT NULL,
  `admin_name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `virtual_tour_url` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `last_price_update` timestamp NULL DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `props`
--

INSERT INTO `props` (`id`, `name`, `location`, `image`, `price`, `beds`, `baths`, `sqft`, `home_type`, `year_built`, `type`, `price_sqft`, `description`, `admin_name`, `created_at`) VALUES
(16, '625 Nyotu Road ', 'Nyotu Ongata Rongai, Kenya', 'img_1.jpg', '4,000,000', 5, 4, '3400', 'Land Property', '2020', 'sale', 540, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda aperiam perferendis deleniti vitae asperiores accusamus tempora facilis sapiente, quas! Quos asperiores alias fugiat sunt tempora molestias quo deserunt similique sequi.\r\n\r\nNisi voluptatum error ipsum repudiandae, autem deleniti, velit dolorem enim quaerat rerum incidunt sed, qui ducimus! Tempora architecto non, eligendi vitae dolorem laudantium dolore blanditiis assumenda in eos hic unde.\r\n\r\nVoluptatum debitis cupiditate vero tempora error fugit aspernatur sint veniam laboriosam eaque eum, et hic odio quibusdam molestias corporis dicta! Beatae id magni, laudantium nulla iure ea sunt aliquam. A.', 'SudoAdmin', '2025-01-14 16:15:38'),
(18, '1234 Allstar Plaza 7', 'Matiba St Westlands Nairobi', 'img_2.jpg', '1,500,000', 5, 5, '5,500', 'Commercial Building', '2010', 'lease', 456, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda aperiam perferendis deleniti vitae asperiores accusamus tempora facilis sapiente, quas! Quos asperiores alias fugiat sunt tempora molestias quo deserunt similique sequi.\r\n\r\nNisi voluptatum error ipsum repudiandae, autem deleniti, velit dolorem enim quaerat rerum incidunt sed, qui ducimus! Tempora architecto non, eligendi vitae dolorem laudantium dolore blanditiis assumenda in eos hic unde.\r\n\r\nVoluptatum debitis cupiditate vero tempora error fugit aspernatur sint veniam laboriosam eaque eum, et hic odio quibusdam molestias corporis dicta! Beatae id magni, laudantium nulla iure ea sunt aliquam. A.', 'SudoAdmin', '2025-01-14 16:23:21'),
(19, '567 Bogani Rd', 'Bognani Rd Karen Nairobi', 'img_5.jpg', '60,000', 5, 4, '4,567', 'Condo', '2020', 'rent', 345, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda aperiam perferendis deleniti vitae asperiores accusamus tempora facilis sapiente, quas! Quos asperiores alias fugiat sunt tempora molestias quo deserunt similique sequi.\r\n\r\nNisi voluptatum error ipsum repudiandae, autem deleniti, velit dolorem enim quaerat rerum incidunt sed, qui ducimus! Tempora architecto non, eligendi vitae dolorem laudantium dolore blanditiis assumenda in eos hic unde.\r\n\r\nVoluptatum debitis cupiditate vero tempora error fugit aspernatur sint veniam laboriosam eaque eum, et hic odio quibusdam molestias corporis dicta! Beatae id magni, laudantium nulla iure ea sunt aliquam. A.', 'SudoAdmin', '2025-01-14 17:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `props_gallery`
--

CREATE TABLE `props_gallery` (
  `id` int(10) NOT NULL,
  `image` varchar(200) NOT NULL,
  `prop_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `props_gallery`
--

INSERT INTO `props_gallery` (`id`, `image`, `prop_id`) VALUES
(1, 'img_1.jpg', 1),
(2, 'img_2-1736871338.jpg', 1),
(3, 'img_3.jpg', 1),
(4, 'img_4.jpg', 2),
(5, 'img_5.jpg', 2),
(6, 'img_6.jpg', 3),
(7, 'img_7.jpg', 3),
(8, 'img_3.jpg', 3),
(9, 'img_8.jpg', 5),
(10, 'img_7-1736603545.jpg', 6),
(11, 'img_8-1736603951.jpg', 7),
(12, 'img_7-1736604092.jpg', 8),
(13, 'img_8-1736604221.jpg', 9),
(14, 'img_8-1736605442.jpg', 13),
(15, 'img_6-1736605533.jpg', 14),
(16, 'img_8-1736605888.jpg', 15),
(17, 'img_2-1736871338.jpg', 16),
(18, 'img_3-1736871338.jpg', 16),
(19, 'img_4-1736871338.jpg', 16),
(20, 'img_5-1736871338.jpg', 16),
(21, 'img_4-1736871551.jpg', 17),
(22, 'img_5-1736871551.jpg', 17),
(23, 'img_6-1736871551.jpg', 17),
(24, 'img_7-1736871551.jpg', 17),
(26, 'img_2-1736871338', 18),
(27, 'img_4-1736871801.jpg', 18),
(28, 'img_6-1736871801.jpg', 18),
(29, 'img_8-1736871801.jpg', 18),
(30, 'person_5-1736871801.jpg', 18),
(31, 'img_3-1736874440.jpg', 19),
(32, 'img_4-1736874440.jpg', 19),
(33, 'img_6-1736874440.jpg', 19),
(34, 'img_7-1736874440.jpg', 19),
(35, 'home 4-1737053811.jpg', 20),
(36, 'home 1-1737054111.jpg', 21),
(37, 'home 2-1737054111.jpg', 21),
(38, 'home 3-1737054111.jpg', 21),
(39, 'home 4-1737054111.jpg', 21);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `prop_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `agent_name` varchar(200) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `name`, `email`, `phone`, `prop_id`, `user_id`, `agent_name`, `timestamp`) VALUES
(5, 'James', 'james@gmail.com', '07324124124', 1, 1, 'Lenon', '2025-01-10 05:07:31'),
(6, 'James Kamoto', 'user@example.com', '07123456789', 16, 3, 'SudoAdmin', '2025-06-01 08:46:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mypassword` varchar(200) NOT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `notification_preferences` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `mypassword`) VALUES
(3, 'User', 'user@example.com', '$2y$10$yb7Ew4vasY7WIhyig9xspuzNcF7mOZaiP00TSF5saZRKA4p9Gwj.G');

-- --------------------------------------------------------

--
-- Table structure for table `saved_searches`
--

CREATE TABLE `saved_searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `search_name` varchar(255) NOT NULL,
  `search_params` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `saved_searches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_features`
--

CREATE TABLE `property_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `feature_name` varchar(255) NOT NULL,
  `feature_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `property_features_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_views`
--

CREATE TABLE `property_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `view_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `property_views_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE,
  CONSTRAINT `property_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_properties`
--

CREATE TABLE `saved_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `saved_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `saved_properties_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_properties_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_tours`
--

CREATE TABLE `property_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `tour_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `property_tours_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `callback_requests`
--

CREATE TABLE `callback_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `preferred_time` varchar(255) NOT NULL,
  `status` enum('pending','scheduled','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `fav`
--
ALTER TABLE `fav`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `props`
--
ALTER TABLE `props`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `props_gallery`
--
ALTER TABLE `props_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `saved_searches`
--
ALTER TABLE `saved_searches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `property_features`
--
ALTER TABLE `property_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `property_views`
--
ALTER TABLE `property_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `saved_properties`
--
ALTER TABLE `saved_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `property_tours`
--
ALTER TABLE `property_tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `callback_requests`
--
ALTER TABLE `callback_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `fav`
--
ALTER TABLE `fav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `props`
--
ALTER TABLE `props`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `props_gallery`
--
ALTER TABLE `props_gallery`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `saved_searches`
--
ALTER TABLE `saved_searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_features`
--
ALTER TABLE `property_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_views`
--
ALTER TABLE `property_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_properties`
--
ALTER TABLE `saved_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_tours`
--
ALTER TABLE `property_tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `callback_requests`
--
ALTER TABLE `callback_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `saved_searches`
--
ALTER TABLE `saved_searches`
  ADD CONSTRAINT `saved_searches_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_features`
--
ALTER TABLE `property_features`
  ADD CONSTRAINT `property_features_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_views`
--
ALTER TABLE `property_views`
  ADD CONSTRAINT `property_views_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_properties`
--
ALTER TABLE `saved_properties`
  ADD CONSTRAINT `saved_properties_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_properties_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_tours`
--
ALTER TABLE `property_tours`
  ADD CONSTRAINT `property_tours_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `props` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

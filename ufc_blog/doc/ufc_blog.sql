-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 02:37 AM
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
-- Database: `ufc_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(1, 4, 2, 'super excited\r\n', '2024-12-09 10:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `event_date`, `location`, `description`, `image_url`, `created_at`) VALUES
(2, 'UFC 300: Pereira vs Hill', '2024-04-13 22:00:00', 'T-Mobile Arena, Las Vegas', 'Historic milestone event headlined by Light Heavyweight Championship bout between Alex Pereira and Jamahal Hill. Co-main event features Zhang Weili vs Yan Xiaonan.', NULL, '2024-12-08 17:21:06'),
(3, 'UFC Fight Night: Dolidze vs Imavov', '2024-05-18 20:00:00', 'UFC APEX, Las Vegas', 'Middleweight showdown between Roman Dolidze and Nassourdine Imavov headlines an action-packed fight night.', NULL, '2024-12-08 17:21:06'),
(4, 'UFC 301: Pantoja vs Royval 2', '2024-06-15 22:00:00', 'Jeunesse Arena, Rio de Janeiro', 'UFC returns to Brazil with Flyweight Championship rematch between Alexandre Pantoja and Brandon Royval. Co-main event features Charles Oliveira vs Arman Tsarukyan.', NULL, '2024-12-08 17:21:06'),
(5, 'UFC 302: Edwards vs Burns', '2024-07-20 22:00:00', 'Madison Square Garden, New York', 'Welterweight Championship bout between Leon Edwards and Gilbert Burns headlines at MSG. Co-main event features Sean O\'Malley vs Marlon Vera 2 for the Bantamweight Championship.', NULL, '2024-12-08 17:21:06'),
(6, 'UFC 311: Makhachev vs Tsarukyan', '2025-01-18 22:00:00', 'Intuit Dome, Inglewood', 'The main event is a lightweight title fight between Islam Makhachev of Russia and Arman Tsarukyan of Armenia. The co-main event is a bantamweight title fight between Merab Dvalishvili of Georgia and Umar Nurmagomedov. Both should be very exciting fights with great striking and even better grappling exchanges. Watch out for many scrambles and some KO power.', NULL, '2024-12-08 17:38:46');

-- --------------------------------------------------------

--
-- Table structure for table `fighters`
--

CREATE TABLE `fighters` (
  `fighter_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `weight_class` varchar(50) NOT NULL,
  `record` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fighters`
--

INSERT INTO `fighters` (`fighter_id`, `name`, `weight_class`, `record`, `bio`, `image_url`, `created_at`) VALUES
(1, 'Jon Jones', 'Heavyweight', '27-1-0', 'One of the greatest MMA fighters of all time.', NULL, '2024-12-08 16:52:46'),
(4, 'Alexander Volkanovski', 'Featherweight', '25-3-0', 'UFC Featherweight Champion with exceptional fight IQ.', NULL, '2024-12-08 17:21:14'),
(5, 'Islam Makhachev', 'Lightweight', '24-1-0', 'Current UFC Lightweight Champion with dominant grappling.', NULL, '2024-12-08 17:21:14'),
(7, 'Jiří Procházka', 'Light Heavyweight', '30-5-1', 'Jiří Procházka is a Czech professional mixed martial artist. He currently competes in the Light Heavyweight division of the Ultimate Fighting Championship, where he is a former UFC Light Heavyweight Champion and the first Czech fighter to win a UFC championship.', '/ufc_blog/data/fighters/6757990565a40.png', '2024-12-09 20:27:33');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `post_id`, `user_id`, `created_at`) VALUES
(3, 4, 2, '2024-12-09 10:33:16'),
(12, 4, 3, '2024-12-09 10:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('event','fighter') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `content`, `category`, `image_url`, `created_at`, `updated_at`) VALUES
(4, 3, 'UFC 311 Announcement', 'Great fights announced. Very excited to see Islam Vs. Arman 2. Will be a great fight, very exciting striking and scrambles. Hope to see Arman win and face Charles for a belt. Also excited to see Jiri Vs Jamal. Great striking match and hope to see Jiri get back in the win column.', 'event', '/ufc_blog/data/posts/675708cc5424f.png', '2024-12-09 10:12:12', '2024-12-09 10:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `ufc_history`
--

CREATE TABLE `ufc_history` (
  `fact_id` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `fact` text NOT NULL,
  `event_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ufc_history`
--

INSERT INTO `ufc_history` (`fact_id`, `month`, `day`, `year`, `fact`, `event_name`) VALUES
(1, 12, 8, 2012, 'Benson Henderson defended his Lightweight Championship against Nate Diaz at UFC on Fox 5.', 'UFC on Fox 5'),
(2, 12, 9, 2007, 'Roger Huerta became the first UFC fighter to appear on the cover of Sports Illustrated magazine.', NULL),
(3, 12, 10, 2023, 'Leon Edwards successfully defended his Welterweight Championship against Colby Covington at UFC 296.', 'UFC 296'),
(4, 12, 31, 2016, 'Amanda Nunes defeated Ronda Rousey in 48 seconds at UFC 207, marking Rousey\'s final MMA fight.', 'UFC 207');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `created_at` datetime DEFAULT current_timestamp(),
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `created_at`, `remember_token`) VALUES
(2, 'helmscd', 'helmsc1@nku.edu', '$2y$10$p7Dkzul3hqbpMWspsFLUGOcCINHnASSJGMoWn5eOGqpLvFk5.46Xi', 'member', '2024-12-08 16:53:55', NULL),
(3, 'yamos17', '17yamos@gmail.com', '$2y$10$axMnn6rlKxWpr2XaY7oTbOpZJCyGS/tDQ97wUaiBr5XEuzUzKMiSe', 'admin', '2024-12-08 17:12:09', NULL),
(7, 'mmafan227', '22helmsc@badinhs.org', '$2y$10$DN3vdi36jcHxEvG57jFfjOEDTVS9qrRrzVDzbCs6Mdq4qRlL68F2q', 'member', '2024-12-09 20:24:14', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `fighters`
--
ALTER TABLE `fighters`
  ADD PRIMARY KEY (`fighter_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ufc_history`
--
ALTER TABLE `ufc_history`
  ADD PRIMARY KEY (`fact_id`),
  ADD UNIQUE KEY `unique_date` (`month`,`day`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `fighters`
--
ALTER TABLE `fighters`
  MODIFY `fighter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ufc_history`
--
ALTER TABLE `ufc_history`
  MODIFY `fact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

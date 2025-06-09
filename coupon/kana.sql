-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-04 08:14:38
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `my_db`
--

-- --------------------------------------------------------

--
-- 資料表結構 `msgs`
--

CREATE TABLE `msgs` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `is_valid` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `msgs`
--

INSERT INTO `msgs` (`id`, `name`, `category_id`, `content`, `create_at`, `update_at`, `end_at`, `is_valid`) VALUES
(2, '123', 1, '456', '2025-05-26 14:21:00', '2025-05-28 11:21:17', NULL, 1),
(3, 'wdfwqf', 3, 'awfqr rrc214', '2025-05-26 14:21:00', '2025-05-28 11:21:22', NULL, 0),
(4, 'aaaa', NULL, '123', '2025-05-26 14:21:20', NULL, NULL, 1),
(7, 'reaper', 1, 'diedieeidieee', '2025-05-27 09:41:21', '2025-05-28 11:20:50', NULL, 1),
(8, '5', NULL, 'trryu5nu5n', '2025-05-27 10:47:32', NULL, NULL, 1),
(9, 'Kana', 4, '想回家', '2025-05-28 09:39:05', '2025-05-28 13:57:21', NULL, 1),
(10, 'REAPER', 1, 'RUBY CHAN HI~', '2025-05-28 09:45:21', NULL, '2025-05-28 11:18:26', 1),
(11, '5', 2, 'ｗｑｗｒ　　ｒｒ', '2025-05-28 10:30:26', NULL, NULL, 1),
(12, '喵', 2, '想打鬥陣', '2025-05-28 11:11:07', NULL, NULL, 1),
(13, '喵', 3, '想吃烏龍麵', '2025-05-28 11:11:19', NULL, NULL, 1),
(14, '喵', 4, '上線', '2025-05-28 11:11:30', NULL, NULL, 1),
(15, '鼠泥', 1, '上線', '2025-05-28 11:18:16', NULL, NULL, 1),
(16, '生煎包', 1, '沒熟', '2025-05-28 11:21:11', NULL, NULL, 1),
(17, '123', 2, '456784454', '2025-05-28 11:25:31', NULL, NULL, 1),
(18, 'r rrexcr3rg', 3, 'overqatcgth', '2025-05-28 11:25:48', NULL, NULL, 1),
(19, '242333244', 4, 'ruby chan ', '2025-05-28 11:25:59', NULL, NULL, 1),
(20, '1', 4, '2', '2025-05-28 11:27:14', NULL, NULL, 1),
(21, '3', 1, '4', '2025-05-28 11:27:47', NULL, NULL, 1),
(22, '5', 2, '6', '2025-05-28 11:27:47', NULL, NULL, 1),
(23, '7', 3, '8', '2025-05-28 11:27:47', NULL, NULL, 1),
(24, '9', 4, '10', '2025-05-28 11:27:47', NULL, NULL, 1),
(25, '11', 1, '12', '2025-05-28 11:27:47', NULL, NULL, 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `msgs`
--
ALTER TABLE `msgs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `msgs`
--
ALTER TABLE `msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `msgs`
--
ALTER TABLE `msgs`
  ADD CONSTRAINT `msgs_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

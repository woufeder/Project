-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-04 08:14:41
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
-- 資料庫： `s_db`
--

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `account` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `img` varchar(50) DEFAULT NULL,
  `is_valid` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `name`, `account`, `password`, `gender_id`, `email`, `phone`, `city_id`, `date_of_birth`, `create_at`, `update_at`, `img`, `is_valid`) VALUES
(1, '王小明', 'xiaoming', 'pass123', 1, 'xiaoming@example.com', '0912345678', 1, '1995-06-15', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user1.jpg', 1),
(2, '李小美', 'xiaomei', '123456', 2, 'xiaomei@example.com', '0922333444', 2, '1998-09-20', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user2.jpg', 1),
(3, '陳志偉', 'zhiwei', 'abc123', 1, 'zhiwei@example.com', '0933222111', 3, '1990-02-10', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user3.jpg', 1),
(4, '張婷婷', 'tingting', 'ting789', 2, 'tingting@example.com', '0955666777', 1, '1997-12-05', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user4.jpg', 1),
(5, '林立成', 'licheng', 'pass789', 1, 'licheng@example.com', '0966888999', 2, '1988-08-08', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user5.jpg', 1),
(6, '陳怡君', 'yijun', 'yijun321', 2, 'yijun@example.com', '0922111333', 4, '1993-04-12', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user6.jpg', 1),
(7, '黃俊傑', 'junjie', 'jjpass456', 1, 'junjie@example.com', '0933444555', 5, '1991-07-23', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user7.jpg', 1),
(8, '劉雅婷', 'yating', 'ytpass789', 2, 'yating@example.com', '0955123456', 6, '1996-01-30', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user8.jpg', 1),
(9, '吳宗憲', 'zongxian', 'wz123123', 3, 'zongxian@example.com', '0911555666', 7, '1985-11-05', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user9.jpg', 1),
(10, '林佩雯', 'peiwen', 'pw321321', 2, 'peiwen@example.com', '0977888999', 8, '1999-06-18', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user10.jpg', 1),
(11, '蔡承翰', 'chenghan', 'hanhan456', 1, 'chenghan@example.com', '0988777666', 9, '1987-09-11', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user11.jpg', 1),
(12, '張書豪', 'shuhao', 'shaopass', 1, 'shuhao@example.com', '0922999888', 10, '1992-03-25', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user12.jpg', 1),
(13, '江品妤', 'pinyu', 'pinyu999', 3, 'pinyu@example.com', '0933111222', 11, '1994-08-07', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user13.jpg', 1),
(14, '王宇翔', 'yuxiang', 'yxpass888', 1, 'yuxiang@example.com', '0966333444', 12, '1990-12-14', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user14.jpg', 1),
(15, '李思涵', 'sihan', 'sihan000', 2, 'sihan@example.com', '0955777888', 13, '1995-02-28', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user15.jpg', 1),
(16, '賴政憲', 'zhengxian', 'zxadmin', 1, 'zhengxian@example.com', '0911666555', 14, '1989-05-21', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user16.jpg', 1),
(17, '鄭雅芳', 'yafang', 'yafang123', 2, 'yafang@example.com', '0922444777', 15, '1997-10-02', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user17.jpg', 1),
(18, '高家豪', 'jiahao', 'gh987654', 1, 'jiahao@example.com', '0944333222', 16, '1986-06-03', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user18.jpg', 1),
(19, '簡珮甄', 'peizhen', 'pz123abc', 2, 'peizhen@example.com', '0977444111', 17, '1993-09-19', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user19.jpg', 1),
(20, '林建宏', 'jianhong', 'jh456xyz', 1, 'jianhong@example.com', '0966111000', 18, '1990-01-09', '2025-06-03 13:07:37', '2025-06-03 13:07:37', 'user20.jpg', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `city_id` (`city_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

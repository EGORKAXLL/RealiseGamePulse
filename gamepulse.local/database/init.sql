-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.4:3306
-- Время создания: Май 14 2026 г., 20:49
-- Версия сервера: 8.4.7
-- Версия PHP: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gamepulse`
--

-- --------------------------------------------------------

--
-- Структура таблицы `friends`
--

CREATE TABLE `friends` (
  `id` int NOT NULL,
  `gamer1_id` int NOT NULL,
  `gamer2_id` int NOT NULL,
  `status` enum('pending','accepted','blocked') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `friends`
--

INSERT INTO `friends` (`id`, `gamer1_id`, `gamer2_id`, `status`, `created_at`) VALUES
(1, 2, 1, 'accepted', '2026-03-24 13:22:06'),
(2, 5, 4, 'accepted', '2026-04-12 21:05:32');

-- --------------------------------------------------------

--
-- Структура таблицы `gamers`
--

CREATE TABLE `gamers` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('gamer','admin') DEFAULT 'gamer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `gamers`
--

INSERT INTO `gamers` (`id`, `username`, `email`, `password_hash`, `created_at`, `role`) VALUES
(1, 'admin', 'admin@gamepulse.com', '$2y$10$YourHashedPasswordHere', '2026-03-20 15:36:59', 'admin'),
(2, 'gamer', 'gamer@gamepulse.com', '$2y$10$YourHashedPasswordHere', '2026-03-20 15:37:00', 'gamer'),
(4, 'sinejkinpr', 'pavel.sineikin@yandex.ru', '$2y$12$UtQgY9Fac2GW0ol96q5HLOZ7wlfLyvaZivAWd77AHfpj0tNkjTB9.', '2026-04-02 11:18:35', 'admin'),
(5, 'egorzln', 'egorzln@gmail.com', '$2y$12$Cxs9qlko2oegpw2ZNBSun.lNE746pde7HbM9T6ZojxO0hiDi8bXWq', '2026-04-12 20:51:52', 'gamer');

-- --------------------------------------------------------

--
-- Структура таблицы `games`
--

CREATE TABLE `games` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `developer` varchar(100) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `description` text,
  `release_year` year DEFAULT NULL,
  `admin_id` int DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `games`
--

INSERT INTO `games` (`id`, `title`, `developer`, `genre`, `description`, `release_year`, `admin_id`, `cover_image`) VALUES
(1, 'Hollow Knight', 'Team Cherry', 'Metroidvania', 'Приключение в подземном королевстве', '2017', 1, NULL),
(2, 'Disco Elysium', 'ZA/UM', 'RPG', 'Детективная RPG с уникальной механикой', '2019', 1, NULL),
(3, 'Baldur\'s Gate 3', 'Larian Studios', 'CRPG', 'Эпическая RPG по мотивам D&D', '2023', 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `gamer_id` int NOT NULL,
  `game_id` int NOT NULL,
  `review_text` text,
  `rating` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `gamer_id`, `game_id`, `review_text`, `rating`, `created_at`) VALUES
(3, 4, 1, 'кайф', 5, '2026-04-20 20:55:22');

-- --------------------------------------------------------

--
-- Структура таблицы `user_games`
--

CREATE TABLE `user_games` (
  `id` int NOT NULL,
  `gamer_id` int NOT NULL,
  `game_id` int NOT NULL,
  `status` enum('playing','completed','planned','dropped') DEFAULT 'planned',
  `rating` tinyint DEFAULT NULL,
  `notes` text,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Дамп данных таблицы `user_games`
--

INSERT INTO `user_games` (`id`, `gamer_id`, `game_id`, `status`, `rating`, `notes`, `added_at`) VALUES
(1, 2, 3, 'playing', 5, 'Тестовая запись', '2026-03-24 13:22:06'),
(2, 4, 1, 'planned', 5, NULL, '2026-04-12 20:46:15'),
(5, 5, 1, 'planned', 5, NULL, '2026-04-12 20:52:04'),
(6, 4, 2, 'planned', 4, '', '2026-04-12 21:33:30'),
(7, 5, 2, 'planned', 3, '', '2026-04-12 21:34:07'),
(8, 5, 3, 'planned', NULL, '', '2026-04-12 21:37:12');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_friendship` (`gamer1_id`,`gamer2_id`),
  ADD KEY `gamer2_id` (`gamer2_id`);

--
-- Индексы таблицы `gamers`
--
ALTER TABLE `gamers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_game_review` (`gamer_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Индексы таблицы `user_games`
--
ALTER TABLE `user_games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_game` (`gamer_id`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `gamers`
--
ALTER TABLE `gamers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `games`
--
ALTER TABLE `games`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_games`
--
ALTER TABLE `user_games`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`gamer1_id`) REFERENCES `gamers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`gamer2_id`) REFERENCES `gamers` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `gamers` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`gamer_id`) REFERENCES `gamers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_games`
--
ALTER TABLE `user_games`
  ADD CONSTRAINT `user_games_ibfk_1` FOREIGN KEY (`gamer_id`) REFERENCES `gamers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_games_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

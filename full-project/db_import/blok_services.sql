-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 10, 2025 at 12:25 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blok_services`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_user_id` int(10) UNSIGNED NOT NULL,
  `reciver_user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `games`
--

CREATE TABLE `games` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(60) NOT NULL,
  `game_path` text NOT NULL,
  `image_path` text DEFAULT NULL,
  `description` text NOT NULL,
  `type` enum('Action','Adventure','RPG','Strategy','Simulation','Puzzle','Sports','Racing','Fighting','Horror','Indie','Casual','MMORPG','MOBA','FPS','TPS','Board game','Card game','Party game','Educational','Other') NOT NULL,
  `average_rating` float DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `title`, `game_path`, `image_path`, `description`, `type`, `average_rating`, `creation_date`) VALUES
(1, 'Agar.io', 'https://agar.io/', 'https://imgs.crazygames.com/agario/20230719092731/agario-cover?metadata=none&quality=40&width=1200&height=630&fit=crop', 'Pożeraj mniejsze komórki i unikaj większych w dynamicznej grze multiplayer', 'Action', 4.5, '2015-04-28 00:00:00'),
(2, 'Krunker.io', 'https://krunker.io/', 'https://images.crazygames.com/games/krunker-io/cover-1591336739727.png?auto=format,compress&q=75&cs=strip', 'Szybki FPS z retro grafiką i niskoopóźnieniowym gameplayem', 'FPS', 4.7, '2018-05-01 00:00:00'),
(3, 'Diep.io', 'https://diep.io/', 'https://cdn2.fptshop.com.vn/unsafe/1920x0/filters:format(webp):quality(75)/2024_2_15_638436331980405786_diep-io-thum.jpg', 'Steruj czołgiem, zbieraj punkty i ewoluuj w potężną maszynę', 'Action', 4.6, '2016-06-15 00:00:00'),
(4, 'Surviv.io', 'https://suroi.io', 'https://tcf.admeen.org/game/17500/17345/400x246/surviv-io.jpg', '2D battle royale z 50 graczami na mapie pełnej broni', '', 4.3, '2017-11-01 00:00:00'),
(5, 'GeoGuessr', 'https://www.geoguessr.com/', 'https://geoguessr.io/data/image/posts/geoguessr-free-banner2.jpg', 'Rozpoznaj lokalizację na podstawie Google Street View', 'Puzzle', 4.8, '2013-05-09 00:00:00'),
(6, 'Shell Shockers', 'https://shellshock.io/', 'https://www.shellshock.io/img/previewImage_shellShockers.webp', 'Strzelanina pierwszoosobowa z postaciami-jajkami', 'FPS', 4.4, '2017-12-12 00:00:00'),
(7, 'RuneScape', 'https://www.runescape.com/', 'https://www.runescape.com/img/microsite/social-share-fb.jpg', 'Legendarny MMORPG z otwartym światem i questami', 'MMORPG', 4.9, '2001-01-04 00:00:00'),
(8, 'Bonk.io', 'https://bonk.io/', 'https://img.gamepix.com/games/bonk-io/cover/bonk-io.png?w=400&ar=16:10', 'Fizyczne pojedynki na customowych arenach', 'Action', 3.9, '2016-09-01 00:00:00'),
(9, 'Among Us', 'https://www.innersloth.com/games/among-us/', 'https://upload.wikimedia.org/wikipedia/en/thumb/9/9a/Among_Us_cover_art.jpg/220px-Among_Us_cover_art.jpg', 'Wykryj oszustów wśród członków załogi statku kosmicznego', 'Party game', 4.8, '2018-06-15 00:00:00'),
(10, 'Dota 2', 'https://www.dota2.com/', 'https://cdn.akamai.steamstatic.com/apps/dota2/images/dota_react/heroes/social/wisp.jpg', 'Rywalizacja drużynowa w klimacie fantasy (lite wersja przeglądarkowa)', 'MOBA', 4.7, '2013-07-09 00:00:00'),
(11, 'Slither.io', 'https://slither.io/', 'https://slither.io/s/fbthumb3.jpg', 'Klasyczny wąż w wersji multiplayer z globalnym rankingiem', 'Action', 4.5, '2016-03-25 00:00:00'),
(12, 'Wings.io', 'https://wings.io/', 'https://i.ytimg.com/vi/s0pJofmoCUo/maxresdefault.jpg', 'Bitwy powietrzne w minimalistycznym stylu', 'Racing', 3.8, '2016-11-01 00:00:00'),
(13, 'ZombsRoyale.io', 'https://zombsroyale.io/', 'https://images.crazygames.com/games/zombsroyaleio/cover-1587299840102.png?auto=format,compress&q=75&cs=strip', '100 graczy, 2D mapa i walka do ostatniego przetrwania', '', 4.2, '2018-02-14 00:00:00'),
(14, 'MooMoo.io', 'https://moomoo.io/', 'https://images.crazygames.com/games/moomooio/cover-1616832340489.png?auto=format,compress&q=75&cs=strip', 'Zbieraj zasoby, buduj bazy i przetrwaj w dziczy', 'Simulation', 4, '2017-03-03 00:00:00'),
(15, 'Ev.io', 'https://ev.io/', 'https://ev.io/themes/ev/images/ev-io-og-image.png', 'Cyberpunkowy FPS z futurystycznym uzbrojeniem', 'FPS', 4.1, '2020-05-20 00:00:00'),
(16, 'Chess.com', 'https://www.chess.com/play/online', 'https://images.chesscomfiles.com/uploads/v1/blog/291978.0ba48c8e.5000x5000o.b1dd3c4ba347.png', 'Największa platforma szachowa online', 'Board game', 4.9, '2007-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `games_library`
--

CREATE TABLE `games_library` (
  `id` int(11) UNSIGNED NOT NULL,
  `game_id` int(11) UNSIGNED NOT NULL,
  `owner_user_id` int(11) UNSIGNED NOT NULL,
  `library_game_added_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_rating` float DEFAULT NULL,
  `alias_name` varchar(60) DEFAULT NULL,
  `is_favourite` tinyint(1) NOT NULL DEFAULT 0,
  `last_play_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `media`
--

CREATE TABLE `media` (
  `id` int(11) UNSIGNED NOT NULL,
  `owner_user_id` int(11) UNSIGNED NOT NULL,
  `resource_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('Picture','Video','Music','E-book','Document','Other') NOT NULL,
  `description` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_modification_date` datetime NOT NULL DEFAULT current_timestamp(),
  `access_level` enum('private','public','friends') NOT NULL DEFAULT 'private'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE `messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_user_id` int(11) UNSIGNED NOT NULL,
  `reciver_user_id` int(11) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `read_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `newsletters_articles`
--

CREATE TABLE `newsletters_articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `type` enum('news','warning','offer','promotion') NOT NULL,
  `title` char(50) NOT NULL,
  `content` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `owner_user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_modification_date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_commentable` tinyint(1) NOT NULL DEFAULT 1,
  `access_level` enum('private','public','friends') NOT NULL DEFAULT 'public',
  `publication_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts_comments`
--

CREATE TABLE `posts_comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `comment_author_id` int(11) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_modification_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts_comments_likes`
--

CREATE TABLE `posts_comments_likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_comment_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reaction_data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts_likes`
--

CREATE TABLE `posts_likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `reaction_data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `resources`
--

CREATE TABLE `resources` (
  `id` int(11) UNSIGNED NOT NULL,
  `owner_user_id` int(11) UNSIGNED DEFAULT NULL,
  `path` text NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `description` text NOT NULL,
  `register_date` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login_date` datetime NOT NULL,
  `last_logout_date` datetime NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `current_theme` enum('Light','Dark') NOT NULL DEFAULT 'Light',
  `profile_visibility` enum('public','friends') NOT NULL DEFAULT 'public',
  `last_modification_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_user_id` (`sender_user_id`),
  ADD KEY `reciver_user_id` (`reciver_user_id`);

--
-- Indeksy dla tabeli `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `games_library`
--
ALTER TABLE `games_library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`),
  ADD KEY `owner_user_id` (`owner_user_id`);

--
-- Indeksy dla tabeli `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resource_id` (`resource_id`),
  ADD KEY `owner_user_id` (`owner_user_id`);

--
-- Indeksy dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_user_id` (`sender_user_id`),
  ADD KEY `reciver_user_id` (`reciver_user_id`);

--
-- Indeksy dla tabeli `newsletters_articles`
--
ALTER TABLE `newsletters_articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indeksy dla tabeli `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_user_id` (`owner_user_id`);

--
-- Indeksy dla tabeli `posts_comments`
--
ALTER TABLE `posts_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `comment_author_id` (`comment_author_id`);

--
-- Indeksy dla tabeli `posts_comments_likes`
--
ALTER TABLE `posts_comments_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_comment_id` (`post_comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `posts_likes`
--
ALTER TABLE `posts_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_user_id` (`owner_user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indeksy dla tabeli `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `games_library`
--
ALTER TABLE `games_library`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsletters_articles`
--
ALTER TABLE `newsletters_articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts_comments`
--
ALTER TABLE `posts_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts_comments_likes`
--
ALTER TABLE `posts_comments_likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts_likes`
--
ALTER TABLE `posts_likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `friend_requests_ibfk_1` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friend_requests_ibfk_2` FOREIGN KEY (`reciver_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `games_library`
--
ALTER TABLE `games_library`
  ADD CONSTRAINT `games_library_ibfk_1` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `games_library_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `media_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`reciver_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `newsletters_articles`
--
ALTER TABLE `newsletters_articles`
  ADD CONSTRAINT `newsletters_articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts_comments`
--
ALTER TABLE `posts_comments`
  ADD CONSTRAINT `posts_comments_ibfk_1` FOREIGN KEY (`comment_author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts_comments_likes`
--
ALTER TABLE `posts_comments_likes`
  ADD CONSTRAINT `posts_comments_likes_ibfk_1` FOREIGN KEY (`post_comment_id`) REFERENCES `posts_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_comments_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts_likes`
--
ALTER TABLE `posts_likes`
  ADD CONSTRAINT `posts_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

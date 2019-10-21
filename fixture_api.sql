-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 21, 2019 at 09:01 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fixture_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

DROP TABLE IF EXISTS `matches`;
CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `team_1` int(11) NOT NULL,
  `team_2` int(11) NOT NULL,
  `dttm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `winner` int(11) NOT NULL,
  `score_team_1` int(11) NOT NULL,
  `score_team_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `team_1`, `team_2`, `dttm`, `winner`, `score_team_1`, `score_team_2`) VALUES
(1, 1, 2, '2019-10-21 18:59:07', 1, 0, 0),
(2, 1, 3, '2019-10-21 18:59:07', 3, 0, 0),
(3, 1, 3, '2019-10-21 18:59:07', 1, 10, 20),
(8, 5, 6, '2019-10-21 19:24:42', 5, 10, 20),
(15, 5, 6, '2019-10-21 19:27:28', 6, 100, 200),
(72, 5, 6, '2019-10-21 20:57:41', 6, 100, 200),
(73, 5, 6, '2019-10-21 20:58:17', 6, 100, 200),
(74, 5, 6, '2019-10-21 20:58:26', 6, 100, 200),
(75, 5, 6, '2019-10-21 20:58:51', 6, 100, 200),
(76, 5, 6, '2019-10-21 20:59:16', 6, 100, 200),
(80, 5, 6, '2019-10-21 21:00:21', -99, 100, 200),
(81, 5, 6, '2019-10-21 21:00:39', -99, 100, 200),
(82, 5, 6, '2019-10-21 21:00:46', 5, 100, 200);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `player_1` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `player_2` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `points` int(11) NOT NULL,
  `rounds_played` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tournament` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `player_1`, `player_2`, `points`, `rounds_played`, `last_update`, `tournament`) VALUES
(1, 'Equipo prueba desde MAMP', 'jugador uno', 'jugador dos', 0, 0, '2019-10-21 18:58:54', 1),
(2, 'Equipo 2 prueba', 'jugador uno v2', 'jugador dos v2', 0, 0, '2019-10-21 18:58:54', 1),
(3, 'Nuevo equipo desde postman', 'jugador uno postman', 'jugador dos postman', 0, 0, '2019-10-21 18:58:54', 1),
(4, 'Nuevo equipo desde postman 2', 'jugador uno postman', 'jugador dos postman', 0, 0, '2019-10-21 19:09:48', 1),
(5, 'Equipo uno', 'jugador uno postman 2', 'jugador dos postman 2', 8, 10, '2019-10-21 21:00:46', 3),
(6, 'Equipo dos', 'jugador uno postman', 'jugador dos postman', 20, 10, '2019-10-21 21:00:46', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

DROP TABLE IF EXISTS `tournaments`;
CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `points_per_win` int(11) NOT NULL,
  `points_per_draw` int(11) NOT NULL,
  `points_per_lose` int(11) NOT NULL,
  `max_teams` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`id`, `name`, `points_per_win`, `points_per_draw`, `points_per_lose`, `max_teams`, `start_date`, `active`) VALUES
(1, 'Torneo desde MAMP', 3, 5, 7, 1, '2019-10-21 19:41:53', 1),
(3, 'Torneo desde Postman', 3, 1, 0, 1, '2019-10-21 20:59:13', 1),
(4, 'Torneo desde Postman', 3, 1, 0, 1000, '2019-10-21 20:57:19', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

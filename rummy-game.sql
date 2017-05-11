-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 03, 2017 at 02:22 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rummy-game`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `players` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `name`, `players`) VALUES
(1, 'AOFhearts,2OFhearts,3OFhearts,4OFhearts,5OFhearts,6OFhearts,7OFhearts,8OFhearts,9OFhearts,10OFhearts,JOFhearts,QOFhearts,KOFhearts,AOFdiams,2OFdiams,3OFdiams,4OFdiams,5OFdiams,6OFdiams,7OFdiams,8OFdiams,9OFdiams,10OFdiams,JOFdiams,QOFdiams,KOFdiams,AOFspades,2OFspades,3OFspades,4OFspades,5OFspades,6OFspades,7OFspades,8OFspades,9OFspades,10OFspades,JOFspades,QOFspades,KOFspades,AOFclubs,2OFclubs,3OFclubs,4OFclubs,5OFclubs,6OFclubs,7OFclubs,8OFclubs,9OFclubs,10OFclubs,JOFclubs,QOFclubs,KOFclubs,Joker,Joker,AOFhearts,2OFhearts,3OFhearts,4OFhearts,5OFhearts,6OFhearts,7OFhearts,8OFhearts,9OFhearts,10OFhearts,JOFhearts,QOFhearts,KOFhearts,AOFdiams,2OFdiams,3OFdiams,4OFdiams,5OFdiams,6OFdiams,7OFdiams,8OFdiams,9OFdiams,10OFdiams,JOFdiams,QOFdiams,KOFdiams,AOFspades,2OFspades,3OFspades,4OFspades,5OFspades,6OFspades,7OFspades,8OFspades,9OFspades,10OFspades,JOFspades,QOFspades,KOFspades,AOFclubs,2OFclubs,3OFclubs,4OFclubs,5OFclubs,6OFclubs,7OFclubs,8OFclubs,9OFclubs,10OFclubs,JOFclubs,QOFclubs,KOFclubs,Joker,Joker', 2),
(2, 'AOFhearts,2OFhearts,3OFhearts,4OFhearts,5OFhearts,6OFhearts,7OFhearts,8OFhearts,9OFhearts,10OFhearts,JOFhearts,QOFhearts,KOFhearts,AOFdiams,2OFdiams,3OFdiams,4OFdiams,5OFdiams,6OFdiams,7OFdiams,8OFdiams,9OFdiams,10OFdiams,JOFdiams,QOFdiams,KOFdiams,AOFspades,2OFspades,3OFspades,4OFspades,5OFspades,6OFspades,7OFspades,8OFspades,9OFspades,10OFspades,JOFspades,QOFspades,KOFspades,AOFclubs,2OFclubs,3OFclubs,4OFclubs,5OFclubs,6OFclubs,7OFclubs,8OFclubs,9OFclubs,10OFclubs,JOFclubs,QOFclubs,KOFclubs,Joker,Joker,AOFhearts,2OFhearts,3OFhearts,4OFhearts,5OFhearts,6OFhearts,7OFhearts,8OFhearts,9OFhearts,10OFhearts,JOFhearts,QOFhearts,KOFhearts,AOFdiams,2OFdiams,3OFdiams,4OFdiams,5OFdiams,6OFdiams,7OFdiams,8OFdiams,9OFdiams,10OFdiams,JOFdiams,QOFdiams,KOFdiams,AOFspades,2OFspades,3OFspades,4OFspades,5OFspades,6OFspades,7OFspades,8OFspades,9OFspades,10OFspades,JOFspades,QOFspades,KOFspades,AOFclubs,2OFclubs,3OFclubs,4OFclubs,5OFclubs,6OFclubs,7OFclubs,8OFclubs,9OFclubs,10OFclubs,JOFclubs,QOFclubs,KOFclubs,Joker,Joker', 6),
(3, 'AOFhearts,2OFhearts,3OFhearts,4OFhearts,5OFhearts,6OFhearts,7OFhearts,8OFhearts,9OFhearts,10OFhearts,JOFhearts,QOFhearts,KOFhearts,AOFdiams,2OFdiams,3OFdiams,4OFdiams,5OFdiams,6OFdiams,7OFdiams,8OFdiams,9OFdiams,10OFdiams,JOFdiams,QOFdiams,KOFdiams,AOFspades,2OFspades,3OFspades,4OFspades,5OFspades,6OFspades,7OFspades,8OFspades,9OFspades,10OFspades,JOFspades,QOFspades,KOFspades,AOFclubs,2OFclubs,3OFclubs,4OFclubs,5OFclubs,6OFclubs,7OFclubs,8OFclubs,9OFclubs,10OFclubs,JOFclubs,QOFclubs,KOFclubs', 0);

-- --------------------------------------------------------

--
-- Table structure for table `game_running`
--

CREATE TABLE `game_running` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `joker_selected` text NOT NULL,
  `round` int(11) NOT NULL,
  `show_button` int(11) NOT NULL,
  `toss_winner` int(11) NOT NULL,
  `deck` text NOT NULL,
  `throw_card` text NOT NULL,
  `current_player` int(255) NOT NULL,
  `melded_count` int(11) NOT NULL,
  `player_won` int(11) NOT NULL,
  `wrong_melders` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `game_running`
--

INSERT INTO `game_running` (`id`, `game_id`, `joker_selected`, `round`, `show_button`, `toss_winner`, `deck`, `throw_card`, `current_player`, `melded_count`, `player_won`, `wrong_melders`) VALUES
(60, 2, 'QOFdiams', 5, 0, 2, '', 'AOFdiams', 2, 3, 2, ''),
(83, 1, 'QOFhearts', 5, 0, 1, '', 'KOFdiams', 1, 2, 1, ''),
(85, 3, 'QOFhearts', 7, 0, 1, '', 'KOFclubs', 1, 2, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `players` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `game_id`, `players`) VALUES
(1226, 2, '1,2,3'),
(1250, 1, '1,2'),
(1252, 3, '1,2');

-- --------------------------------------------------------

--
-- Table structure for table `player_gamedata`
--

CREATE TABLE `player_gamedata` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `cards_in_hand` text NOT NULL,
  `group_1` text NOT NULL,
  `group_2` text NOT NULL,
  `group_3` text NOT NULL,
  `group_4` text NOT NULL,
  `group_5` text NOT NULL,
  `group_6` text NOT NULL,
  `melded_group_1` text NOT NULL,
  `melded_group_2` text NOT NULL,
  `melded_group_3` text NOT NULL,
  `melded_group_4` text NOT NULL,
  `discarded` text NOT NULL,
  `points` int(11) NOT NULL,
  `total_points` int(11) NOT NULL,
  `status` text NOT NULL,
  `match_status` text NOT NULL,
  `melded` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `player_gamedata`
--

INSERT INTO `player_gamedata` (`id`, `user_id`, `game_id`, `cards_in_hand`, `group_1`, `group_2`, `group_3`, `group_4`, `group_5`, `group_6`, `melded_group_1`, `melded_group_2`, `melded_group_3`, `melded_group_4`, `discarded`, `points`, `total_points`, `status`, `match_status`, `melded`) VALUES
(167, 2, 2, 'KOFclubs,10OFspades,KOFhearts,7OFspades,6OFhearts,9OFclubs,7OFhearts,QOFhearts,4OFhearts,QOFspades,3OFspades,7OFhearts,5OFspades', '', '', '', '', '', '', '10OFspades,7OFspades,QOFspades', 'KOFclubs,5OFspades,3OFspades', '7OFhearts,AOFdiams,4OFhearts', 'KOFhearts,6OFhearts,QOFhearts,7OFhearts', '', 0, 0, '', '', 'Y'),
(168, 3, 2, '8OFdiams,10OFclubs,3OFclubs,QOFhearts,10OFclubs,QOFdiams,6OFspades,8OFhearts,AOFspades,9OFdiams,3OFclubs,KOFhearts,7OFclubs', '', '', '', '', '', '', 'AOFspades,9OFdiams,3OFclubs', 'QOFhearts,10OFclubs,QOFdiams,6OFspades', '7OFclubs,KOFhearts,3OFclubs', '10OFclubs,8OFhearts,8OFdiams', '', 37, 117, 'over', 'out', 'Y'),
(169, 1, 2, 'KOFdiams,4OFclubs,KOFspades,2OFdiams,10OFdiams,KOFclubs,JOFhearts,4OFhearts,7OFdiams,QOFclubs,KOFspades,6OFdiams,QOFclubs', '', '', '', '', '', '', 'KOFdiams,4OFclubs,KOFspades,2OFdiams,10OFdiams,KOFclubs,JOFhearts,4OFhearts,7OFdiams,QOFclubs,KOFspades,6OFdiams,QOFclubs', '', '', '', '', 80, 160, 'over', 'out', 'Y'),
(212, 2, 1, 'KOFclubs,6OFdiams,4OFspades,9OFhearts,KOFclubs,6OFclubs,10OFspades,AOFdiams,8OFdiams,AOFclubs,7OFclubs,4OFhearts,Joker', '', '', '', '', '', '', 'KOFclubs,6OFdiams,4OFspades,9OFhearts,KOFclubs,6OFclubs,10OFspades,AOFdiams,8OFdiams,AOFclubs,7OFclubs,4OFhearts,Joker', '', '', '', '', 80, 160, 'over', 'out', 'Y'),
(213, 1, 1, '7OFspades,4OFclubs,9OFspades,QOFspades,7OFspades,AOFspades,2OFspades,5OFdiams,5OFhearts,9OFdiams,7OFdiams,2OFdiams,JOFclubs', '', '', '', '', '', '', '7OFspades,9OFspades,QOFspades,7OFspades', '4OFclubs,JOFclubs,AOFspades', '5OFdiams,9OFdiams,7OFdiams', '5OFhearts,2OFdiams,KOFdiams', '', 0, 80, '', '', 'Y'),
(216, 2, 3, 'QOFdiams,AOFdiams,AOFclubs,2OFhearts,7OFclubs,9OFhearts,10OFspades,2OFdiams,5OFclubs,3OFspades,10OFclubs,10OFdiams,8OFdiams', '', '', '', '', '', '', 'QOFdiams,AOFdiams,AOFclubs,2OFhearts,7OFclubs,9OFhearts,10OFspades,2OFdiams,5OFclubs,3OFspades,10OFclubs,10OFdiams,8OFdiams', '', '', '', '', 80, 277, 'over', 'out', 'Y'),
(217, 1, 3, 'AOFhearts,3OFclubs,AOFdiams,9OFclubs,8OFhearts,8OFdiams,6OFclubs,KOFdiams,9OFspades,5OFhearts,4OFspades,2OFdiams,10OFhearts', '', '', '', '', '', '', 'AOFhearts,8OFhearts,5OFhearts,10OFhearts', 'AOFdiams,8OFdiams,KOFdiams', '9OFspades,4OFspades,3OFclubs', '9OFclubs,6OFclubs,KOFclubs', '', 0, 80, '', '', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` enum('Y','N') NOT NULL,
  `status` varchar(255) NOT NULL,
  `gametype` varchar(255) NOT NULL,
  `players` int(11) NOT NULL,
  `bet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `created`, `status`, `gametype`, `players`, `bet`) VALUES
(1, '101 Rummy 2 players', 'N', 'open', '101', 2, 500),
(2, '101 Rummy 6 players', 'N', 'open', '101', 6, 1000),
(3, '201 Rummy 2 players', 'N', 'open', '201', 2, 500),
(4, '201 Rummy 6 players', 'N', 'open', '201', 6, 500);

-- --------------------------------------------------------

--
-- Table structure for table `shuffled_card`
--

CREATE TABLE `shuffled_card` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `shuffled_cards` text NOT NULL,
  `toss_shuffled_cards` text NOT NULL,
  `shuffled` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shuffled_card`
--

INSERT INTO `shuffled_card` (`id`, `game_id`, `shuffled_cards`, `toss_shuffled_cards`, `shuffled`) VALUES
(34, 1, '2OFclubs,KOFspades,KOFhearts,8OFdiams,9OFhearts,8OFhearts,AOFdiams,10OFclubs,2OFspades,3OFclubs,KOFspades,10OFhearts,JOFdiams,4OFclubs,10OFspades,JOFclubs,5OFdiams,QOFdiams,9OFspades,4OFdiams,QOFclubs,10OFdiams,AOFclubs,Joker,8OFspades,KOFhearts,10OFhearts,3OFspades,7OFdiams,9OFclubs,4OFhearts,JOFdiams,10OFclubs,3OFdiams,6OFhearts,6OFhearts,Joker,6OFspades,3OFspades,3OFhearts,6OFspades,5OFspades,5OFspades,9OFdiams,8OFspades,QOFspades,8OFclubs,5OFhearts,2OFhearts,JOFspades,QOFhearts,4OFdiams,KOFdiams,3OFhearts,QOFdiams,8OFhearts,4OFspades,2OFclubs,8OFclubs,2OFdiams,2OFhearts,5OFclubs,9OFclubs,10OFdiams,QOFclubs,6OFclubs,JOFspades,AOFhearts,3OFdiams,Joker,3OFclubs,JOFhearts,5OFclubs,QOFhearts,JOFhearts,6OFdiams,7OFhearts,AOFspades,7OFclubs,AOFhearts', '10OFspades,10OFdiams,3OFdiams,9OFdiams,10OFclubs,9OFspades,QOFspades,KOFspades,10OFhearts,8OFhearts,JOFspades,5OFdiams,AOFclubs,JOFdiams,5OFclubs,7OFspades,QOFclubs,4OFspades,6OFdiams,2OFclubs,KOFdiams,2OFdiams,7OFclubs,6OFhearts,9OFclubs,QOFdiams,5OFhearts,AOFhearts,6OFspades,8OFclubs,3OFhearts,2OFhearts,KOFclubs,9OFhearts,2OFspades,JOFhearts,QOFhearts,7OFhearts,7OFdiams,4OFclubs,8OFspades,6OFclubs,KOFhearts,5OFspades,4OFhearts,8OFdiams,AOFdiams,3OFclubs,3OFspades,4OFdiams,AOFspades,JOFclubs', 1),
(35, 2, '7OFspades,2OFclubs,3OFhearts,10OFdiams,8OFhearts,AOFclubs,JOFspades,3OFhearts,JOFclubs,8OFspades,2OFclubs,JOFspades,Joker,AOFdiams,Joker,4OFdiams,6OFdiams,AOFhearts,9OFdiams,5OFclubs,2OFdiams,4OFspades,9OFhearts,5OFdiams,6OFclubs,4OFclubs,6OFspades,2OFhearts,KOFdiams,5OFspades,10OFhearts,QOFspades,AOFhearts,4OFdiams,2OFhearts,5OFhearts,10OFhearts,Joker,4OFspades,5OFhearts,3OFdiams,7OFdiams,2OFspades,6OFhearts,JOFclubs,9OFspades,6OFclubs,3OFspades,7OFclubs,2OFspades,QOFdiams,3OFdiams,8OFdiams,8OFclubs,Joker,9OFhearts,JOFdiams,JOFhearts,8OFclubs,10OFspades,9OFspades,AOFclubs,AOFspades,5OFdiams,5OFclubs,8OFspades,JOFdiams', 'AOFclubs,6OFspades,5OFspades,JOFspades,10OFspades,KOFspades,6OFclubs,7OFhearts,KOFclubs,5OFdiams,8OFhearts,8OFclubs,4OFdiams,AOFspades,7OFdiams,3OFhearts,2OFhearts,5OFhearts,10OFdiams,8OFdiams,2OFdiams,9OFspades,2OFclubs,6OFdiams,KOFdiams,QOFhearts,10OFhearts,3OFspades,4OFspades,AOFdiams,9OFclubs,QOFclubs,6OFhearts,QOFspades,9OFdiams,7OFclubs,JOFdiams,9OFhearts,4OFhearts,AOFhearts,KOFhearts,5OFclubs,4OFclubs,3OFclubs,10OFclubs,7OFspades,QOFdiams,3OFdiams,2OFspades,8OFspades,JOFhearts,JOFclubs', 1),
(36, 3, '6OFspades,10OFdiams,6OFspades,4OFclubs,JOFdiams,KOFspades,8OFspades,Joker,7OFdiams,10OFspades,5OFhearts,AOFspades,9OFhearts,Joker,2OFclubs,8OFclubs,JOFspades,8OFhearts,4OFdiams,JOFhearts,KOFdiams,5OFdiams,10OFhearts,3OFdiams,4OFclubs,7OFhearts,9OFspades,9OFclubs,KOFspades,3OFhearts,8OFclubs,QOFspades,JOFdiams,7OFspades,Joker,JOFclubs,6OFdiams,7OFclubs,3OFdiams,2OFspades,KOFclubs,6OFdiams,QOFclubs,4OFdiams,JOFclubs,8OFspades,3OFclubs,7OFdiams,4OFspades,KOFhearts,3OFhearts,6OFclubs,7OFspades,6OFhearts,3OFspades,AOFspades,2OFhearts,5OFspades,4OFhearts,4OFhearts,Joker,KOFhearts,9OFdiams,JOFhearts,2OFspades,10OFclubs,AOFclubs,QOFhearts,5OFdiams,AOFhearts,JOFspades,5OFspades,6OFhearts,2OFclubs,QOFhearts,5OFclubs,QOFspades,9OFdiams,QOFdiams,QOFclubs', '7OFspades,5OFclubs,9OFclubs,6OFhearts,3OFspades,5OFdiams,2OFspades,4OFhearts,10OFdiams,KOFclubs,8OFclubs,10OFclubs,2OFclubs,10OFspades,7OFclubs,JOFspades,KOFhearts,2OFhearts,QOFspades,4OFspades,AOFhearts,JOFhearts,9OFdiams,9OFspades,7OFdiams,6OFclubs,KOFspades,3OFdiams,8OFspades,3OFclubs,JOFdiams,8OFdiams,QOFhearts,4OFdiams,6OFspades,AOFspades,AOFdiams,KOFdiams,JOFclubs,6OFdiams,5OFspades,9OFhearts,10OFhearts,7OFhearts,AOFclubs,3OFhearts,4OFclubs,2OFdiams,5OFhearts,QOFclubs,QOFdiams,8OFhearts', 1);

-- --------------------------------------------------------

--
-- Table structure for table `toss`
--

CREATE TABLE `toss` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `toss`
--

INSERT INTO `toss` (`id`, `game_id`, `player_id`) VALUES
(77, 1, 1),
(78, 2, 3),
(79, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `name`, `password`) VALUES
(1, 'sagnik@gmail.com', 'nickborti', 'Sagnik', '1234'),
(2, 'player1@gmail.com', 'player1', 'Player1', '1234'),
(3, 'player2@gmail.com', '', 'Player2', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `user_connection`
--

CREATE TABLE `user_connection` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `connection_id` text NOT NULL,
  `game_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_connection`
--

INSERT INTO `user_connection` (`id`, `user_id`, `connection_id`, `game_id`) VALUES
(181, 1, 'fo9dezns0pb9', 2),
(182, 2, 'dmruprjmsj1yvi', 2),
(183, 3, 'otuedo1330aki119atxb', 2),
(231, 1, '5j6w3pwfqi79zfr', 1),
(232, 2, '3xaejztbac680k9', 1),
(235, 1, '8pa9sgok0d2kpgb9', 3),
(236, 2, 'eiu4zjdob3ayvi', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_running`
--
ALTER TABLE `game_running`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `player_gamedata`
--
ALTER TABLE `player_gamedata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shuffled_card`
--
ALTER TABLE `shuffled_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `toss`
--
ALTER TABLE `toss`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_connection`
--
ALTER TABLE `user_connection`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `game_running`
--
ALTER TABLE `game_running`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1253;
--
-- AUTO_INCREMENT for table `player_gamedata`
--
ALTER TABLE `player_gamedata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;
--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `shuffled_card`
--
ALTER TABLE `shuffled_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `toss`
--
ALTER TABLE `toss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_connection`
--
ALTER TABLE `user_connection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

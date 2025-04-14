-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2025 at 02:02 PM
-- Server version: 10.11.11-MariaDB-0ubuntu0.24.04.2
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `HelveticBasket`
--

-- --------------------------------------------------------

--
-- Table structure for table `Club`
--

CREATE TABLE `Club` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Club`
--

INSERT INTO `Club` (`id`, `name`, `logo`, `location`) VALUES
(1, 'Saint Jean Basket', 'stjean.png', 'Salle des Asters - Servette'),
(2, 'Bernex Basket', 'bernex.png', 'Salle omnisports de Vailly - Bernex');

-- --------------------------------------------------------

--
-- Table structure for table `Matches`
--

CREATE TABLE `Matches` (
  `id` int(11) NOT NULL,
  `dateTime` datetime NOT NULL,
  `homeScore` int(11) DEFAULT NULL,
  `visitorScore` int(11) DEFAULT NULL,
  `idHomeTeam` int(11) DEFAULT NULL,
  `idVisitorTeam` int(11) DEFAULT NULL,
  `isFinished` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Matches`
--

INSERT INTO `Matches` (`id`, `dateTime`, `homeScore`, `visitorScore`, `idHomeTeam`, `idVisitorTeam`, `isFinished`) VALUES
(1, '2024-11-11 20:30:00', 63, 74, 1, 2, 1),
(2, '2025-01-15 20:30:00', 63, 58, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Player`
--

CREATE TABLE `Player` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `idTeam` int(11) DEFAULT NULL,
  `averagePoints` decimal(5,2) DEFAULT NULL,
  `averageMinutes` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Player`
--

INSERT INTO `Player` (`id`, `name`, `idTeam`, `averagePoints`, `averageMinutes`) VALUES
(1, 'Timon Sohan Amey', 1, 0.00, 0.00),
(2, 'Luca Ilan Bottallo', 1, 0.00, 0.00),
(3, 'Carlos Benedict Hidalgo', 1, 0.00, 0.00),
(4, 'Diego Ahmed Hostettler', 1, 0.00, 0.00),
(5, 'Luca Lanfranchini', 1, 0.00, 0.00),
(6, 'Jhon Alexander Lopez Sanchez', 1, 0.00, 0.00),
(7, 'Malik Adam Mouaki', 1, 0.00, 0.00),
(8, 'Kaué Oliveira Santos ', 1, 0.00, 0.00),
(9, 'Emir Özdemir', 1, 0.00, 0.00),
(10, 'Ilyes Reece', 1, 0.00, 0.00),
(11, 'Rayane Hichem Ben Farhat', 2, 0.00, 0.00),
(12, 'Sevan Caliboso', 2, 0.00, 0.00),
(13, 'Olivier Charbonnier', 2, 0.00, 0.00),
(14, 'Enis Dokaj', 2, 0.00, 0.00),
(15, 'Gabriel Grond', 2, 0.00, 0.00),
(16, 'Gonzalez Sylvio Higuera', 2, 0.00, 0.00),
(17, 'Ferdinand Mattias Pittet', 2, 0.00, 0.00),
(18, 'D\'Istria Sandro Re Colonna', 2, 0.00, 0.00),
(19, 'Gaëtan Rodriguez', 2, 0.00, 0.00),
(20, 'Ethan Sorg', 2, 0.00, 0.00),
(21, 'Akira Thévoz', 2, 0.00, 0.00),
(22, 'Soufiane Baina', 1, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `PlayerStats`
--

CREATE TABLE `PlayerStats` (
  `id` int(11) NOT NULL,
  `idPlayer` int(11) DEFAULT NULL,
  `idMatch` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `minutes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PlayerStats`
--

INSERT INTO `PlayerStats` (`id`, `idPlayer`, `idMatch`, `points`, `minutes`) VALUES
(1, 1, 1, 3, 16),
(2, 22, 1, 7, 24),
(3, 2, 1, 2, 22),
(4, 3, 1, 3, 15),
(5, 7, 1, 1, 11),
(6, 8, 1, 17, 34),
(7, 9, 1, 13, 33),
(8, 10, 1, 17, 37),
(9, 11, 1, 1, 21),
(10, 12, 1, 7, 22),
(11, 13, 1, 12, 29),
(12, 14, 1, 3, 14),
(13, 15, 1, 0, 11),
(14, 16, 1, 2, 12),
(15, 17, 1, 0, 7),
(16, 18, 1, 0, 9),
(17, 19, 1, 26, 33),
(18, 20, 1, 15, 34);

-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE `Team` (
  `id` int(11) NOT NULL,
  `teamName` varchar(50) NOT NULL,
  `level` enum('U16','U18','U20') DEFAULT NULL,
  `idClub` int(11) DEFAULT NULL,
  `region` enum('Cantonal','Regional','National') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Team`
--

INSERT INTO `Team` (`id`, `teamName`, `level`, `idClub`, `region`) VALUES
(1, 'Saint Jean U20', 'U20', 1, 'Regional'),
(2, 'Bernex Basket U20M', 'U20', 2, 'Regional');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `name`, `email`, `passwordHash`) VALUES
(1, 'Carlos', 'admin@eduge.ch', '$2y$10$dUmSMwC4trhQc0pE1.miTewNBk/.L43sdJzRoGHysmViE2JCN4OPG');

-- --------------------------------------------------------

--
-- Table structure for table `UserFavoritePlayers`
--

CREATE TABLE `UserFavoritePlayers` (
  `idUser` int(11) NOT NULL,
  `idPlayer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserFavoriteTeams`
--

CREATE TABLE `UserFavoriteTeams` (
  `idUser` int(11) NOT NULL,
  `idTeam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Club`
--
ALTER TABLE `Club`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Matches`
--
ALTER TABLE `Matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_home_team` (`idHomeTeam`),
  ADD KEY `id_visitor_team` (`idVisitorTeam`);

--
-- Indexes for table `Player`
--
ALTER TABLE `Player`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTeam` (`idTeam`);

--
-- Indexes for table `PlayerStats`
--
ALTER TABLE `PlayerStats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPlayer` (`idPlayer`),
  ADD KEY `idMatch` (`idMatch`);

--
-- Indexes for table `Team`
--
ALTER TABLE `Team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idClub` (`idClub`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `UserFavoritePlayers`
--
ALTER TABLE `UserFavoritePlayers`
  ADD PRIMARY KEY (`idUser`,`idPlayer`),
  ADD KEY `idPlayer` (`idPlayer`);

--
-- Indexes for table `UserFavoriteTeams`
--
ALTER TABLE `UserFavoriteTeams`
  ADD PRIMARY KEY (`idUser`,`idTeam`),
  ADD KEY `idTeam` (`idTeam`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Club`
--
ALTER TABLE `Club`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Matches`
--
ALTER TABLE `Matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Player`
--
ALTER TABLE `Player`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `PlayerStats`
--
ALTER TABLE `PlayerStats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Team`
--
ALTER TABLE `Team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Matches`
--
ALTER TABLE `Matches`
  ADD CONSTRAINT `Matches_ibfk_1` FOREIGN KEY (`idHomeTeam`) REFERENCES `Team` (`id`),
  ADD CONSTRAINT `Matches_ibfk_2` FOREIGN KEY (`idVisitorTeam`) REFERENCES `Team` (`id`);

--
-- Constraints for table `Player`
--
ALTER TABLE `Player`
  ADD CONSTRAINT `Player_ibfk_1` FOREIGN KEY (`idTeam`) REFERENCES `Team` (`id`);

--
-- Constraints for table `PlayerStats`
--
ALTER TABLE `PlayerStats`
  ADD CONSTRAINT `PlayerStats_ibfk_1` FOREIGN KEY (`idPlayer`) REFERENCES `Player` (`id`),
  ADD CONSTRAINT `PlayerStats_ibfk_2` FOREIGN KEY (`idMatch`) REFERENCES `Matches` (`id`);

--
-- Constraints for table `Team`
--
ALTER TABLE `Team`
  ADD CONSTRAINT `Team_ibfk_1` FOREIGN KEY (`idClub`) REFERENCES `Club` (`id`);

--
-- Constraints for table `UserFavoritePlayers`
--
ALTER TABLE `UserFavoritePlayers`
  ADD CONSTRAINT `UserFavoritePlayers_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `UserFavoritePlayers_ibfk_2` FOREIGN KEY (`idPlayer`) REFERENCES `Player` (`id`);

--
-- Constraints for table `UserFavoriteTeams`
--
ALTER TABLE `UserFavoriteTeams`
  ADD CONSTRAINT `UserFavoriteTeams_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `UserFavoriteTeams_ibfk_2` FOREIGN KEY (`idTeam`) REFERENCES `Team` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

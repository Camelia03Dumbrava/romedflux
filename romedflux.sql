-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 05:32 PM
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
-- Database: `romedflux`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitate_stoc_bo1`
--

CREATE TABLE `activitate_stoc_bo1` (
  `id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `cantitate` int(11) NOT NULL,
  `actiune` varchar(255) NOT NULL,
  `realizat_de` int(11) NOT NULL,
  `solicitat_de` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `vazut` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activitate_stoc_bo1`
--

INSERT INTO `activitate_stoc_bo1` (`id`, `medicament_id`, `cantitate`, `actiune`, `realizat_de`, `solicitat_de`, `created_at`, `vazut`) VALUES
(1, 6, 5, 'Mutare din stoc general în BO1', 9, NULL, '2025-04-21 16:27:51', NULL),
(2, 7, 2, 'Mutare din stoc general în BO1', 2, NULL, '2025-04-21 16:31:40', NULL),
(3, 6, 1, 'Utilizare medicament în BO1', 2, 13, '2025-04-21 16:44:30', '2025-06-25 23:28:25'),
(4, 3, 1, 'Mutare din stoc general în BO1', 9, NULL, '2025-04-21 17:53:12', NULL),
(5, 4, 1, 'Mutare din stoc general în BO1', 13, NULL, '2025-04-21 17:53:47', NULL),
(6, 3, 1, 'Utilizare medicament în BO1', 13, 13, '2025-04-21 18:03:23', '2025-06-25 23:28:17'),
(7, 3, 1, 'Mutare din stoc general în BO1', 13, NULL, '2025-04-21 18:03:47', NULL),
(8, 1, 4, 'Mutare din stoc general în BO1', 9, NULL, '2025-05-15 15:14:44', NULL),
(9, 6, 1, 'Utilizare medicament în BO1', 2, 10, '2025-05-15 17:12:34', NULL),
(10, 3, 2, 'Mutare din stoc general în BO1', 2, NULL, '2025-05-15 17:13:00', NULL),
(11, 6, 1, 'Utilizare medicament în BO1', 9, 13, '2025-06-10 18:53:32', '2025-06-25 23:28:20'),
(12, 6, 4, 'Mutare din stoc general în BO1', 2, NULL, '2025-06-10 19:02:11', NULL),
(13, 2, 1, 'Mutare din stoc general în BO1', 9, NULL, '2025-06-25 20:13:13', NULL),
(14, 2, 1, 'Utilizare medicament în BO1', 9, 10, '2025-06-25 21:37:52', NULL),
(15, 2, 1, 'Mutare din stoc general în BO1', 9, NULL, '2025-06-25 21:38:20', NULL),
(16, 2, 1, 'Mutare din stoc general în BO1', 9, NULL, '2025-06-25 21:51:12', NULL),
(17, 1, 1, 'Utilizare medicament în BO1', 16, 18, '2025-06-25 23:45:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activitate_stoc_bo2`
--

CREATE TABLE `activitate_stoc_bo2` (
  `id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `cantitate` int(11) NOT NULL,
  `actiune` varchar(255) NOT NULL,
  `realizat_de` int(11) DEFAULT NULL,
  `solicitat_de` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `vazut` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activitate_stoc_bo2`
--

INSERT INTO `activitate_stoc_bo2` (`id`, `medicament_id`, `cantitate`, `actiune`, `realizat_de`, `solicitat_de`, `created_at`, `vazut`) VALUES
(5, 2, 3, 'Mutare din stoc general în BO2', 9, NULL, '2025-04-21 15:39:27', NULL),
(6, 2, 1, 'Utilizare medicament în BO2', 9, NULL, '2025-04-21 15:39:46', NULL),
(7, 2, 2, 'Utilizare medicament în BO2', 9, NULL, '2025-04-21 15:39:57', NULL),
(8, 2, 2, 'Mutare din stoc general în BO2', 9, NULL, '2025-04-21 15:40:38', NULL),
(9, 2, 3, 'Mutare din stoc general în BO2', 9, NULL, '2025-04-21 15:41:23', NULL),
(10, 2, 3, 'Utilizare medicament în BO2', 9, 13, '2025-04-21 15:41:30', '2025-06-25 23:28:24'),
(11, 2, 4, 'Mutare din stoc general în BO2', 9, NULL, '2025-04-21 15:42:14', NULL),
(12, 2, 2, 'Utilizare medicament în BO2', 9, 13, '2025-04-21 15:43:11', '2025-06-25 23:28:23'),
(13, 2, 2, 'Utilizare medicament în BO2', 9, 13, '2025-04-21 15:43:18', '2025-06-25 23:28:21'),
(14, 2, 3, 'Mutare din stoc general în BO2', 9, NULL, '2025-04-21 15:43:39', NULL),
(15, 2, 3, 'Utilizare medicament în BO2', 9, 18, '2025-06-25 18:41:55', NULL),
(16, 2, 5, 'Mutare din stoc general în BO2', 9, NULL, '2025-06-25 18:42:24', NULL),
(17, 2, 1, 'Mutare din stoc general în BO2', 9, NULL, '2025-06-25 18:51:21', NULL),
(18, 4, 1, 'Utilizare medicament în BO2', 9, 18, '2025-06-25 18:52:55', NULL),
(19, 4, 1, 'Utilizare medicament în BO2', 16, 19, '2025-06-25 18:53:31', NULL),
(20, 4, 1, 'Utilizare medicament în BO2', 16, 19, '2025-06-25 18:53:46', '2025-06-25 23:42:37'),
(21, 1, 13, 'Mutare din stoc general în BO2', 16, NULL, '2025-06-25 19:05:47', NULL),
(22, 3, 22, 'Mutare din stoc general în BO2', 16, NULL, '2025-06-25 19:05:53', NULL),
(23, 5, 7, 'Mutare din stoc general în BO2', 16, NULL, '2025-06-25 19:06:08', NULL),
(24, 7, 3, 'Mutare din stoc general în BO2', 16, NULL, '2025-06-25 19:06:13', NULL),
(25, 1, 3, 'Utilizare medicament în BO2', 16, 17, '2025-06-25 19:37:04', '2025-06-25 23:36:59'),
(26, 3, 3, 'Utilizare medicament în BO2', 16, 13, '2025-06-25 19:37:15', '2025-06-25 23:28:19'),
(27, 3, 2, 'Utilizare medicament în BO2', 16, 17, '2025-06-25 20:37:50', '2025-06-25 23:38:54'),
(28, 1, 1, 'Utilizare medicament în BO2', 16, 17, '2025-06-25 20:37:57', '2025-06-25 23:38:52');

-- --------------------------------------------------------

--
-- Table structure for table `medicamente`
--

CREATE TABLE `medicamente` (
  `id` int(11) NOT NULL,
  `denumire` varchar(100) NOT NULL,
  `cod` varchar(50) NOT NULL,
  `forma_farmaceutica` enum('flacon','fiolă','comprimat','altele') NOT NULL,
  `concentratie` varchar(50) DEFAULT NULL,
  `descriere` text DEFAULT NULL,
  `creat_la` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicamente`
--

INSERT INTO `medicamente` (`id`, `denumire`, `cod`, `forma_farmaceutica`, `concentratie`, `descriere`, `creat_la`) VALUES
(1, 'Midazolam', 'W70991001', 'fiolă', '5mg/ml', 'Sedativ benzodiazepin', '2025-04-06 13:37:01'),
(2, 'Propofol', 'W66963006', 'fiolă', '10mg/ml', 'Anestezic general', '2025-04-06 13:57:21'),
(3, 'Fentanil', 'W67084002', 'fiolă', '50ug/ml', 'Anestezic opioid', '2025-04-06 13:58:37'),
(4, 'Morfina', 'W59797001', 'fiolă', '20mg/ml', 'Analgezic opioid', '2025-04-06 13:59:54'),
(5, 'Calypsol (Ketamina)', 'W54826001', 'flacon', '50mg/ml', 'Analgezic sedativ, amnezic', '2025-04-06 14:02:51'),
(6, 'Lidocaina', 'W65510008', 'fiolă', '10mg/ml', 'Anestezic local', '2025-04-06 14:05:08'),
(7, 'Bupivacaina', 'W68634001', 'fiolă', '5mg/ml', 'Anestezic local', '2025-04-06 14:06:03');

-- --------------------------------------------------------

--
-- Table structure for table `programari_bo1`
--

CREATE TABLE `programari_bo1` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `procedura` text NOT NULL,
  `durata` varchar(20) DEFAULT NULL,
  `chirurgie` varchar(100) DEFAULT NULL,
  `anestezie` varchar(100) DEFAULT NULL,
  `tip` enum('programata','urgenta') DEFAULT 'programata',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programari_bo1`
--

INSERT INTO `programari_bo1` (`id`, `data`, `ora`, `procedura`, `durata`, `chirurgie`, `anestezie`, `tip`, `created_at`) VALUES
(1, '2025-04-21', '12:30:00', 'Excizie chist sebaceu', '2h', 'Dr. Marcu Aurel', 'Dr. Ana Blandiana', 'programata', '2025-04-21 13:55:14'),
(2, '2025-04-21', '10:30:00', 'Onicocriptoza', '1h', 'Dr. Alex Prog', 'Dr. Sam Sam', 'programata', '2025-04-21 14:09:12'),
(3, '2025-04-21', '14:30:00', 'Excizie chist sebaceu', '30min', 'Dr. Medic Alex', 'Dr. Medic Alex', 'programata', '2025-04-21 14:34:09'),
(4, '2025-04-21', '09:00:00', 'Excizie chist sebaceu', '1h', 'Dr. Medic Alex', 'Dr. Medic Alex', 'programata', '2025-04-21 14:34:38'),
(5, '2025-04-22', '09:00:00', 'Excizie chist sebaceu', '1h', 'Dr. Medic Alex', 'Dr. Medic Alex', 'programata', '2025-04-21 14:45:15'),
(6, '2025-04-22', '16:00:00', 'Excizie chist sebaceu', '30min', 'Dr. Medic Alex', 'Dr. Medic Alex', 'urgenta', '2025-04-21 14:51:54'),
(7, '2025-04-21', '11:00:00', 'Onicocriptoza', '1h', 'Dr. Medic Alex', 'Dr. Medic Alex', 'programata', '2025-04-21 15:15:39'),
(8, '2025-04-21', '21:28:00', 'Excizie chist sebaceu', '1h', 'Dr. Medic Alex', 'Dr. Medic Alex', 'urgenta', '2025-04-29 16:26:17'),
(9, '2025-06-23', '13:30:00', 'Onicocriptoza', '1h', 'Dr. Ion Savu', 'Dr. Alexandru Coman', 'programata', '2025-06-25 16:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `programari_bo2`
--

CREATE TABLE `programari_bo2` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `durata` varchar(20) NOT NULL,
  `procedura` varchar(255) NOT NULL,
  `chirurgie` varchar(255) DEFAULT NULL,
  `anestezie` varchar(255) DEFAULT NULL,
  `tip` enum('programata','urgenta') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programari_bo2`
--

INSERT INTO `programari_bo2` (`id`, `data`, `ora`, `durata`, `procedura`, `chirurgie`, `anestezie`, `tip`, `created_at`) VALUES
(1, '2025-04-21', '09:00:00', '1h', 'Onicocriptoza', 'Dr. Medic Alex', 'Dr. Medic Alex', 'programata', '2025-04-21 15:22:30'),
(2, '2025-06-26', '10:00:00', '1h', 'excizie lipom ant', 'Dr. Ion Savu', 'Dr. Alexandru Savi', 'programata', '2025-06-26 07:26:46');

-- --------------------------------------------------------

--
-- Table structure for table `stoc_bo1`
--

CREATE TABLE `stoc_bo1` (
  `id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `cantitate` int(11) NOT NULL CHECK (`cantitate` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stoc_bo1`
--

INSERT INTO `stoc_bo1` (`id`, `medicament_id`, `cantitate`) VALUES
(7, 6, 2),
(8, 7, 2),
(10, 4, 1),
(12, 1, 3),
(13, 3, 2),
(17, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stoc_bo2`
--

CREATE TABLE `stoc_bo2` (
  `id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `cantitate` int(11) NOT NULL CHECK (`cantitate` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stoc_bo2`
--

INSERT INTO `stoc_bo2` (`id`, `medicament_id`, `cantitate`) VALUES
(1, 4, 0),
(7, 2, 6),
(10, 1, 9),
(11, 3, 17),
(12, 5, 7),
(13, 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `stoc_general`
--

CREATE TABLE `stoc_general` (
  `id` int(11) NOT NULL,
  `medicament_id` int(11) NOT NULL,
  `cantitate` int(11) NOT NULL CHECK (`cantitate` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stoc_general`
--

INSERT INTO `stoc_general` (`id`, `medicament_id`, `cantitate`) VALUES
(1, 1, 33),
(2, 2, 2),
(3, 3, 13),
(4, 4, 5),
(5, 5, 8),
(6, 6, 6),
(7, 7, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('asistent','medic','administrator') NOT NULL DEFAULT 'asistent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','active','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `role`, `created_at`, `status`) VALUES
(1, 'Alexia', 'Pop', 'testuser', '$2y$10$4x8nmSyI/x6aJUd3ebJ2QeUHH/ORd7tPod23K.Ao4E1EVjAdbrKW2', 'asistent', '2025-04-01 18:59:46', 'pending'),
(2, 'Camelia', 'Dumbrava', 'test', '$2y$10$Y./kBNppDRZVIhF6YEsMAeStFHXq8WZ5K9Qa3JzW6ixvgYrMFNQxi', 'asistent', '2025-04-06 06:56:25', 'active'),
(3, 'Test2', 'Test2', 'test2', '$2y$10$3jDXL540wCPjUs0p8ZJm1uL5.INEwhHBf52vrtizxxlwRk3PHm082', 'asistent', '2025-04-06 08:52:13', 'rejected'),
(9, 'Sonia', 'Pop', 'admin', '$2y$10$wKelLFpcRgj.nKSsdtydQ.APBVlWDCXgo/TlBsULEuzFJNKdiGzi6', 'administrator', '2025-04-06 11:48:00', 'active'),
(10, 'Marcel', 'Popescu', 'test3', '$2y$10$U1piCpL/nYhNNQ3/pLulw.7sMpMEHoQ/HPlTpSN7Ho6PdDanyu/q6', 'medic', '2025-04-06 13:22:31', 'pending'),
(13, 'Alexandru', 'Savi', 'medic', '$2y$10$lHiWPtBX7ee6FDlIn2tR7uELqIqnBExOcj4Jnu6aMN9mVSRTIZEfC', 'medic', '2025-04-21 10:17:02', 'active'),
(14, 'Ioana', 'Balas', 'ioanabalas', '$2y$10$qyahiu7vuhRLDEYChWCDG.r.J9BB9jfdbK5ffry4rcFq2c/gvaqCK', 'asistent', '2025-06-25 15:40:06', 'active'),
(15, 'Vlad', 'Manea', 'vladmanea', '$2y$10$CJtirJ4vBFMoi96i6eLs8eNDwiomjAiGRg82D3Ot4hDmejTgmdinC', 'asistent', '2025-06-25 15:40:27', 'active'),
(16, 'Camelia', 'Dumbrava', 'cameliadumbrava', '$2y$10$zQvclOoQ/sp3fywryUGo7.mFx2OT27w2eOy7t1.Y4nxIOVUQk8ska', 'asistent', '2025-06-25 15:40:47', 'active'),
(17, 'Ion', 'Savu', 'ionsavu', '$2y$10$0hE73FYe4Lf3R7nItzJ49eQ.H8e5lCAEPNEUXz13GblJIx7z1ixg.', 'medic', '2025-06-25 15:41:48', 'active'),
(18, 'Alexandru', 'Coman', 'alexandrucoman', '$2y$10$nJv5gtqhsP5GUXTabuYOweMfQcmtwNobWBnezuY/LuswkuzkMIjGO', 'medic', '2025-06-25 15:42:19', 'active'),
(19, 'Ana', 'Preda', 'anapreda', '$2y$10$LNM2FbTgw5/HDq3GktY36exWqoKsviFFqKUKWu3wJkKad3dr.mwb2', 'medic', '2025-06-25 15:42:40', 'active'),
(20, 'Dan', 'Pop', 'danpop', '$2y$10$1mr32p1elFnRCbBMFmMr7u7d4sGyP7UXw3ADumxR3r.lJxK5LHheS', 'administrator', '2025-06-25 19:21:16', 'active'),
(21, 'Radu', 'Mot', 'radumot', '$2y$10$IVJ6YfnfPNG26Hqf/8GWg.hZRKFuH0lqgYblL0DCc0jk.MGxAdqWm', 'administrator', '2025-06-25 19:26:40', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitate_stoc_bo1`
--
ALTER TABLE `activitate_stoc_bo1`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicament_id` (`medicament_id`),
  ADD KEY `realizat_de` (`realizat_de`),
  ADD KEY `solicitat_de` (`solicitat_de`);

--
-- Indexes for table `activitate_stoc_bo2`
--
ALTER TABLE `activitate_stoc_bo2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicament_id` (`medicament_id`),
  ADD KEY `realizat_de` (`realizat_de`),
  ADD KEY `solicitat_de` (`solicitat_de`);

--
-- Indexes for table `medicamente`
--
ALTER TABLE `medicamente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cod` (`cod`);

--
-- Indexes for table `programari_bo1`
--
ALTER TABLE `programari_bo1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programari_bo2`
--
ALTER TABLE `programari_bo2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stoc_bo1`
--
ALTER TABLE `stoc_bo1`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicament_id` (`medicament_id`);

--
-- Indexes for table `stoc_bo2`
--
ALTER TABLE `stoc_bo2`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicament_id` (`medicament_id`);

--
-- Indexes for table `stoc_general`
--
ALTER TABLE `stoc_general`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicament_id` (`medicament_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activitate_stoc_bo1`
--
ALTER TABLE `activitate_stoc_bo1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `activitate_stoc_bo2`
--
ALTER TABLE `activitate_stoc_bo2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `medicamente`
--
ALTER TABLE `medicamente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `programari_bo1`
--
ALTER TABLE `programari_bo1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `programari_bo2`
--
ALTER TABLE `programari_bo2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stoc_bo1`
--
ALTER TABLE `stoc_bo1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stoc_bo2`
--
ALTER TABLE `stoc_bo2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stoc_general`
--
ALTER TABLE `stoc_general`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activitate_stoc_bo1`
--
ALTER TABLE `activitate_stoc_bo1`
  ADD CONSTRAINT `activitate_stoc_bo1_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicamente` (`id`),
  ADD CONSTRAINT `activitate_stoc_bo1_ibfk_2` FOREIGN KEY (`realizat_de`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `activitate_stoc_bo1_ibfk_3` FOREIGN KEY (`solicitat_de`) REFERENCES `users` (`id`);

--
-- Constraints for table `activitate_stoc_bo2`
--
ALTER TABLE `activitate_stoc_bo2`
  ADD CONSTRAINT `activitate_stoc_bo2_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicamente` (`id`),
  ADD CONSTRAINT `activitate_stoc_bo2_ibfk_2` FOREIGN KEY (`realizat_de`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `activitate_stoc_bo2_ibfk_3` FOREIGN KEY (`solicitat_de`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stoc_bo1`
--
ALTER TABLE `stoc_bo1`
  ADD CONSTRAINT `stoc_bo1_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicamente` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stoc_bo2`
--
ALTER TABLE `stoc_bo2`
  ADD CONSTRAINT `stoc_bo2_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicamente` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stoc_general`
--
ALTER TABLE `stoc_general`
  ADD CONSTRAINT `stoc_general_ibfk_1` FOREIGN KEY (`medicament_id`) REFERENCES `medicamente` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

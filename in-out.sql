-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Jul 22, 2016 at 03:58 AM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `in-out`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`) VALUES
(1, 'Standard User', '{"admin":0,"moderator":1}'),
(2, 'Administrator', '{"admin":1,"moderator":1}');

-- --------------------------------------------------------

--
-- Table structure for table `meta_data`
--

CREATE TABLE `meta_data` (
  `id` int(9) NOT NULL,
  `id_user` int(9) NOT NULL,
  `id_data` int(9) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meta_data`
--

INSERT INTO `meta_data` (`id`, `id_user`, `id_data`) VALUES
(1, 1, 4),
(2, 1, 5),
(3, 2, 1),
(7, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`, `description`) VALUES
(1, 'Administrador', 'Administrador de Ventas'),
(2, 'Conserje', 'Empleado de Limpieza');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` date NOT NULL,
  `hourIn` time NOT NULL,
  `hourOut` time DEFAULT NULL,
  `id_timetable` int(9) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `id_user`, `date`, `hourIn`, `hourOut`, `id_timetable`) VALUES
(1, 1, '2016-06-03', '17:06:01', '17:40:16', 0),
(2, 1, '2016-06-03', '17:42:07', '17:44:41', 0),
(3, 1, '2016-06-03', '17:44:57', '17:44:58', 0),
(4, 1, '2016-06-03', '17:45:12', '17:45:13', 0),
(5, 1, '2016-06-03', '17:50:37', '17:58:45', 0),
(6, 1, '2016-06-03', '17:59:41', '17:59:45', 0),
(7, 1, '2016-06-03', '20:26:29', '21:50:57', 0),
(8, 1, '2016-06-03', '21:51:08', '21:53:43', 0),
(9, 1, '2016-06-03', '21:54:06', '21:54:48', 0),
(10, 1, '2016-06-03', '22:55:32', '22:31:33', 0),
(11, 1, '2016-06-04', '09:01:22', '09:03:15', 0),
(12, 1, '2016-06-04', '09:03:20', '09:03:40', 0),
(13, 1, '2016-06-04', '13:44:32', '13:48:41', 0),
(14, 1, '2016-06-04', '14:45:48', '20:41:21', 0),
(15, 1, '2016-06-04', '20:45:08', '20:57:18', 0),
(16, 1, '2016-06-04', '20:57:24', '21:38:28', 0),
(17, 2, '2016-06-04', '21:39:00', '22:31:13', 0),
(18, 2, '2016-06-04', '22:31:28', '22:34:12', 0),
(19, 2, '2016-06-04', '22:34:18', '22:34:22', 0),
(20, 2, '2016-06-04', '22:34:38', '22:39:31', 0),
(21, 2, '2016-06-04', '22:35:54', '22:41:17', 0),
(22, 2, '2016-06-04', '22:41:33', '22:41:34', 0),
(23, 2, '2016-06-04', '22:41:56', '22:43:17', 0),
(24, 2, '2016-06-04', '22:43:25', '22:43:36', 0),
(25, 1, '2016-06-04', '22:43:41', '22:43:44', 0),
(26, 1, '2016-06-22', '16:14:14', '17:31:42', 0),
(27, 1, '2016-06-22', '17:31:43', '17:31:50', 0),
(28, 1, '2016-07-21', '20:52:18', '21:17:57', 0),
(29, 1, '2016-07-21', '21:22:53', '21:23:16', 0),
(30, 1, '2016-07-21', '21:30:47', NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(9) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `hourIn` time NOT NULL,
  `hourOut` time NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `name`, `description`, `hourIn`, `hourOut`) VALUES
(1, 'Continuo Mañana', 'Primer Horario Continuo', '07:00:00', '15:00:00'),
(2, 'Continuo Tarde', 'Segundo Horario Continuo', '15:00:00', '22:00:00'),
(3, 'Continuo Noche', 'Tercer Horario Continuo', '22:00:00', '06:00:00'),
(4, 'Matutino', 'Primer Discontinuo', '08:00:00', '12:00:00'),
(5, 'Vespertino', 'Segundo Discontinuo', '14:00:00', '18:00:00'),
(6, 'Nocturno', 'Tercer Discontinuo', '18:00:00', '22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `id_num` int(9) NOT NULL,
  `id_type` char(1) DEFAULT 'V',
  `email` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(45) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `joined` datetime NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_position` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_num`, `id_type`, `email`, `password`, `salt`, `firstName`, `lastName`, `joined`, `id_group`, `id_position`) VALUES
(1, 21367773, 'V', 'aado29@gmail.com', '1c2e3150f68ae77b0c4789619d9460a56fd87f5a25e21bec5b193c454dd4885d', '¿Ú\\@u‡\Z;ÅŽ”MÍ¼ÆS(‹G*aM\Z+½9Å\0¡Û=', 'Alberto', 'Diaz', '2016-07-10 15:07:40', 2, 1),
(2, 12345678, 'V', 'johnsmith@correo.com', 'c3df29c60e5b7496f20243667b3512cea76316c9635bbdcb594604d0f538e9e4', '–À”/o[çdmtºz^d|J"  r´zÚÀ(¢ªÎ', 'John', 'Smith', '2016-06-04 21:35:21', 1, 1),
(3, 10000000, 'V', 'joserodriguez@gmail.com', 'd83d23857c2ed29631a63c18a5bf59755985a0733d002cdd145b26e21828935d', '–>±ø¸Ù‡ÃªK$ž¨"ôýÎC•(•¨·¼ÓÅR', 'Jose', 'Rodriguez', '2016-07-10 14:57:28', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE `users_session` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta_data`
--
ALTER TABLE `meta_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_position` (`id_position`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meta_data`
--
ALTER TABLE `meta_data`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`id_position`) REFERENCES `positions` (`id`);

--
-- Constraints for table `users_session`
--
ALTER TABLE `users_session`
  ADD CONSTRAINT `users_session_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

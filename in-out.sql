-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Aug 04, 2016 at 08:43 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `in-out`
--

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(9) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `hourIn` time NOT NULL,
  `hourOut` time NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`id`, `name`, `description`, `hourIn`, `hourOut`, `type`) VALUES
(1, 'Continuo MaÃ±ana', 'Primer Horario Continuo', '07:00:00', '15:00:00', 0),
(2, 'Continuo Tarde', 'Segundo Horario Continuo', '15:00:00', '22:00:00', 0),
(3, 'Continuo Noche', 'Tercer Horario Continuo', '22:00:00', '06:00:00', 0),
(4, 'Matutino', 'Primer Discontinuo', '08:00:00', '12:00:00', 1),
(5, 'Vespertino', 'Segundo Discontinuo', '12:00:00', '18:00:00', 1),
(6, 'Nocturno', 'Tercer Discontinuo', '18:00:00', '22:00:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
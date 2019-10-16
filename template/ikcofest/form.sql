-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2019 at 04:27 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ikcofest`
--

-- --------------------------------------------------------

--
-- Table structure for table `ik_form`
--

CREATE TABLE `ik_form` (
  `ID` bigint(48) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `family` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `province` int(10) NOT NULL,
  `city` int(10) NOT NULL,
  `address` text NOT NULL,
  `picture_place` text NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ik_form`
--
ALTER TABLE `ik_form`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ik_form`
--
ALTER TABLE `ik_form`
  MODIFY `ID` bigint(48) NOT NULL AUTO_INCREMENT;

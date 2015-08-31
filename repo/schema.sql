-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 01, 2015 at 02:52 AM
-- Server version: 5.6.25-0ubuntu0.15.04.1
-- PHP Version: 5.6.4-4ubuntu6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ejr`
--

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
`id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `mime` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'permission name',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'last updated date',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'deleted date'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'user_view', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 'user_create', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 'user_update', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 'user_remove', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'name of the role',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'last updated date',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'deleted date'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Administrator', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 'Client', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE IF NOT EXISTS `role_permission` (
`id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT 'foreign key of role table',
  `permission_id` int(11) NOT NULL COMMENT 'foreign key of permission table',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'last updated date',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'deleted date'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(2, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(3, 1, 3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
`id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `title`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'test title 1', 'test description 1', NULL, NULL, NULL),
(2, 'test title 2', 'test description 2', NULL, NULL, NULL),
(3, 'test title 3', 'test description 3', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','disabled','') NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `status`, `role_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', '11f6ad8ec52a2984abaafd7c3b516503785c2072', 'active', 1, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file`
--
ALTER TABLE `file`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
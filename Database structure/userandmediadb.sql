-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2024 at 02:30 PM
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
-- Database: `userandmediadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicant`
--

CREATE TABLE `applicant` (
  `applicant_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `application_position` varchar(50) NOT NULL,
  `identity_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applicant`
--

INSERT INTO `applicant` (`applicant_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `application_position`, `identity_number`) VALUES
(2, 'Ofentse', 'Molefe', 'molefeofentse@hotmail.com', '$2y$10$UKvltIqAo64I5xq.H4LGEOmPCtiCtFjCfBgnbmL80aIVJpYiuGX2e', '0717748155', 'Technician', '987542'),
(3, 'Judas ', 'Mohlala ', 'judasmohlala2012@gmail.com', '$2y$10$BThAIXJGd0JQ4CotbDbiduxGebR3/qlSgK1hfGABAMTz2dYerwTM2', '0712192066', 'Technician', '9807055768084'),
(6, 'Jacob', 'zuma', 'zuma@gmail.com', '$2y$10$31p5PT3wfdO3qK8X.DHaSOuAK2.Wa/YB/Nu4GSBHXGBej93w/ROme', '0812543654', 'Lecturer', '123456789');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `candidate_ID` int(11) NOT NULL,
  `recruiter_ID` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cell_no` varchar(20) DEFAULT NULL,
  `occupation` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook`
--

CREATE TABLE `facebook` (
  `Fb_AccID` int(11) NOT NULL,
  `SocialMediaID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `Profile_URL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram`
--

CREATE TABLE `instagram` (
  `instaAccID` int(11) NOT NULL,
  `SocialMediaID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `Profile_URL` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `linkdedin`
--

CREATE TABLE `linkdedin` (
  `LinkInID` int(11) NOT NULL,
  `CandidateID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `Profile_URL` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruiter`
--

CREATE TABLE `recruiter` (
  `recruiterID` int(15) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `occupation` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiter`
--

INSERT INTO `recruiter` (`recruiterID`, `first_name`, `last_name`, `email`, `password`, `occupation`) VALUES
(1008, 'Maseko', 'zulu', 'mrszulu@gmail.com', '12345', 'Admin'),
(1009, 'Sam', 'Mthembu', 'sam@hotmail.com', 'sam123', 'Admin'),
(1010, 'Victor', 'Tau', 'tauv@gmail.com', 'tau123', 'Hr'),
(1011, 'system', 'admin', 'admin@gmail.com', '12345', 'Admin'),
(1013, 'fezo', 'Ndaba', 'ndaba@gmail.com', 'ndaba123', 'Hr'),
(1015, 'Ofentse', 'Molefe', 'molefeofentse2@hotmail.com', 'mac11', 'Hr');

-- --------------------------------------------------------

--
-- Table structure for table `socialmediaprofile`
--

CREATE TABLE `socialmediaprofile` (
  `SocialMediaID` int(11) NOT NULL,
  `candidate_ID` int(11) NOT NULL,
  `recruiter_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `twitter`
--

CREATE TABLE `twitter` (
  `TX_AccID` int(11) NOT NULL,
  `CandidateID` int(11) DEFAULT NULL,
  `User_Name` varchar(50) DEFAULT NULL,
  `Profile_URL` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicant`
--
ALTER TABLE `applicant`
  ADD PRIMARY KEY (`applicant_id`),
  ADD UNIQUE KEY `identity_number` (`identity_number`);

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`candidate_ID`);

--
-- Indexes for table `facebook`
--
ALTER TABLE `facebook`
  ADD PRIMARY KEY (`Fb_AccID`),
  ADD KEY `CandidateID` (`SocialMediaID`);

--
-- Indexes for table `instagram`
--
ALTER TABLE `instagram`
  ADD PRIMARY KEY (`instaAccID`),
  ADD UNIQUE KEY `SocialMediaID` (`SocialMediaID`),
  ADD KEY `CandidateID` (`SocialMediaID`);

--
-- Indexes for table `linkdedin`
--
ALTER TABLE `linkdedin`
  ADD PRIMARY KEY (`LinkInID`),
  ADD KEY `CandidateID` (`CandidateID`);

--
-- Indexes for table `recruiter`
--
ALTER TABLE `recruiter`
  ADD PRIMARY KEY (`recruiterID`),
  ADD UNIQUE KEY `recruiterID` (`recruiterID`);

--
-- Indexes for table `socialmediaprofile`
--
ALTER TABLE `socialmediaprofile`
  ADD PRIMARY KEY (`SocialMediaID`);

--
-- Indexes for table `twitter`
--
ALTER TABLE `twitter`
  ADD PRIMARY KEY (`TX_AccID`),
  ADD KEY `CandidateID` (`CandidateID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicant`
--
ALTER TABLE `applicant`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `recruiter`
--
ALTER TABLE `recruiter`
  MODIFY `recruiterID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1018;

--
-- AUTO_INCREMENT for table `socialmediaprofile`
--
ALTER TABLE `socialmediaprofile`
  MODIFY `SocialMediaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facebook`
--
ALTER TABLE `facebook`
  ADD CONSTRAINT `fk_facebooktable_candidate` FOREIGN KEY (`socialMediaID`) REFERENCES `candidate` (`Candidate_ID`) ON DELETE CASCADE;

--
-- Constraints for table `instagram`
--
ALTER TABLE `instagram`
  ADD CONSTRAINT `instagram_ibfk_1` FOREIGN KEY (`SocialMediaID`) REFERENCES `candidate` (`Candidate_ID`) ON DELETE CASCADE;

--
-- Constraints for table `linkdedin`
--
ALTER TABLE `linkdedin`
  ADD CONSTRAINT `linkdedin_ibfk_1` FOREIGN KEY (`CandidateID`) REFERENCES `candidate` (`Candidate_ID`) ON DELETE CASCADE;

--
-- Constraints for table `twitter`
--
ALTER TABLE `twitter`
  ADD CONSTRAINT `twitter_ibfk_1` FOREIGN KEY (`CandidateID`) REFERENCES `candidate` (`Candidate_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

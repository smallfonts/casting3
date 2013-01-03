-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2012 at 07:08 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `casting3`
--

-- --------------------------------------------------------

--
-- Table structure for table `artiste_portfolio`
--

CREATE TABLE IF NOT EXISTS `artiste_portfolio` (
  `artiste_portfolioid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `race` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `home_phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `income` varchar(255) DEFAULT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `videoid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`artiste_portfolioid`),
  KEY `userid` (`userid`),
  KEY `photoid` (`photoid`),
  KEY `videoid` (`videoid`),
  KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `artiste_portfolio`
--

INSERT INTO `artiste_portfolio` (`artiste_portfolioid`, `userid`, `name`, `race`, `gender`, `nationality`, `height`, `weight`, `email`, `home_phone`, `mobile_phone`, `dob`, `status`, `address`, `income`, `photoid`, `videoid`, `url`) VALUES
(1, 2, 'Your Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '13394141162'),
(2, 3, 'Your Name', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '13402576733'),
(3, 5, 'hello', '', 'Female', '', '', '', '', NULL, '', NULL, NULL, NULL, NULL, 1, 1, '13409512485');

-- --------------------------------------------------------

--
-- Table structure for table `artiste_portfolio_photo`
--

CREATE TABLE IF NOT EXISTS `artiste_portfolio_photo` (
  `artiste_portfolioid` bigint(100) NOT NULL,
  `photoid` bigint(100) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`artiste_portfolioid`,`photoid`),
  KEY `photoid` (`photoid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artiste_portfolio_photo`
--

INSERT INTO `artiste_portfolio_photo` (`artiste_portfolioid`, `photoid`, `order`) VALUES
(1, 2, 1),
(1, 3, 2),
(2, 2, 1),
(2, 3, 2),
(3, 2, 1),
(3, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `casting_call`
--

CREATE TABLE IF NOT EXISTS `casting_call` (
  `casting_call_id` bigint(100) NOT NULL,
  `production_portfolioid` bigint(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_desc` varchar(255) NOT NULL,
  `audition_dates` date NOT NULL,
  `duration` bigint(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `characters` varchar(255) NOT NULL,
  `experience` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  PRIMARY KEY (`casting_call_id`),
  KEY `production_portfolioid` (`production_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `casting_call`
--

INSERT INTO `casting_call` (`casting_call_id`, `production_portfolioid`, `name`, `project_title`, `project_desc`, `audition_dates`, `duration`, `location`, `characters`, `experience`, `deadline`) VALUES
(1, 1, 'oaktree', 'snow white', 'funny', '2012-06-06', 2, 'orchard', 'grumpy', '2years', '2012-06-29'),
(2, 1, 'oaktree', 'beauty and the beast', 'romance', '2012-06-20', 4, 'town', 'beast', '5years', '2012-06-19');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE IF NOT EXISTS `experience` (
  `experienceid` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `programme` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `artist_portfolioid` bigint(100) NOT NULL,
  PRIMARY KEY (`experienceid`),
  KEY `artist_portfolioid` (`artist_portfolioid`),
  KEY `artist_portfolioid_2` (`artist_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `languageid` bigint(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`languageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`languageid`, `name`) VALUES
(1, 'Acholi'),
(2, 'Afrikaans'),
(3, 'Akan'),
(4, 'Albanian'),
(5, 'Amharic'),
(6, 'Arabic'),
(7, 'Armenian'),
(8, 'Assyrian'),
(9, 'Azerbaijani'),
(10, 'Azeri'),
(11, 'Bajuni'),
(12, 'Bambara'),
(13, 'Basque'),
(14, 'Behdini'),
(15, 'Belorussian'),
(16, 'Bengali'),
(17, 'Berber'),
(18, 'Bosnian'),
(19, 'Bravanese'),
(20, 'Bulgarian'),
(21, 'Burmese'),
(22, 'Cantonese'),
(23, 'Catalan'),
(24, 'Chaldean'),
(25, 'Chaochow'),
(26, 'Chamorro'),
(27, 'Chavacano'),
(28, 'Cherokee'),
(29, 'Chuukese'),
(30, 'Croatian'),
(31, 'Czech'),
(32, 'Dakota'),
(33, 'Danish'),
(34, 'Dari'),
(35, 'Dinka'),
(36, 'Diula'),
(37, 'Dutch'),
(38, 'Ewe'),
(39, 'English'),
(40, 'Farsi'),
(41, 'Fijian Hindi'),
(42, 'Finnish'),
(43, 'Flemish'),
(44, 'French'),
(45, 'French Canadian'),
(46, 'Fukienese'),
(47, 'Fula'),
(48, 'Fulani'),
(49, 'Fuzhou'),
(50, 'Gaddang'),
(51, 'Georgian'),
(52, 'German'),
(53, 'Gorani'),
(54, 'Greek'),
(55, 'Gujarati'),
(56, 'Haitian Creole'),
(57, 'Hakka'),
(58, 'Hausa'),
(59, 'Hebrew'),
(60, 'Hindi'),
(61, 'Hmong'),
(62, 'Hokkien'),
(63, 'Hunanese'),
(64, 'Hungarian'),
(65, 'Ibanag'),
(66, 'Ibo/Igbo'),
(67, 'Icelandic'),
(68, 'Ilocano'),
(69, 'Indonesian'),
(70, 'Italian'),
(71, 'Jakartanese'),
(72, 'Japanese'),
(73, 'Javanese'),
(74, 'Karen'),
(75, 'Kashmiri'),
(76, 'Kazakh'),
(77, 'Khmer (Cambodian)'),
(78, 'Kinyarwanda'),
(79, 'Kirghiz'),
(80, 'Kirundi'),
(81, 'Korean'),
(82, 'Kosovan'),
(83, 'Krio'),
(84, 'Kurdish'),
(85, 'Kurmanji'),
(86, 'Lakota'),
(87, 'Laotian'),
(88, 'Latvian'),
(89, 'Lingala'),
(90, 'Lithuanian'),
(91, 'Luganda'),
(92, 'Luxembourgeois'),
(93, 'Maay'),
(94, 'Macedonian'),
(95, 'Malagasy'),
(96, 'Malay'),
(97, 'Malayalam'),
(98, 'Maltese'),
(99, 'Mandarin'),
(100, 'Mandingo'),
(101, 'Mandinka'),
(102, 'Maninka'),
(103, 'Mankon'),
(104, 'Marathi'),
(105, 'Marshallese'),
(106, 'Mien'),
(107, 'Mina'),
(108, 'Mirpuri'),
(109, 'Mixteco'),
(110, 'Moldavan'),
(111, 'Mongolian'),
(112, 'Montenegrin'),
(113, 'Navajo'),
(114, 'Neapolitan'),
(115, 'Nepali'),
(116, 'Nigerian Pidgin English'),
(117, 'Norwegian'),
(118, 'Nuer'),
(119, 'Oromo'),
(120, 'Pahari'),
(121, 'Pampangan'),
(122, 'Pamgasinan'),
(123, 'Pashto'),
(124, 'Patois'),
(125, 'Pidgin English'),
(126, 'Polish'),
(127, 'Portuguese'),
(128, 'Portuguese Creole'),
(129, 'Punjabi'),
(130, 'Romanian'),
(131, 'Russian'),
(132, 'Samoan'),
(133, 'Serbian'),
(134, 'Shanghainese'),
(135, 'Shona'),
(136, 'Sicilian'),
(137, 'Sinhalese'),
(138, 'Sindhi'),
(139, 'Slovak'),
(140, 'Slovenian'),
(141, 'Somali'),
(142, 'Sorani'),
(143, 'Spanish'),
(144, 'Sudanese Arabic'),
(145, 'Swahili'),
(146, 'Swedish'),
(147, 'Sylhetti'),
(148, 'Tagalog'),
(149, 'Taiwanese'),
(150, 'Tajik'),
(151, 'Tamil'),
(152, 'Telugu'),
(153, 'Thai'),
(154, 'Tibetan'),
(155, 'Tigre'),
(156, 'Tigrinya'),
(157, 'Toishanese'),
(158, 'Tongan'),
(159, 'Tshiluba'),
(160, 'Turkish'),
(161, 'Twi'),
(162, 'Ukrainian'),
(163, 'Urdu'),
(164, 'Uzbek'),
(165, 'Vietnamese'),
(166, 'Visayan'),
(167, 'Welsh'),
(168, 'Wolof'),
(169, 'Yiddish'),
(170, 'Yoruba'),
(171, 'Yupik');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_token`
--

CREATE TABLE IF NOT EXISTS `password_reset_token` (
  `userid` bigint(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `photoid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`photoid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`photoid`, `userid`, `url`) VALUES
(1, NULL, '/timberwerkz/images/photos/anonymous.jpg'),
(2, NULL, '/timberwerkz/images/photos/anonymous_2.jpg'),
(3, NULL, '/timberwerkz/images/photos/anonymous_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `production_portfolio`
--

CREATE TABLE IF NOT EXISTS `production_portfolio` (
  `production_portfolioid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `postalcode` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`production_portfolioid`),
  KEY `photoid` (`photoid`),
  KEY `userid` (`userid`),
  KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `production_portfolio`
--

INSERT INTO `production_portfolio` (`production_portfolioid`, `userid`, `name`, `country`, `address`, `address2`, `postalcode`, `email`, `phone`, `photoid`, `url`) VALUES
(1, 4, 'Production House', NULL, NULL, NULL, '', NULL, NULL, 1, '13409461734'),
(2, 6, 'Production House', NULL, NULL, NULL, '', NULL, NULL, 1, '13410555136');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `roleid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`roleid`, `name`) VALUES
(1, 'Artiste'),
(2, 'Production House'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `spoken_language`
--

CREATE TABLE IF NOT EXISTS `spoken_language` (
  `languageid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  PRIMARY KEY (`languageid`,`artiste_portfolioid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `statusid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`statusid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusid`, `name`) VALUES
(1, 'active'),
(2, 'locked'),
(3, 'suspended'),
(4, 'deleted'),
(5, 'public'),
(6, 'private');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE IF NOT EXISTS `user_account` (
  `userid` bigint(100) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `roleid` int(10) NOT NULL,
  `statusid` int(10) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`roleid`),
  KEY `status` (`statusid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`userid`, `password`, `email`, `roleid`, `statusid`) VALUES
(1, 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'admin@admin.com', 3, 1),
(2, 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 'reginalme@gmail.com', 1, 1),
(3, 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'funkychunkyr_r@hotmail.com', 1, 1),
(4, 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 'regina.lee.2009@sis.smu.edu.sg', 2, 1),
(5, '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'her@email.com', 1, 1),
(6, 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '1@1.com', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `videoid` bigint(100) NOT NULL,
  `userid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`videoid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`videoid`, `userid`, `url`) VALUES
(1, NULL, 'iUmtSpGhMEs');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artiste_portfolio`
--
ALTER TABLE `artiste_portfolio`
  ADD CONSTRAINT `artiste_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  ADD CONSTRAINT `artiste_portfolio_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`),
  ADD CONSTRAINT `artiste_portfolio_ibfk_3` FOREIGN KEY (`videoid`) REFERENCES `video` (`videoid`);

--
-- Constraints for table `artiste_portfolio_photo`
--
ALTER TABLE `artiste_portfolio_photo`
  ADD CONSTRAINT `artiste_portfolio_photo_ibfk_1` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  ADD CONSTRAINT `artiste_portfolio_photo_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`);

--
-- Constraints for table `casting_call`
--
ALTER TABLE `casting_call`
  ADD CONSTRAINT `casting_call_ibfk_1` FOREIGN KEY (`production_portfolioid`) REFERENCES `production_portfolio` (`production_portfolioid`);

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`artist_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`);

--
-- Constraints for table `password_reset_token`
--
ALTER TABLE `password_reset_token`
  ADD CONSTRAINT `password_reset_token_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`);

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`);

--
-- Constraints for table `production_portfolio`
--
ALTER TABLE `production_portfolio`
  ADD CONSTRAINT `production_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  ADD CONSTRAINT `production_portfolio_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`);

--
-- Constraints for table `spoken_language`
--
ALTER TABLE `spoken_language`
  ADD CONSTRAINT `spoken_language_ibfk_1` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  ADD CONSTRAINT `spoken_language_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`languageid`);

--
-- Constraints for table `user_account`
--
ALTER TABLE `user_account`
  ADD CONSTRAINT `user_account_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `role` (`roleid`),
  ADD CONSTRAINT `user_account_ibfk_2` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`);

--
-- Constraints for table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

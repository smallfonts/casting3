DROP DATABASE `casting3`;CREATE DATABASE `casting3` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;USE `casting3`; -- MySQL dump 10.13  Distrib 5.1.56-ndb-7.1.15a, for Win64 (unknown)
--
-- Host: localhost    Database: casting3
-- ------------------------------------------------------
-- Server version	5.5.20-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `artiste_portfolio`
--

DROP TABLE IF EXISTS `artiste_portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artiste_portfolio` (
  `artiste_portfolioid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `race` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `videoid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `chest` varchar(255) DEFAULT NULL,
  `waist` varchar(255) DEFAULT NULL,
  `hip` varchar(255) DEFAULT NULL,
  `shoe` varchar(255) DEFAULT NULL,
  `desc` varchar(500) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `years_of_experience` int(100) DEFAULT NULL,
  PRIMARY KEY (`artiste_portfolioid`),
  KEY `userid` (`userid`),
  KEY `photoid` (`photoid`),
  KEY `videoid` (`videoid`),
  KEY `url` (`url`),
  CONSTRAINT `artiste_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `artiste_portfolio_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`),
  CONSTRAINT `artiste_portfolio_ibfk_3` FOREIGN KEY (`videoid`) REFERENCES `video` (`videoid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `artiste_portfolio_photo`
--

DROP TABLE IF EXISTS `artiste_portfolio_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artiste_portfolio_photo` (
  `artiste_portfolioid` bigint(100) NOT NULL,
  `photoid` bigint(100) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`artiste_portfolioid`,`photoid`),
  KEY `photoid` (`photoid`),
  CONSTRAINT `artiste_portfolio_photo_ibfk_1` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `artiste_portfolio_photo_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `artiste_portfolio_profession`
--

DROP TABLE IF EXISTS `artiste_portfolio_profession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artiste_portfolio_profession` (
  `professionid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  UNIQUE KEY `professionid` (`professionid`,`artiste_portfolioid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  CONSTRAINT `artiste_portfolio_profession_ibfk_1` FOREIGN KEY (`professionid`) REFERENCES `profession` (`professionid`),
  CONSTRAINT `artiste_portfolio_profession_ibfk_2` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `artiste_portfolio_skills`
--

DROP TABLE IF EXISTS `artiste_portfolio_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `artiste_portfolio_skills` (
  `skillid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  PRIMARY KEY (`skillid`,`artiste_portfolioid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  CONSTRAINT `artiste_portfolio_skills_ibfk_1` FOREIGN KEY (`skillid`) REFERENCES `skill` (`skillid`),
  CONSTRAINT `artiste_portfolio_skills_ibfk_2` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `casting_call`
--

DROP TABLE IF EXISTS `casting_call`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casting_call` (
  `casting_callid` bigint(100) NOT NULL AUTO_INCREMENT,
  `production_portfolioid` bigint(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `audition_start` date DEFAULT NULL,
  `audition_end` date DEFAULT NULL,
  `project_start` date DEFAULT NULL,
  `project_end` date DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `statusid` int(10) NOT NULL,
  `created` date NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`casting_callid`),
  KEY `production_portfolioid` (`production_portfolioid`),
  KEY `photoid` (`photoid`),
  KEY `statusid` (`statusid`),
  CONSTRAINT `casting_call_ibfk_1` FOREIGN KEY (`production_portfolioid`) REFERENCES `production_portfolio` (`production_portfolioid`),
  CONSTRAINT `casting_call_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `character`
--

DROP TABLE IF EXISTS `character`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `character` (
  `characterid` bigint(100) NOT NULL AUTO_INCREMENT,
  `casting_callid` bigint(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `gender` varchar(255) NOT NULL,
  `age_start` bigint(100) NOT NULL,
  `age_end` bigint(100) NOT NULL,
  `statusid` int(10) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`characterid`),
  KEY `casting_callid` (`casting_callid`),
  CONSTRAINT `character_ibfk_1` FOREIGN KEY (`casting_callid`) REFERENCES `casting_call` (`casting_callid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `character_application`
--

DROP TABLE IF EXISTS `character_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `character_application` (
  `character_applicationid` bigint(100) NOT NULL AUTO_INCREMENT,
  `characterid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  `application_date` date NOT NULL,
  `statusid` int(10) NOT NULL,
  `videoid` bigint(100) DEFAULT NULL,
  PRIMARY KEY (`character_applicationid`),
  KEY `statusid` (`statusid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `characterid` (`characterid`),
  KEY `videoid` (`videoid`),
  CONSTRAINT `character_application_ibfk_4` FOREIGN KEY (`videoid`) REFERENCES `video` (`videoid`),
  CONSTRAINT `character_application_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`),
  CONSTRAINT `character_application_ibfk_2` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `character_application_ibfk_3` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `character_language`
--

DROP TABLE IF EXISTS `character_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `character_language` (
  `characterid` bigint(100) NOT NULL,
  `languageid` bigint(100) NOT NULL,
  PRIMARY KEY (`characterid`,`languageid`),
  KEY `languageid` (`languageid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `character_language_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`),
  CONSTRAINT `character_language_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`languageid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `experience`
--

DROP TABLE IF EXISTS `experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `experience` (
  `experienceid` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `programme` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `artist_portfolioid` bigint(100) NOT NULL,
  PRIMARY KEY (`experienceid`),
  KEY `artist_portfolioid` (`artist_portfolioid`),
  KEY `artist_portfolioid_2` (`artist_portfolioid`),
  CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`artist_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favourite_artiste_portfolio`
--

DROP TABLE IF EXISTS `favourite_artiste_portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favourite_artiste_portfolio` (
  `userid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  PRIMARY KEY (`userid`,`artiste_portfolioid`),
  KEY `userid` (`userid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  CONSTRAINT `favourite_artiste_portfolio_ibfk_3` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `favourite_artiste_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `favourite_character`
--

DROP TABLE IF EXISTS `favourite_character`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favourite_character` (
  `userid` bigint(100) NOT NULL,
  `characterid` bigint(100) NOT NULL,
  PRIMARY KEY (`userid`,`characterid`),
  KEY `userid` (`userid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `favourite_character_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `favourite_character_ibfk_2` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language` (
  `languageid` bigint(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`languageid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `language_proficiency`
--

DROP TABLE IF EXISTS `language_proficiency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language_proficiency` (
  `language_proficiencyid` bigint(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`language_proficiencyid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_token`
--

DROP TABLE IF EXISTS `password_reset_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_token` (
  `userid` bigint(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`,`token`),
  CONSTRAINT `password_reset_token_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `photoid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `statusid` int(10) NOT NULL,
  PRIMARY KEY (`photoid`),
  KEY `userid` (`userid`),
  KEY `statusid` (`statusid`),
  CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `production_portfolio`
--

DROP TABLE IF EXISTS `production_portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_portfolio` (
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
  KEY `url` (`url`),
  CONSTRAINT `production_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `production_portfolio_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profession`
--

DROP TABLE IF EXISTS `profession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profession` (
  `professionid` bigint(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`professionid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `roleid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `skill`
--

DROP TABLE IF EXISTS `skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skill` (
  `skillid` bigint(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`skillid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `spoken_language`
--

DROP TABLE IF EXISTS `spoken_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spoken_language` (
  `languageid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  `language_proficiencyid` bigint(100) NOT NULL,
  PRIMARY KEY (`languageid`,`artiste_portfolioid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `language_proficiencyid` (`language_proficiencyid`),
  CONSTRAINT `spoken_language_ibfk_1` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `spoken_language_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`languageid`),
  CONSTRAINT `spoken_language_ibfk_3` FOREIGN KEY (`language_proficiencyid`) REFERENCES `language_proficiency` (`language_proficiencyid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `statusid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_account`
--

DROP TABLE IF EXISTS `user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_account` (
  `userid` bigint(100) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `roleid` int(10) NOT NULL,
  `statusid` int(10) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`roleid`),
  KEY `status` (`statusid`),
  CONSTRAINT `user_account_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `role` (`roleid`),
  CONSTRAINT `user_account_ibfk_2` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `video`
--

DROP TABLE IF EXISTS `video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `video` (
  `videoid` bigint(100) NOT NULL,
  `userid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`videoid`),
  KEY `userid` (`userid`),
  CONSTRAINT `video_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-15 11:49:50

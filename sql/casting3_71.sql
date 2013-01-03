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
-- Table structure for table `application_photo`
--

DROP TABLE IF EXISTS `application_photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_photo` (
  `application_photoid` bigint(100) NOT NULL AUTO_INCREMENT,
  `artiste_portfolioid` bigint(100) NOT NULL,
  `photoid` bigint(100) NOT NULL,
  `character_photo_attachmentid` bigint(100) NOT NULL,
  `characterid` bigint(100) NOT NULL,
  PRIMARY KEY (`application_photoid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `photoid` (`photoid`),
  KEY `character_photo_attachmentid` (`character_photo_attachmentid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `application_photo_ibfk_1` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `application_photo_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`),
  CONSTRAINT `application_photo_ibfk_3` FOREIGN KEY (`character_photo_attachmentid`) REFERENCES `character_photo_attachment` (`character_photo_attachmentid`),
  CONSTRAINT `application_photo_ibfk_4` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_video`
--

DROP TABLE IF EXISTS `application_video`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_video` (
  `application_videoid` bigint(100) NOT NULL AUTO_INCREMENT,
  `artiste_portfolioid` bigint(100) NOT NULL,
  `videoid` bigint(100) NOT NULL,
  `character_video_attachmentid` bigint(100) NOT NULL,
  `characterid` bigint(100) NOT NULL,
  PRIMARY KEY (`application_videoid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `videoid` (`videoid`),
  KEY `character_video_attachmentid` (`character_video_attachmentid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `application_video_ibfk_1` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `application_video_ibfk_2` FOREIGN KEY (`videoid`) REFERENCES `video` (`videoid`),
  CONSTRAINT `application_video_ibfk_3` FOREIGN KEY (`character_video_attachmentid`) REFERENCES `character_video_attachment` (`character_video_attachmentid`),
  CONSTRAINT `application_video_ibfk_4` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `ethnicityid` bigint(100) DEFAULT NULL,
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
  `portfolio_guide` bigint(100) DEFAULT '0',
  `experience` text NOT NULL,
  `audition_guide` bigint(100) DEFAULT '0',
  PRIMARY KEY (`artiste_portfolioid`),
  KEY `userid` (`userid`),
  KEY `photoid` (`photoid`),
  KEY `videoid` (`videoid`),
  KEY `url` (`url`),
  KEY `ethnicityid` (`ethnicityid`),
  CONSTRAINT `artiste_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `artiste_portfolio_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`),
  CONSTRAINT `artiste_portfolio_ibfk_3` FOREIGN KEY (`videoid`) REFERENCES `video` (`videoid`),
  CONSTRAINT `artiste_portfolio_ibfk_4` FOREIGN KEY (`ethnicityid`) REFERENCES `ethnicity` (`ethnicityid`)
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
-- Table structure for table `audition`
--

DROP TABLE IF EXISTS `audition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audition` (
  `auditionid` bigint(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `casting_callid` bigint(100) NOT NULL,
  `production_portfolioid` bigint(100) NOT NULL,
  `casting_manager_portfolioid` bigint(100) NOT NULL,
  `application_start` date DEFAULT NULL,
  `application_end` date DEFAULT NULL,
  `statusid` int(10) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`auditionid`),
  KEY `casting_callid` (`casting_callid`),
  KEY `production_portfolioid` (`production_portfolioid`),
  KEY `statusid` (`statusid`),
  KEY `casting_manager_portfolioid` (`casting_manager_portfolioid`),
  CONSTRAINT `audition_ibfk_1` FOREIGN KEY (`casting_callid`) REFERENCES `casting_call` (`casting_callid`),
  CONSTRAINT `audition_ibfk_2` FOREIGN KEY (`production_portfolioid`) REFERENCES `production_portfolio` (`production_portfolioid`),
  CONSTRAINT `audition_ibfk_3` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`),
  CONSTRAINT `audition_ibfk_4` FOREIGN KEY (`casting_manager_portfolioid`) REFERENCES `casting_manager_portfolio` (`casting_manager_portfolioid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audition_interviewee`
--

DROP TABLE IF EXISTS `audition_interviewee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audition_interviewee` (
  `audition_intervieweeid` bigint(100) NOT NULL AUTO_INCREMENT,
  `auditionid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  `character_applicationid` bigint(100) DEFAULT NULL,
  `statusid` int(10) DEFAULT '12',
  `notified` tinyint(1) NOT NULL,
  PRIMARY KEY (`audition_intervieweeid`),
  KEY `auditionid` (`auditionid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `statusid` (`statusid`),
  KEY `character_applicationid` (`character_applicationid`),
  CONSTRAINT `audition_interviewee_ibfk_1` FOREIGN KEY (`auditionid`) REFERENCES `audition` (`auditionid`),
  CONSTRAINT `audition_interviewee_ibfk_2` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `audition_interviewee_ibfk_3` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`),
  CONSTRAINT `audition_interviewee_ibfk_4` FOREIGN KEY (`character_applicationid`) REFERENCES `character_application` (`character_applicationid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audition_interviewee_slot`
--

DROP TABLE IF EXISTS `audition_interviewee_slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audition_interviewee_slot` (
  `audition_interviewee_slotid` bigint(100) NOT NULL AUTO_INCREMENT,
  `audition_intervieweeid` bigint(100) NOT NULL,
  `auditionid` bigint(100) NOT NULL,
  `artiste_portfolioid` bigint(100) NOT NULL,
  `audition_slotid` bigint(100) NOT NULL,
  `priority` int(11) NOT NULL,
  `statusid` int(10) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`audition_interviewee_slotid`),
  KEY `audition_slotid` (`audition_slotid`),
  KEY `statusid` (`statusid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `auditionid` (`auditionid`),
  KEY `audition_intervieweeid` (`audition_intervieweeid`),
  CONSTRAINT `audition_interviewee_slot_ibfk_1` FOREIGN KEY (`audition_slotid`) REFERENCES `audition_slot` (`audition_slotid`),
  CONSTRAINT `audition_interviewee_slot_ibfk_2` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`),
  CONSTRAINT `audition_interviewee_slot_ibfk_4` FOREIGN KEY (`auditionid`) REFERENCES `audition` (`auditionid`),
  CONSTRAINT `audition_interviewee_slot_ibfk_5` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `audition_interviewee_slot_ibfk_6` FOREIGN KEY (`audition_intervieweeid`) REFERENCES `audition_interviewee` (`audition_intervieweeid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audition_note`
--

DROP TABLE IF EXISTS `audition_note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audition_note` (
  `audition_noteid` bigint(100) NOT NULL AUTO_INCREMENT,
  `auditionid` bigint(100) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`audition_noteid`),
  KEY `auditionid` (`auditionid`),
  CONSTRAINT `audition_note_ibfk_1` FOREIGN KEY (`auditionid`) REFERENCES `audition` (`auditionid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audition_slot`
--

DROP TABLE IF EXISTS `audition_slot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audition_slot` (
  `audition_slotid` bigint(100) NOT NULL AUTO_INCREMENT,
  `auditionid` bigint(100) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `statusid` int(10) NOT NULL,
  `fixed` tinyint(1) NOT NULL,
  PRIMARY KEY (`audition_slotid`),
  KEY `auditionid` (`auditionid`),
  KEY `statusid` (`statusid`),
  CONSTRAINT `audition_slot_ibfk_1` FOREIGN KEY (`auditionid`) REFERENCES `audition` (`auditionid`),
  CONSTRAINT `audition_slot_ibfk_2` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
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
  `casting_manager_portfolioid` bigint(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `application_start` date DEFAULT NULL,
  `application_end` date DEFAULT NULL,
  `audition_start` date DEFAULT NULL,
  `audition_end` date DEFAULT NULL,
  `project_start` date DEFAULT NULL,
  `project_end` date DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `statusid` int(10) NOT NULL,
  `created` date NOT NULL,
  `url` varchar(255) NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`casting_callid`),
  KEY `production_portfolioid` (`production_portfolioid`),
  KEY `photoid` (`photoid`),
  KEY `statusid` (`statusid`),
  KEY `casting_manager_portfolioid` (`casting_manager_portfolioid`),
  CONSTRAINT `casting_call_ibfk_1` FOREIGN KEY (`production_portfolioid`) REFERENCES `production_portfolio` (`production_portfolioid`),
  CONSTRAINT `casting_call_ibfk_2` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`),
  CONSTRAINT `casting_call_ibfk_3` FOREIGN KEY (`casting_manager_portfolioid`) REFERENCES `casting_manager_portfolio` (`casting_manager_portfolioid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `casting_call_invitation`
--

DROP TABLE IF EXISTS `casting_call_invitation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casting_call_invitation` (
  `casting_call_invitationid` bigint(100) NOT NULL AUTO_INCREMENT,
  `casting_callid` bigint(100) DEFAULT NULL,
  `artiste_portfolioid` bigint(100) DEFAULT NULL,
  `statusid` bigint(100) DEFAULT '12',
  `notified` tinyint(1) NOT NULL,
  PRIMARY KEY (`casting_call_invitationid`),
  KEY `casting_callid` (`casting_callid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `statusid` (`statusid`),
  CONSTRAINT `casting_call_invitation_ibfk_1` FOREIGN KEY (`casting_callid`) REFERENCES `casting_call` (`casting_callid`),
  CONSTRAINT `casting_call_invitation_ibfk_2` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `casting_manager_portfolio`
--

DROP TABLE IF EXISTS `casting_manager_portfolio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `casting_manager_portfolio` (
  `casting_manager_portfolioid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `statusid` int(10) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `audition_guide` bigint(100) DEFAULT '0',
  `castingcall_guide` bigint(100) DEFAULT '0',
  PRIMARY KEY (`casting_manager_portfolioid`),
  KEY `userid` (`userid`),
  KEY `statusid` (`statusid`),
  KEY `photoid` (`photoid`),
  CONSTRAINT `casting_manager_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `casting_manager_portfolio_ibfk_2` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`),
  CONSTRAINT `casting_manager_portfolio_ibfk_3` FOREIGN KEY (`photoid`) REFERENCES `photo` (`photoid`)
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
  `age_start` bigint(100) DEFAULT NULL,
  `age_end` bigint(100) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `ethnicityid` bigint(100) DEFAULT NULL,
  `statusid` int(10) NOT NULL,
  `created` date NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`characterid`),
  KEY `casting_callid` (`casting_callid`),
  KEY `ethnicityid` (`ethnicityid`),
  CONSTRAINT `character_ibfk_1` FOREIGN KEY (`casting_callid`) REFERENCES `casting_call` (`casting_callid`),
  CONSTRAINT `character_ibfk_2` FOREIGN KEY (`ethnicityid`) REFERENCES `ethnicity` (`ethnicityid`)
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
  `comments` text,
  `rating` int(11) DEFAULT NULL,
  `statusid` int(10) NOT NULL,
  PRIMARY KEY (`character_applicationid`),
  KEY `statusid` (`statusid`),
  KEY `artiste_portfolioid` (`artiste_portfolioid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `character_application_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`),
  CONSTRAINT `character_application_ibfk_2` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`),
  CONSTRAINT `character_application_ibfk_3` FOREIGN KEY (`statusid`) REFERENCES `status` (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
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
  `language_proficiencyid` bigint(100) NOT NULL,
  PRIMARY KEY (`characterid`,`languageid`),
  KEY `languageid` (`languageid`),
  KEY `characterid` (`characterid`),
  KEY `language_proficiencyid` (`language_proficiencyid`),
  CONSTRAINT `character_language_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`),
  CONSTRAINT `character_language_ibfk_2` FOREIGN KEY (`languageid`) REFERENCES `language` (`languageid`),
  CONSTRAINT `character_language_ibfk_4` FOREIGN KEY (`language_proficiencyid`) REFERENCES `language_proficiency` (`language_proficiencyid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `character_photo_attachment`
--

DROP TABLE IF EXISTS `character_photo_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `character_photo_attachment` (
  `character_photo_attachmentid` bigint(100) NOT NULL AUTO_INCREMENT,
  `characterid` bigint(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`character_photo_attachmentid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `character_photo_attachment_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `character_skill`
--

DROP TABLE IF EXISTS `character_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `character_skill` (
  `characterid` bigint(100) NOT NULL,
  `skillid` bigint(100) NOT NULL,
  PRIMARY KEY (`characterid`,`skillid`),
  KEY `skillid` (`skillid`),
  CONSTRAINT `character_skill_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`),
  CONSTRAINT `character_skill_ibfk_2` FOREIGN KEY (`skillid`) REFERENCES `skill` (`skillid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `character_video_attachment`
--

DROP TABLE IF EXISTS `character_video_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `character_video_attachment` (
  `character_video_attachmentid` bigint(100) NOT NULL AUTO_INCREMENT,
  `characterid` bigint(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`character_video_attachmentid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `character_video_attachment_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ethnicity`
--

DROP TABLE IF EXISTS `ethnicity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ethnicity` (
  `ethnicityid` bigint(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`ethnicityid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
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
  CONSTRAINT `favourite_artiste_portfolio_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `favourite_artiste_portfolio_ibfk_3` FOREIGN KEY (`artiste_portfolioid`) REFERENCES `artiste_portfolio` (`artiste_portfolioid`)
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
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `messageid` bigint(100) NOT NULL AUTO_INCREMENT,
  `reply_messageid` bigint(100) DEFAULT NULL,
  `userid` bigint(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`messageid`),
  KEY `userid` (`userid`),
  KEY `reply_messageid` (`reply_messageid`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`reply_messageid`) REFERENCES `message` (`messageid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_recipient`
--

DROP TABLE IF EXISTS `message_recipient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_recipient` (
  `message_recipientid` bigint(100) NOT NULL AUTO_INCREMENT,
  `messageid` bigint(100) NOT NULL,
  `userid` bigint(100) NOT NULL,
  `statusid` int(10) NOT NULL,
  `last_modified` datetime NOT NULL,
  `notified` tinyint(1) NOT NULL,
  PRIMARY KEY (`message_recipientid`),
  KEY `messageid` (`messageid`),
  KEY `userid` (`userid`),
  KEY `last_modified` (`last_modified`),
  CONSTRAINT `message_recipient_ibfk_1` FOREIGN KEY (`messageid`) REFERENCES `message` (`messageid`),
  CONSTRAINT `message_recipient_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_sent`
--

DROP TABLE IF EXISTS `message_sent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_sent` (
  `message_sentid` bigint(100) NOT NULL AUTO_INCREMENT,
  `messageid` bigint(100) NOT NULL,
  `userid` bigint(100) NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`message_sentid`),
  KEY `userid` (`userid`),
  KEY `messageid` (`messageid`),
  KEY `last_modified` (`last_modified`),
  CONSTRAINT `message_sent_ibfk_1` FOREIGN KEY (`messageid`) REFERENCES `message` (`messageid`),
  CONSTRAINT `message_sent_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `message_tree`
--

DROP TABLE IF EXISTS `message_tree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message_tree` (
  `parent_messageid` bigint(100) NOT NULL,
  `child_messageid` bigint(100) NOT NULL,
  PRIMARY KEY (`parent_messageid`,`child_messageid`),
  KEY `child_messageid` (`child_messageid`),
  CONSTRAINT `message_tree_ibfk_2` FOREIGN KEY (`parent_messageid`) REFERENCES `message` (`reply_messageid`),
  CONSTRAINT `message_tree_ibfk_3` FOREIGN KEY (`child_messageid`) REFERENCES `message` (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `other_requirement`
--

DROP TABLE IF EXISTS `other_requirement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `other_requirement` (
  `other_requirementid` bigint(100) NOT NULL AUTO_INCREMENT,
  `characterid` bigint(100) NOT NULL,
  `requirement` varchar(255) DEFAULT NULL,
  `desc` text,
  PRIMARY KEY (`other_requirementid`),
  KEY `characterid` (`characterid`),
  CONSTRAINT `other_requirement_ibfk_1` FOREIGN KEY (`characterid`) REFERENCES `character` (`characterid`)
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
-- Table structure for table `production_house_user`
--

DROP TABLE IF EXISTS `production_house_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_house_user` (
  `production_house_userid` bigint(100) NOT NULL AUTO_INCREMENT,
  `production_userid` bigint(100) NOT NULL,
  `cm_userid` bigint(100) NOT NULL,
  PRIMARY KEY (`production_house_userid`),
  KEY `production_userid` (`production_userid`),
  KEY `cm_userid` (`cm_userid`),
  CONSTRAINT `production_house_user_ibfk_1` FOREIGN KEY (`production_userid`) REFERENCES `user_account` (`userid`),
  CONSTRAINT `production_house_user_ibfk_2` FOREIGN KEY (`cm_userid`) REFERENCES `user_account` (`userid`)
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
  `postalcode` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photoid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `products` varchar(500) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
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
  `youtube_token` varchar(255) DEFAULT NULL,
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
  `videoid` bigint(100) NOT NULL AUTO_INCREMENT,
  `userid` bigint(100) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`videoid`),
  KEY `userid` (`userid`),
  CONSTRAINT `video_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user_account` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-11-01 17:15:35

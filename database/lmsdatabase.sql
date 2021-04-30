-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 23, 2019 at 08:15 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `myschool`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `ann_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_id` int(11) NOT NULL,
  `tea_id` int(11) NOT NULL,
  `ann_name` varchar(50) NOT NULL,
  `ann_description` varchar(500) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`ann_id`),
  UNIQUE KEY `ann_id` (`ann_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`ann_id`, `sub_id`, `tea_id`, `ann_name`, `ann_description`, `date_posted`, `last_modified`) VALUES
(11, 49, 142, 'dieuieui', 'ieowgwjglwjg;w', '2019-05-08 16:27:34', NULL),
(12, 49, 142, 'hbjhhgy', 'jgjhjgjghlkm; kygdggyf', '2019-05-08 17:26:25', '2019-05-08(musasa khumalo)'),
(27, 46, 142, '&lt;gg&gt;heh', '&lt;heh&gt;jheh', '2019-06-14 11:11:49', NULL),
(24, 46, 142, 'Test number 2', 'data for the test', '2019-06-09 16:23:59', '2019-06-09(musasa khumalo)'),
(25, 46, 142, '&lt;ngtj', '&lt;hthhty', '2019-06-12 10:08:25', NULL),
(26, 46, 142, '&lt;bhgg', '&lt;hsgh', '2019-06-14 11:11:33', NULL),
(28, 46, 142, '&lt;heheh', 'ndeheh&gt;', '2019-06-14 11:12:43', NULL),
(9, 42, 142, 'khkhkhh', 'jhlkhhlj[j[pfivkj[iuhg', '2019-05-06 16:29:21', '2019-05-07(musasa khumalo)'),
(29, 46, 142, '&lt;gegeg', '&lt;hegeg', '2019-06-14 11:12:52', NULL),
(30, 44, 142, 'test', 'this is just an announcement', '2019-06-23 11:32:51', '2019-06-23(musa khumalo)');

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

DROP TABLE IF EXISTS `assignment`;
CREATE TABLE IF NOT EXISTS `assignment` (
  `ass_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_id` int(11) NOT NULL,
  `tea_id` int(11) NOT NULL,
  `ass_name` varchar(50) NOT NULL,
  `ass_description` varchar(500) NOT NULL,
  `date_posted` date NOT NULL,
  `due_date` date NOT NULL,
  `marks` int(11) NOT NULL,
  `upload_link` varchar(10) NOT NULL,
  PRIMARY KEY (`ass_id`),
  UNIQUE KEY `ass_id` (`ass_id`),
  KEY `sub_id` (`sub_id`),
  KEY `tea_id` (`tea_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`ass_id`, `sub_id`, `tea_id`, `ass_name`, `ass_description`, `date_posted`, `due_date`, `marks`, `upload_link`) VALUES
(42, 43, 142, 'Test now wednesday', 'A test intended solely for fixing the student menu.', '2019-10-23', '2019-10-24', 20, 'enable');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_submission`
--

DROP TABLE IF EXISTS `assignment_submission`;
CREATE TABLE IF NOT EXISTS `assignment_submission` (
  `ass_sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `ass_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `ass_sub_status` int(11) NOT NULL DEFAULT '0',
  `ass_sub_grade` int(11) NOT NULL DEFAULT '0',
  `ass_sub_date` date NOT NULL,
  `ass_sub_path` varchar(200) NOT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `feedback` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`ass_sub_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assignment_submission`
--

INSERT INTO `assignment_submission` (`ass_sub_id`, `ass_id`, `stu_id`, `ass_sub_status`, `ass_sub_grade`, `ass_sub_date`, `ass_sub_path`, `comment`, `feedback`) VALUES
(37, 42, 137, 0, 0, '2019-10-23', './submission/20191023_d709d632b36194687569a16ee31daa1e.jpg', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `tea_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`tea_id`,`sub_id`),
  KEY `sub_id` (`sub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`tea_id`, `sub_id`, `date_created`) VALUES
(122, 36, '2019-03-24 22:00:00'),
(142, 43, '2019-06-14 11:16:36'),
(142, 44, '2019-06-14 11:16:36');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `con_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_id` int(11) NOT NULL,
  `tea_id` int(11) NOT NULL,
  `con_name` varchar(100) NOT NULL,
  `con_path` varchar(1000) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`con_id`),
  UNIQUE KEY `con_id` (`con_id`),
  KEY `sub_id` (`sub_id`),
  KEY `tea_id` (`tea_id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`con_id`, `sub_id`, `tea_id`, `con_name`, `con_path`, `date_posted`) VALUES
(5, 42, 142, 'one more test boys', './content/55881716_2692514824093393_4668303170676457472_n.jpg', '2019-05-10 11:33:42'),
(4, 42, 142, 'New test', './content/js-intro.pdf', '2019-05-10 08:19:18'),
(6, 42, 142, 'ola man', './content/39753184_2044884342221720_720411460311711744_n.jpg', '2019-05-10 11:37:36'),
(8, 42, 142, 'hello', './content/spooky.html.png', '2019-05-10 11:44:37'),
(9, 42, 142, 'ihbiyh', './content/spooky.html.png', '2019-05-10 11:45:33'),
(11, 46, 142, 'hello world', './content/59203846_2171043219647660_5063259262180917248_n.jpg', '2019-05-12 20:57:53'),
(12, 46, 142, 'Test numner 2', './content/js-intro.pdf', '2019-05-12 21:01:03'),
(14, 46, 142, 'test2', './content/almost there4.png', '2019-05-13 07:24:55'),
(15, 46, 142, 'jjjj', './content/36722681_2223426381002242_1444063164210610176_n.jpg', '2019-05-19 15:10:02'),
(37, 46, 142, 'test', './content/20190526_351caf0bf4648960741f117cb80de73f.php', '2019-05-26 07:54:04'),
(38, 46, 142, '<hthh', './content/20190612_9b89bcb9278efaab2da2d7df589a8456.jpg', '2019-06-12 10:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `enrol`
--

DROP TABLE IF EXISTS `enrol`;
CREATE TABLE IF NOT EXISTS `enrol` (
  `stu_id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `enrol_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`stu_id`,`sub_id`),
  KEY `sub_id` (`sub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enrol`
--

INSERT INTO `enrol` (`stu_id`, `sub_id`, `enrol_date`) VALUES
(151, 48, '2019-06-14 11:16:17'),
(150, 40, '2019-06-11 18:31:24'),
(149, 42, '2019-06-11 18:30:25'),
(150, 42, '2019-06-11 18:31:24'),
(150, 47, '2019-06-11 18:31:24'),
(148, 49, '2019-06-11 18:20:40'),
(148, 47, '2019-06-11 18:20:40'),
(138, 43, '2019-04-06 09:15:23'),
(138, 41, '2019-04-06 09:15:23'),
(138, 42, '2019-04-06 09:15:23'),
(148, 42, '2019-06-11 18:20:40'),
(147, 48, '2019-06-11 18:19:29'),
(147, 40, '2019-06-11 18:19:29'),
(147, 41, '2019-06-11 18:19:29'),
(137, 43, '2019-05-25 16:41:13'),
(137, 46, '2019-05-25 16:41:13'),
(149, 40, '2019-06-11 18:30:25'),
(149, 41, '2019-06-11 18:30:25'),
(144, 42, '2019-06-11 18:14:35'),
(144, 40, '2019-06-11 18:14:35'),
(151, 40, '2019-06-14 11:16:17'),
(151, 47, '2019-06-14 11:16:17'),
(144, 47, '2019-06-11 18:14:35'),
(151, 42, '2019-06-14 11:16:17'),
(153, 40, '2019-08-10 09:45:46'),
(151, 49, '2019-06-14 11:16:17'),
(153, 46, '2019-08-10 09:45:46'),
(153, 41, '2019-08-10 09:45:46'),
(156, 43, '2019-09-02 05:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `farmer`
--

DROP TABLE IF EXISTS `farmer`;
CREATE TABLE IF NOT EXISTS `farmer` (
  `tea_id` int(11) NOT NULL AUTO_INCREMENT,
  `tea_name` varchar(35) NOT NULL,
  `tea_surname` varchar(35) NOT NULL,
  `tea_email` varchar(100) NOT NULL,
  `tea_gender` varchar(1) NOT NULL,
  `tea_dob` date NOT NULL,
  `tea_id_num` varchar(13) NOT NULL,
  PRIMARY KEY (`tea_id`),
  UNIQUE KEY `tea_id` (`tea_id`),
  UNIQUE KEY `tea_email` (`tea_email`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `farmer`
--

INSERT INTO `farmer` (`tea_id`, `tea_name`, `tea_surname`, `tea_email`, `tea_gender`, `tea_dob`, `tea_id_num`) VALUES
(142, 'musa', 'khumalo', 'musak@gmail.com', 'M', '2019-04-22', '0601259876081'),
(122, 'quenten', 'ngobeni', 'ngobeniquenten@gmail.com', 'M', '2019-03-05', '0000000000000');

-- --------------------------------------------------------

--
-- Table structure for table `general_announcement`
--

DROP TABLE IF EXISTS `general_announcement`;
CREATE TABLE IF NOT EXISTS `general_announcement` (
  `gen_ann_id` int(11) NOT NULL AUTO_INCREMENT,
  `gen_ann_name` varchar(100) NOT NULL,
  `gen_ann_description` varchar(1000) NOT NULL,
  `gen_ann_date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gen_ann_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `grade_id` int(11) NOT NULL,
  `ass_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `grade_percentage` int(11) NOT NULL,
  `date_graded` date NOT NULL,
  PRIMARY KEY (`grade_id`),
  UNIQUE KEY `grade_id` (`grade_id`),
  KEY `ass_id` (`ass_id`),
  KEY `stu_id` (`stu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_email` varchar(100) NOT NULL,
  `member_password` varchar(1000) NOT NULL,
  `member_level` int(11) NOT NULL,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_id` (`member_id`),
  UNIQUE KEY `member_email` (`member_email`)
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_email`, `member_password`, `member_level`) VALUES
(144, 'testmail@test.test', '$2y$10$.cND2PcWABPuyCZYSwi6lOtGw16EJeP3lvILLOSODjw/u.uNSirM2', 1),
(148, 'hsdh2@fg.ckn', '$2y$10$Z73hy3H7t266baIWi5B22uxZtLbinPc/TmQi91H7KlcPNn1YCUx..', 1),
(122, 'ngobeniquenten@gmail.com', '$2y$10$XNJjo0q2u161WL1RAM3KNeOTPI.tQgRMZurupc9wu8156IoJb2SQm', 2),
(131, 'teacher@test.com', '$2y$10$scUp9mOyIlnbhR8eKeIkkuw16TlQa2ddTCwpIe77trqX.UodRpncy', 2),
(130, 'student@test.com', '$2y$10$scUp9mOyIlnbhR8eKeIkkuw16TlQa2ddTCwpIe77trqX.UodRpncy', 1),
(143, 'vuhlalingobeni@gmail.com', '$2y$10$W9gDFLZyFFMjPgslUeO.RO2.Z5FVYIOa75bSrVzc/FP8FXkibBuvG', 1),
(147, 'tabs@kuh.com', '$2y$10$XkmYOCzEC4whjJQjScOCX.GwcBYQftHZz60uJQQjoxhzvAL9awEl2', 1),
(132, 'admin@test.com', '$2y$10$scUp9mOyIlnbhR8eKeIkkuw16TlQa2ddTCwpIe77trqX.UodRpncy', 3),
(137, 'ngobeniq@gmail.com', '$2y$10$Q2cwY2krAeYulMdMwTkoWe8.yZx9XG2ntdSzLpWYkZVp116fR41K.', 1),
(138, 'qngosnh@vio.org', '$2y$10$ZZvYSSGJmHzs6qB34tFrBuVjytTmmYuD4qAbEfp1tYbjFd8oZ2P56', 1),
(142, 'musak@gmail.com', '$2y$10$PZ5Q3vvkVHCpbQs./ViX0ejqUM.2W2B/.oTCAxfztSSU1hixkiaRu', 2),
(149, 'jhj@vvh.com', '$2y$10$mYgDg5/HETibC88czIHGx.iOE.O69l/qVUyT5EEUnkJYI3NYQF4I6', 1),
(150, 'nghgg@bdsgd.com', '$2y$10$KEz5RU/yjajgFkFKz3VCauoGfeG/x0SfSlk/C7N8D3XZbrAuyTFZ6', 1),
(151, 'hgsdhs@nsbe.com', '$2y$10$H1qjzGMX1xshYijKUIij7um1xGqqQ6W0/wRRci7bFWKyblKePg5tK', 1),
(153, 'qqqqqqqqqqqq2@dd.com', '$2y$10$DGiCLJcOuGwTHlQCWpHTKu/XPQ8kaO3Frtbv.kRk9MOPE..SzBwzi', 1),
(156, 'mboropearl@gmail.com', '$2y$10$6t76Q5q1cD/CUlyQSBZ/ceG/t4xAn8plNNWddnlqAOpELxRo3nFm2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `question_des` varchar(500) NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `quiz_id`, `question_des`) VALUES
(1, 1, 'This is a test question and ypu dont have to answer it'),
(2, 1, 'This is a test question and ypu dont have to answer it'),
(3, 1, 'This is another question yet'),
(4, 1, 'This is another question yet'),
(5, 1, 'This is another question yet'),
(6, 1, 'another test nje'),
(7, 1, 'it kind of sucks hey'),
(8, 2, 'This is another test question'),
(9, 2, 'and one more question wont hurt or will it?'),
(10, 1, 'test number numbers'),
(11, 1, 'Test again and again'),
(12, 1, 'Test again and again'),
(13, 1, 'Test again and again'),
(14, 1, 'Test again and again'),
(15, 1, 'Test again and again'),
(16, 1, 'Test again and again'),
(17, 1, 'Test again and again'),
(18, 1, 'Test again and again'),
(19, 1, 'Test again and again'),
(20, 1, 'Test again and again'),
(21, 1, 'Test again and again'),
(22, 1, 'Test again and again'),
(23, 1, 'Test again and again'),
(24, 1, 'Test again and again'),
(25, 1, 'Test again and again'),
(26, 1, 'Test again and again'),
(27, 1, 'Test again and again'),
(28, 1, 'Test again and again'),
(29, 1, 'Test again and again'),
(30, 1, 'Test again and again'),
(31, 1, 'Test again and again'),
(32, 1, 'Test again and again'),
(33, 1, 'Test again and again'),
(34, 1, 'Test again and again'),
(35, 1, 'Test again and again'),
(36, 1, 'Test again and again'),
(37, 1, 'Test again and again'),
(38, 1, 'Test again and not again'),
(39, 2, 'This is a new quedtion'),
(40, 2, 'The blue elephant question'),
(42, 4, 'Who is God?'),
(43, 1, 'This is the last question'),
(44, 5, 'This is the second question now edited'),
(45, 5, 'This is the third question that has also been edited'),
(46, 5, 'This is the forth question'),
(47, 5, 'This is the fifth question'),
(48, 5, 'This is the sixth question'),
(49, 5, 'This is the seventh question'),
(50, 5, 'This is the eighth question'),
(51, 5, 'This is the ninth question'),
(52, 5, 'This is the tenth question'),
(56, 6, 'this is also an edited question'),
(57, 6, 'Q2'),
(59, 6, 'Q4'),
(60, 6, 'THIS IA AN EDITED QUESTION'),
(68, 7, 'Q2'),
(70, 7, 'this is also an edited question'),
(71, 7, 'THIS IA AN EDITED QUESTION'),
(72, 7, 'thids is abbbb'),
(73, 6, 'thids is abbbb'),
(74, 6, 'Q2'),
(75, 9, 'question 1'),
(76, 8, 'question 1'),
(77, 8, 'question 1'),
(78, 8, 'question 1'),
(79, 9, 'question 1'),
(80, 9, 'question 1');

-- --------------------------------------------------------

--
-- Table structure for table `question_choice`
--

DROP TABLE IF EXISTS `question_choice`;
CREATE TABLE IF NOT EXISTS `question_choice` (
  `choice_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `is_right_choice` int(11) NOT NULL,
  `choice` varchar(500) NOT NULL,
  PRIMARY KEY (`choice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_choice`
--

INSERT INTO `question_choice` (`choice_id`, `question_id`, `is_right_choice`, `choice`) VALUES
(42, 48, 1, 'False'),
(41, 47, 0, 'False'),
(39, 46, 0, 'False'),
(40, 47, 1, 'True'),
(38, 46, 1, 'True'),
(54, 56, 0, 'A'),
(36, 45, 1, 'True'),
(37, 45, 0, 'False'),
(53, 44, 1, 'True'),
(34, 44, 0, 'False'),
(33, 41, 1, 'True'),
(32, 41, 0, 'False'),
(31, 2, 0, 'Test choice q3'),
(30, 2, 1, 'Test choice q1'),
(28, 1, 0, 'test 1'),
(29, 1, 1, 'test 2'),
(43, 48, 0, 'True'),
(44, 49, 0, 'False'),
(45, 49, 1, 'True'),
(46, 50, 1, 'True'),
(47, 50, 0, 'False'),
(48, 51, 1, 'False'),
(49, 51, 0, 'True'),
(50, 52, 0, 'False'),
(51, 52, 1, 'True'),
(55, 56, 1, 'C'),
(56, 57, 0, 'NONE OF THE ABOVE'),
(57, 57, 1, 'ALL OF THE ABOVE'),
(58, 58, 0, 'TRUE'),
(59, 58, 1, 'FALSE'),
(60, 59, 1, 'AA'),
(61, 59, 0, 'BB'),
(62, 60, 0, '12'),
(63, 60, 1, '16'),
(64, 0, 0, 'A'),
(65, 0, 1, 'C'),
(66, 0, 0, 'NONE OF THE ABOVE'),
(67, 0, 1, 'ALL OF THE ABOVE'),
(68, 67, 0, 'NONE OF THE ABOVE'),
(69, 67, 1, 'ALL OF THE ABOVE'),
(70, 68, 0, 'NONE OF THE ABOVE'),
(71, 68, 1, 'ALL OF THE ABOVE'),
(72, 70, 0, 'A'),
(73, 70, 1, 'C'),
(74, 71, 0, '12'),
(75, 71, 1, '16'),
(76, 74, 0, 'NONE OF THE ABOVE'),
(77, 74, 1, 'ALL OF THE ABOVE');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `quiz_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_id` int(11) NOT NULL,
  `publish_date` date NOT NULL,
  `due_date` date NOT NULL,
  `quiz_title` varchar(100) NOT NULL,
  `quiz_description` varchar(350) NOT NULL,
  `status` int(11) NOT NULL,
  `n_of_questions` int(11) NOT NULL,
  PRIMARY KEY (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `sub_id`, `publish_date`, `due_date`, `quiz_title`, `quiz_description`, `status`, `n_of_questions`) VALUES
(8, 43, '2019-10-22', '2019-10-11', 'quiz 1', 'Hello quiz is here', 1, 2),
(9, 43, '2019-10-22', '2019-10-17', 'quiz 2', 'Hello quiz two is here.', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_mark`
--

DROP TABLE IF EXISTS `quiz_mark`;
CREATE TABLE IF NOT EXISTS `quiz_mark` (
  `mark_id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `stu_score` int(11) NOT NULL,
  PRIMARY KEY (`mark_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz_mark`
--

INSERT INTO `quiz_mark` (`mark_id`, `stu_id`, `quiz_id`, `stu_score`) VALUES
(2, 137, 6, 2),
(9, 156, 6, 1),
(10, 137, 8, 0),
(11, 137, 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `seed`
--

DROP TABLE IF EXISTS `seed`;
CREATE TABLE IF NOT EXISTS `seed` (
  `stu_id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_name` varchar(35) NOT NULL,
  `stu_surname` varchar(35) NOT NULL,
  `stu_email` varchar(100) NOT NULL,
  `stu_grade` int(11) NOT NULL,
  `stu_gender` varchar(1) NOT NULL,
  `stu_dob` date NOT NULL,
  `stu_id_num` varchar(13) NOT NULL,
  PRIMARY KEY (`stu_id`),
  UNIQUE KEY `stu_id` (`stu_id`),
  UNIQUE KEY `stu_email` (`stu_email`)
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `seed`
--

INSERT INTO `seed` (`stu_id`, `stu_name`, `stu_surname`, `stu_email`, `stu_grade`, `stu_gender`, `stu_dob`, `stu_id_num`) VALUES
(137, 'quenten', 'ngobeni', 'ngobeniq@gmail.com', 12, 'M', '1995-02-21', '9503315852081'),
(156, 'pheliwe', 'mboro', 'mboropearl@gmail.com', 12, 'F', '2019-09-11', '9610142220085');

-- --------------------------------------------------------

--
-- Table structure for table `sentmessages`
--

DROP TABLE IF EXISTS `sentmessages`;
CREATE TABLE IF NOT EXISTS `sentmessages` (
  `sm_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sentmessages`
--

INSERT INTO `sentmessages` (`sm_id`, `email`, `subject`, `message`, `date_sent`) VALUES
(1, 'mq@gmail.com', 'none', 'hi boy', '2019-03-17 22:00:00'),
(2, 'mq@gmail.com', 'none', 'hi boy', '2019-03-17 22:00:00'),
(3, 'mq@gmail.com', 'none', 'hi boy', '2019-03-17 22:00:00'),
(4, 'mmm@ggg.com', 'jjj', 'jjj', '2019-03-17 22:00:00'),
(5, 'kkjh@ff.com', 'tyttt', 'rt', '2019-03-17 22:00:00'),
(6, 'kkjh@ff.com', 'tyttt', 'rt', '2019-03-17 22:00:00'),
(7, 'kkjh@ff.com', 'tyttt', 'rt', '2019-03-17 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `subjectss`
--

DROP TABLE IF EXISTS `subjectss`;
CREATE TABLE IF NOT EXISTS `subjectss` (
  `sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_code` varchar(10) NOT NULL,
  `sub_name` varchar(100) NOT NULL,
  `sub_grade` int(11) NOT NULL,
  PRIMARY KEY (`sub_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjectss`
--

INSERT INTO `subjectss` (`sub_id`, `sub_code`, `sub_name`, `sub_grade`) VALUES
(44, 'engli9', 'english home language', 9),
(53, 'mathe8', 'mathematics', 8),
(46, 'accou12', 'accounting', 12),
(43, 'life 12', 'life sciences', 12),
(41, 'econo12', 'economics', 12),
(42, 'histo12', 'history', 12),
(36, 'econo9', 'economic theory', 9),
(37, 'histo9', 'history', 9),
(40, 'mathe12', 'mathematics', 12),
(47, 'busin12', 'business studies', 12),
(48, 'life 12', 'life orientation', 12),
(49, 'afrik12', 'afrikaans FAL', 12),
(50, 'engli12', 'english home language', 12),
(52, 'maths12', 'maths literacy', 12),
(54, 'engli10', 'english', 10),
(55, 'econo11', 'economics', 11),
(56, 'mathe8', 'mathematics', 8);

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
CREATE TABLE IF NOT EXISTS `timetable` (
  `time_id` int(11) NOT NULL AUTO_INCREMENT,
  `time_title` varchar(50) NOT NULL,
  `time_type` varchar(20) NOT NULL,
  `time_grade` int(11) NOT NULL,
  `time_path` varchar(500) NOT NULL,
  PRIMARY KEY (`time_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timetable`
--

INSERT INTO `timetable` (`time_id`, `time_title`, `time_type`, `time_grade`, `time_path`) VALUES
(1, 'Test Timetable', 'class', 12, './content/20190708_caff3345311df15c6426bd6fb3a2f7c8.jpg'),
(2, 'Exam timetable', 'exam', 12, './content/20190708_262d9b62bb2b49c4c366811ba5318bf4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_question_answer`
--

DROP TABLE IF EXISTS `user_question_answer`;
CREATE TABLE IF NOT EXISTS `user_question_answer` (
  `user_q_answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choice_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `date_answered` date NOT NULL,
  PRIMARY KEY (`user_q_answer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_question_answer`
--

INSERT INTO `user_question_answer` (`user_q_answer_id`, `user_id`, `question_id`, `choice_id`, `quiz_id`, `date_answered`) VALUES
(103, 156, 60, 62, 6, '2019-09-02'),
(102, 156, 56, 54, 6, '2019-09-02'),
(101, 156, 59, 60, 6, '2019-09-02'),
(85, 137, 59, 60, 6, '2019-09-01'),
(84, 137, 56, 54, 6, '2019-09-01'),
(83, 137, 60, 63, 6, '2019-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `visitormessages`
--

DROP TABLE IF EXISTS `visitormessages`;
CREATE TABLE IF NOT EXISTS `visitormessages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_name` varchar(100) NOT NULL,
  `sender_surname` varchar(100) NOT NULL,
  `sender_email` varchar(200) NOT NULL,
  `sender_message` varchar(1000) NOT NULL,
  `message_status` int(11) NOT NULL,
  `date_received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

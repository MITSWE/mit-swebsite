-- phpMyAdmin SQL Dump
-- version 4.0.10.17
-- https://www.phpmyadmin.net
--
-- Host: sql.mit.edu
-- Generation Time: Oct 17, 2016 at 12:51 PM
-- Server version: 5.1.72-2-log
-- PHP Version: 5.5.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `swe+board`
--

-- --------------------------------------------------------

--
-- Table structure for table `board`
--

CREATE TABLE IF NOT EXISTS `board` (
  `first` varchar(9) DEFAULT NULL,
  `last` varchar(10) DEFAULT NULL,
  `position` varchar(31) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `course` varchar(25) DEFAULT NULL,
  `file` varchar(21) DEFAULT NULL,
  `fact` varchar(131) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `campusrelations2016`
--

CREATE TABLE IF NOT EXISTS `campusrelations2016` (
  `first` varchar(9) DEFAULT NULL,
  `last` varchar(8) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `course` varchar(6) DEFAULT NULL,
  `file` varchar(21) DEFAULT NULL,
  `fact` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `campusrelations2016`
--

INSERT INTO `campusrelations2016` (`first`, `last`, `position`, `year`, `course`, `file`, `fact`) VALUES
('Amanda', 'Wu', 'Marketing and Media Coordinator', 2018, '9', 'AmandaWu.jpg', 'I love Hawaii and snorkeling!'),
('Chaaru', 'Deb', 'Marketing and Media Coordinator', 2018, '20', 'ChaaruDeb.jpg', 'I like to do pottery and have a kiln (a clay oven) at my house!'),
('Liz', 'Cox', 'Campus Representative', 2018, '6-3', 'LizCox.jpg', 'Mild salsa is too hot for me.'),
('Jae Hyun', 'Kim', 'Department Liaison', 2018, '3', 'JaeHyunKim.jpg', 'My left foot is noticeably larger than my right!'),
('Kerrie', 'Greene', 'Campus Representative', 2019, '10B', 'KerrieGreene.jpg', 'I have played the cello since the 3rd grade!'),
('Morgan', 'Matranga', 'Department Liaison', 2019, '10B', 'MorganMatranga.jpg', 'My fun fact is that I graduated from elementary school twice!'),
('Anya', 'Quenon', 'Campus Relations Freshman Representative', 2020, '2', 'AnyaQuenon.jpg', 'I love penny boarding, ice skating, snow boarding, and roller blading'),
('Carol', 'Wu', 'Campus Freshman Representative', 2020, '6-3/15', 'CarolWu.jpg', 'I love singing Broadway show tunes');

-- --------------------------------------------------------

--
-- Table structure for table `corporaterelations2016`
--

CREATE TABLE IF NOT EXISTS `corporaterelations2016` (
  `first` varchar(15) DEFAULT NULL,
  `last` varchar(9) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `course` varchar(9) DEFAULT NULL,
  `file` varchar(30) DEFAULT NULL,
  `fact` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `corporaterelations2016`
--

INSERT INTO `corporaterelations2016` (`first`, `last`, `position`, `year`, `course`, `file`, `fact`) VALUES
('Alisha', 'Saxena', 'Career Development Chair', 2018, '6-2', 'AlishaS.jpg', 'I have a Black Belt in Tae Kwon Do!'),
('Amy', 'Huang', 'Career Development Chair', 2018, '18, 15', 'AmyHuang.jpg', 'I am an avid boba fanatic!'),
('Katherine', 'Wang', 'Career Development Chair', 2019, '18C, 15', 'KatherineWang.jpg', 'I''ve made an NCAA Basketball Bracket every year since I was 8. I''m still the undefeated champ of my family.'),
('Siena', 'Scigliuto', 'Career Development Chair', 2018, '2', 'SienaScigliuto.jpg', 'I drag race, and I have been racing since I was 8 years old.'),
('Stephanie', 'Chou', 'Career Development Chair', 2019, '6-3', 'StephanieChou.jpg', 'I love eating sweets.'),
('Kate', 'Yuan', 'Corporate Relations Freshman Representative', 2020, '18C/15', 'KateYuan.jpg', 'Favorite Food: Avocados with Soy Sauce');

-- --------------------------------------------------------

--
-- Table structure for table `exec2016`
--

CREATE TABLE IF NOT EXISTS `exec2016` (
  `first` varchar(10) DEFAULT NULL,
  `last` varchar(12) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `course` varchar(7) DEFAULT NULL,
  `file` varchar(19) DEFAULT NULL,
  `fact` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `exec2016`
--

INSERT INTO `exec2016` (`first`, `last`, `position`, `year`, `course`, `file`, `fact`) VALUES
('Lynn', 'Takeshita', 'President', 2017, '18C, 15', 'LynnTakeshita.jpg', 'I''m a certified scuba diver!'),
('Erin', 'Hong', 'Administrative Officer', 2017, '6-3', 'ErinHong.jpeg', 'I enjoy running half marathons.'),
('Michelle', 'Tai', 'Financial Officer', 2017, '10B, 7A', 'MichelleTai.jpg', 'My favorite board member''s name rhymes with baguette ;)'),
('Lisette', 'Tellez', 'Technology Officer', 2018, '6-3', 'LisetteTellez.jpg', 'Four-time high school food eating competition champ.'),
('Christiane', 'Adcock', 'VP Corporate Relations', 2018, '2', 'ChristianeA.jpg', 'I have a German catamaran sailing license.'),
('Marisa', 'Rozzi', 'VP Membership', 2018, '6-2', 'MarisaRozzi.jpg', 'I can sing my ABC''s backwards in under 5 seconds.'),
('Joanna', 'Han', 'VP Campus Relations', 2018, '6-2', 'JoannaHan.jpg', 'I used to be a lefty.'),
('Teresa', 'deFigueiredo', 'VP Outreach', 2017, '3', 'TeresaD.jpg', 'I enjoy hammocking.'),
('Emma', 'Castanos', 'VP Outreach', 2017, '2', 'EmmaCastanos.jpg', 'Over Winter Break I saw dinosaur footprints in Bolivia!');

-- --------------------------------------------------------

--
-- Table structure for table `memberdevelopment2016`
--

CREATE TABLE IF NOT EXISTS `memberdevelopment2016` (
  `first` varchar(15) DEFAULT NULL,
  `last` varchar(15) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `year` int(4) DEFAULT NULL,
  `course` varchar(9) DEFAULT NULL,
  `file` varchar(30) DEFAULT NULL,
  `fact` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `memberdevelopment2016`
--

INSERT INTO `memberdevelopment2016` (`first`, `last`, `position`, `year`, `course`, `file`, `fact`) VALUES
('Geneva', 'Werner', 'Membership Development Chair', 2019, '10B', 'GenevaWerner.jpg', 'I''ve been stuck in the St. Louis arch twice!'),
('Hannah', 'Huynh', 'Internal Mentorship Chair', 2017, '2', 'HannahHuynh.JPG', 'I''m really into crafting and have 40 rolls of wash tape (colored and patterned tape) in my dorm room!'),
('Jessica', 'Chen', 'Internal Mentorship Chair', 2018, '2A-20', 'JessicaChen.jpg', 'It took me 2 hours to come up with this fun fact.'),
('Michele', 'Miao', 'Membership Development Chair', 2019, '6-7', 'MicheleMiao.jpg', 'My body is 60% Arizona Iced Tea.'),
('Lena', 'Zhu', 'Member Development Freshman Representative', 2020, '10B', 'LenaZhu.jpg', 'I was born in the UK.'),
('Christine', 'Soh', 'Internal Relations Freshman Representative', 2020, '6-2/6-3', 'ChristineSoh.jpg', 'I am a Suzuki-certified violin teacher!');

-- --------------------------------------------------------

--
-- Table structure for table `outreach2016`
--

CREATE TABLE IF NOT EXISTS `outreach2016` (
  `first` varchar(20) DEFAULT NULL,
  `last` varchar(20) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `course` varchar(50) DEFAULT NULL,
  `file` varchar(20) DEFAULT NULL,
  `fact` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `outreach2016`
--

INSERT INTO `outreach2016` (`first`, `last`, `position`, `year`, `course`, `file`, `fact`) VALUES
('Allison', 'Nguyen', 'High School Outreach Chair', 2019, '2', 'AllisonNguyen.jpg', 'I used to hate cats, but after spending a month with one, I love them!'),
('Ana Maria', 'Vargas', 'High School Outreach Chair', 2019, '2A-6', 'AnaMariaVargas.jpg', 'My favorite hobby is custom-building PCs (when I can spare the time and money).'),
('Avira', 'Som', 'WiSE Chair', 2017, '10', 'AviraSom.jpg', 'I can''t live without Tabasco sauce :).'),
('Celine', 'Qiu', 'KEYS Chair', 2019, '6-2', 'CelineQiu.jpg', 'I own eight Christmas sweaters.'),
('Ellen', 'O''Connell', 'Girl Scouts Outreach Chair', 2019, '2A, 14', 'EllenOConnell.jpg', 'I love SWE!'),
('Hannah', 'Thel', 'KEYS Chair', 2019, '6-7', 'HannahThel.jpg', 'I can lick my elbow!'),
('Jeanie', 'Pearson', 'Middle School Outreach Chair', 2018, '6-3', 'JeaniePearson.jpg', 'I love to dance - I''m in MIT''s DanceTroupe.'),
('Jennifer', 'McCleary', 'Off-Campus Education Chair', 2019, '6-3', 'JenniferMcCleary.jpg', 'I''m on MIT''s Asian Dance Team.'),
('Jenny', 'Xu', 'Events Outreach Coordinator', 2019, '6-3', 'JennyXu.jpg', 'Broke into Chinese Teacher''s backyard in high school.'),
('Joy', 'Yu', 'Off-Campus Education Chair', 2018, '6-3', 'JoyYu.jpg', 'I have a nephew and niece!'),
('Margaret', 'Bertoni', 'Events Outreach Coordinator', 2019, '2A', 'MargaretBertoni.jpg', 'I''ve been to half of the habitable continents.'),
('Monica', 'Pham', 'Festival Chair', 2019, '22', 'MonicaPham.jpg', 'Go Big Red!!'),
('Rebecca', 'Gallivan', 'KEYS Chair', 2017, '3', 'RebeccaGallivan.jpg', 'I have ridden a camel!'),
('Rebecca', 'Grekin', 'Girl Scouts Outreach Chair', 2019, '10', 'RebeccaGrekin.jpg', 'In 2015 I went to 12 countries in a span of 2.5 months!'),
('Reva', 'Ranka', 'Off-Campus Education Chair', 2019, '6-3', 'RevaRanka.jpg', 'I love SWE!'),
('Shivani', 'Chauhan', 'High School Outreach Chair', 2018, '6-3, 7A', 'ShivaniChauhan.jpg', 'I love to travel, swim and bake cheesecakes!'),
('Victoria', 'Dean', 'Middle School Outreach Chair', 2017, '6-3', 'VictoriaDean.jpg', 'In 6th grade, I was bit by a bullet ant in the Costa Rican rainforest.'),
('Kelsey', 'Wong', 'CodeIt Chair', 2018, '6-3', 'KelseyWong.jpg', 'I''ve met One Direction!'),
('Yanisa', 'Techagumthorn', 'Festival Chair', 2018, '3, 11', 'YanisaT.jpg', 'My legs can bend back unusually far.'),
('Zareen', 'Choudhury', 'Wise Chair', 2018, '6-2', 'ZareenChoudhury.jpg', 'I ran into George Lopez outside Kresge on Halloween!'),
('Megan', 'Yamoah', 'Events Outreach Freshman Representative', 2020, '8/6-1', 'MeganYamoah.jpg', 'Loves to dance and eat raspberries and chocolate.'),
('Kiera', 'Gavin', 'Outreach Freshman Representative', 2020, '2 or 10', 'KeiraGavin.jpeg', 'I have 26 first cousins!'),
('Prachi', 'Sinha', 'Outreach Freshman Representative', 2020, '6-7', '', ''),
('Sara', 'Wilson', 'Outreach KEYs Freshman Representative', 2020, '3', 'default.jpg', 'Before falling in love with chemistry, which is the reason I now aspire to be an engineer, I wanted to be a lawyer!'),
('Katherine', 'Yang', 'Outreach Festival Freshman Representative', 2020, '18C', 'KatherineYang.png', 'I''ve donated a total of 34 inches of hair.'),
('Rebecca', 'Gallivan', 'KEYs Co-Chair', 2017, '3', 'RebeccaGallivan.JPG', 'I grew up next to an alpaca farm ');

-- --------------------------------------------------------

--
-- Table structure for table `technology2016`
--

CREATE TABLE IF NOT EXISTS `technology2016` (
  `first` varchar(11) NOT NULL,
  `last` varchar(20) NOT NULL,
  `position` varchar(40) NOT NULL,
  `year` varchar(4) NOT NULL,
  `course` varchar(10) NOT NULL,
  `file` varchar(30) NOT NULL,
  `fact` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `technology2016`
--

INSERT INTO `technology2016` (`first`, `last`, `position`, `year`, `course`, `file`, `fact`) VALUES
('Baula', 'Xu', 'Technology Chair', '2019', '6', 'BaulaXu.jpg', 'I can''t walk and drink water at the same time!!!'),
('Nancy', 'Hung', 'Technology Chair', '2019', '6-2', 'NancyHung.jpg', 'I steal chip bags and candy wrappers from my friends and recycle them.'),
('Abby', 'Bertics', 'Technology Chair', '2019', '6-3', 'AbbyBertics.png', 'My height is the same as my major (6-3)!'),
('Francisca', 'Vasconcelos', 'Technology Freshman Representative', '2020', '6-2', 'FranciscaVasconcelos.jpg', 'Portuguese-American Dual Citizen');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

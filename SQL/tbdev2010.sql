-- phpMyAdmin SQL Dump
-- version 4.2.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2014 at 11:16 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tbdev2010`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachmentdownloads`
--

CREATE TABLE IF NOT EXISTS `attachmentdownloads` (
`id` int(10) unsigned NOT NULL,
  `fileid` int(10) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userid` int(10) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `downloads` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
`id` int(10) unsigned NOT NULL,
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `postid` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `downloads` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `avps`
--

CREATE TABLE IF NOT EXISTS `avps` (
  `arg` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value_s` text COLLATE utf8_unicode_ci NOT NULL,
  `value_i` int(11) NOT NULL DEFAULT '0',
  `value_u` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `avps`
--

INSERT INTO `avps` (`arg`, `value_s`, `value_i`, `value_u`) VALUES
('lastcleantime', '', 0, 1293321999),
('seeders', '', 0, 0),
('leechers', '', 0, 0),
('loadlimit', '12.5-1246045258', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE IF NOT EXISTS `bans` (
`id` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `addedby` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first` int(11) DEFAULT NULL,
  `last` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
`id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `blockid` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cat_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No Description'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `cat_desc`) VALUES
(1, 'Appz/PC ISO', 'cat_apps.gif', 'No Description'),
(2, 'Games/PC ISO', 'cat_games.gif', 'No Description'),
(3, 'Movies/SVCD', 'cat_movies.gif', 'No Description'),
(4, 'Music', 'cat_music.gif', 'No Description'),
(5, 'Episodes', 'cat_episodes.gif', 'No Description'),
(6, 'XXX', 'cat_xxx.gif', 'No Description'),
(7, 'Games/GBA', 'cat_games.gif', 'No Description'),
(8, 'Games/PS2', 'cat_games.gif', 'No Description'),
(9, 'Anime', 'cat_anime.gif', 'No Description'),
(10, 'Movies/XviD', 'cat_movies.gif', 'No Description'),
(11, 'Movies/DVD-R', 'cat_movies.gif', 'No Description'),
(12, 'Games/PC Rips', 'cat_games.gif', 'No Description'),
(13, 'Appz/misc', 'cat_apps.gif', 'No Description');

-- --------------------------------------------------------

--
-- Table structure for table `cleanup`
--

CREATE TABLE IF NOT EXISTS `cleanup` (
`clean_id` int(10) NOT NULL,
  `clean_title` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clean_file` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clean_time` int(11) NOT NULL DEFAULT '0',
  `clean_increment` int(11) NOT NULL DEFAULT '0',
  `clean_cron_key` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clean_log` tinyint(1) NOT NULL DEFAULT '0',
  `clean_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `clean_on` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `cleanup`
--

INSERT INTO `cleanup` (`clean_id`, `clean_title`, `clean_file`, `clean_time`, `clean_increment`, `clean_cron_key`, `clean_log`, `clean_desc`, `clean_on`) VALUES
(1, 'Normalize Torrents', 'normalize_torrents.php', 1294437635, 50, 'd6704d582b136ea1ed13635bb9059f57', 1, 'bla blah blah', 0),
(2, 'Frontpage Stats Build', 'frontpage_stats.php', 1403773928, 10, '6272317b90846180504dcf7c28902cb2', 0, 'Thsi rebuilds the stats on index page or wherever else you decide to use them!', 1),
(3, 'Delete Old Torrents', 'delete_old_torrents.php', 0, 86400, '560f666531f0b409a577236a5ec571dc', 0, 'Deletes torrents older than 28 days', 0),
(4, 'Expire Old Peers', 'expire_peers.php', 1403774536, 900, '3a784b3948266707895dfbc31e19c6b0', 1, 'Cleans out old peers from the peers table based on &#39;clean interva&#39; x 1.3', 1),
(5, 'Expire Readposts', 'expire_readposts.php', 0, 86400, 'c254efb9042f1d1a32d123b8e50d9012', 0, 'Cleans all readposts from topics/posts etc', 0),
(6, 'Expire Old User Accounts', 'expire_user_accounts.php', 0, 86400, '2b2786a667e6234e107c4873cb5126a9', 0, 'Expires old accounts every so often.', 0),
(7, 'Updates Forum & Topic Counts', 'forum_topic_stats.php', 1403774528, 900, '08eefec1e363ab5309589cb8f3214a69', 0, 'Updates forum and topic counts etc.', 1),
(8, 'Expires Old Inactive User Accounts', 'incative_user_accounts.php', 0, 86400, 'd4654a8a66e26bbbaff130eab0d5f3d6', 0, 'Deletes old inactive accounts older than 42 days', 0),
(9, 'Make Dead Torrents Invisible', 'invisibilize_torrents.php', 0, 86400, 'b5514005c7e4735e1f636ba59bd47cc8', 0, 'Hides dead torrents from the browse list but does not delete them.', 0),
(10, 'AutoOptimize MySQL Database', 'mysql_optimise_clean.php', 0, 2592000, '7d00f70b99ed8c0ee8056c03fd5e6c75', 0, 'Optimize all mysql tables with overhead.', 0),
(11, 'Kill MySQL Processes', 'mysql_process_kill.php', 0, 86400, 'a03a77aceadd17109542effae0d6f41a', 0, 'Kills all mysql processes over 60 seconds', 0),
(12, 'Normalizes Old Torrents', 'normalize_torrents.php', 0, 900, '575513659ee6a52a358630c943518c52', 0, 'Deletes torrents not in the DB and deletes torrent ID''s not in the torrents directory.', 0),
(13, 'Updates Torrent Comments', 'toorent_comment_update.php', 1403773859, 180, 'c273ee4cb03c85b3fa14f1e8e70c0f71', 1, 'Updates torrent comment counts etc etc.', 1),
(14, 'Promote & Demote Users', 'update_power_users.php', 0, 86400, '36706f9087d357c3bab312d316462375', 0, 'Promotes users to power users and demotes users to erm, users!', 0),
(15, 'Update User Warnings', 'update_user_warnings.php', 0, 17800, '9f6dec80c3870d5fdd5e1e5e48d57c33', 0, 'Removes and updates old warnings, and also instigates new warnings etc etc.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cleanup_log`
--

CREATE TABLE IF NOT EXISTS `cleanup_log` (
`clog_id` int(10) NOT NULL,
  `clog_event` char(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `clog_time` int(11) NOT NULL DEFAULT '0',
  `clog_ip` char(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `clog_desc` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cleanup_log`
--

INSERT INTO `cleanup_log` (`clog_id`, `clog_event`, `clog_time`, `clog_ip`, `clog_desc`) VALUES
(1, 'Updates Torrent Comments', 1403772937, '::1', '0 items deleted'),
(2, 'Frontpage Stats Build', 1403773278, '::1', 'Thsi rebuilds the stats on index page or wherever else you decide to use them!'),
(3, 'Expire Old Peers', 1403773636, '::1', '0 items deleted'),
(4, 'Updates Torrent Comments', 1403773679, '::1', '0 items deleted'),
(5, 'Frontpage Stats Build', 1403773692, '::1', 'Thsi rebuilds the stats on index page or wherever else you decide to use them!'),
(6, 'Frontpage Stats Build', 1403773705, '::1', 'Thsi rebuilds the stats on index page or wherever else you decide to use them!'),
(7, 'Frontpage Stats Build', 1403773918, '::1', 'Thsi rebuilds the stats on index page or wherever else you decide to use them!');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL DEFAULT '0',
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `ori_text` text COLLATE utf8_unicode_ci NOT NULL,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flagpic` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=101 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `flagpic`) VALUES
(1, 'Sweden', 'sweden.gif'),
(2, 'United States of America', 'usa.gif'),
(3, 'Russia', 'russia.gif'),
(4, 'Finland', 'finland.gif'),
(5, 'Canada', 'canada.gif'),
(6, 'France', 'france.gif'),
(7, 'Germany', 'germany.gif'),
(8, 'China', 'china.gif'),
(9, 'Italy', 'italy.gif'),
(10, 'Denmark', 'denmark.gif'),
(11, 'Norway', 'norway.gif'),
(12, 'United Kingdom', 'uk.gif'),
(13, 'Ireland', 'ireland.gif'),
(14, 'Poland', 'poland.gif'),
(15, 'Netherlands', 'netherlands.gif'),
(16, 'Belgium', 'belgium.gif'),
(17, 'Japan', 'japan.gif'),
(18, 'Brazil', 'brazil.gif'),
(19, 'Argentina', 'argentina.gif'),
(20, 'Australia', 'australia.gif'),
(21, 'New Zealand', 'newzealand.gif'),
(22, 'Spain', 'spain.gif'),
(23, 'Portugal', 'portugal.gif'),
(24, 'Mexico', 'mexico.gif'),
(25, 'Singapore', 'singapore.gif'),
(67, 'India', 'india.gif'),
(62, 'Albania', 'albania.gif'),
(26, 'South Africa', 'southafrica.gif'),
(27, 'South Korea', 'southkorea.gif'),
(28, 'Jamaica', 'jamaica.gif'),
(29, 'Luxembourg', 'luxembourg.gif'),
(30, 'Hong Kong', 'hongkong.gif'),
(31, 'Belize', 'belize.gif'),
(32, 'Algeria', 'algeria.gif'),
(33, 'Angola', 'angola.gif'),
(34, 'Austria', 'austria.gif'),
(35, 'Yugoslavia', 'yugoslavia.gif'),
(36, 'Western Samoa', 'westernsamoa.gif'),
(37, 'Malaysia', 'malaysia.gif'),
(38, 'Dominican Republic', 'dominicanrep.gif'),
(39, 'Greece', 'greece.gif'),
(40, 'Guatemala', 'guatemala.gif'),
(41, 'Israel', 'israel.gif'),
(42, 'Pakistan', 'pakistan.gif'),
(43, 'Czech Republic', 'czechrep.gif'),
(44, 'Serbia', 'serbia.gif'),
(45, 'Seychelles', 'seychelles.gif'),
(46, 'Taiwan', 'taiwan.gif'),
(47, 'Puerto Rico', 'puertorico.gif'),
(48, 'Chile', 'chile.gif'),
(49, 'Cuba', 'cuba.gif'),
(50, 'Congo', 'congo.gif'),
(51, 'Afghanistan', 'afghanistan.gif'),
(52, 'Turkey', 'turkey.gif'),
(53, 'Uzbekistan', 'uzbekistan.gif'),
(54, 'Switzerland', 'switzerland.gif'),
(55, 'Kiribati', 'kiribati.gif'),
(56, 'Philippines', 'philippines.gif'),
(57, 'Burkina Faso', 'burkinafaso.gif'),
(58, 'Nigeria', 'nigeria.gif'),
(59, 'Iceland', 'iceland.gif'),
(60, 'Nauru', 'nauru.gif'),
(61, 'Slovenia', 'slovenia.gif'),
(63, 'Turkmenistan', 'turkmenistan.gif'),
(64, 'Bosnia Herzegovina', 'bosniaherzegovina.gif'),
(65, 'Andorra', 'andorra.gif'),
(66, 'Lithuania', 'lithuania.gif'),
(68, 'Netherlands Antilles', 'nethantilles.gif'),
(69, 'Ukraine', 'ukraine.gif'),
(70, 'Venezuela', 'venezuela.gif'),
(71, 'Hungary', 'hungary.gif'),
(72, 'Romania', 'romania.gif'),
(73, 'Vanuatu', 'vanuatu.gif'),
(74, 'Vietnam', 'vietnam.gif'),
(75, 'Trinidad & Tobago', 'trinidadandtobago.gif'),
(76, 'Honduras', 'honduras.gif'),
(77, 'Kyrgyzstan', 'kyrgyzstan.gif'),
(78, 'Ecuador', 'ecuador.gif'),
(79, 'Bahamas', 'bahamas.gif'),
(80, 'Peru', 'peru.gif'),
(81, 'Cambodia', 'cambodia.gif'),
(82, 'Barbados', 'barbados.gif'),
(83, 'Bangladesh', 'bangladesh.gif'),
(84, 'Laos', 'laos.gif'),
(85, 'Uruguay', 'uruguay.gif'),
(86, 'Antigua Barbuda', 'antiguabarbuda.gif'),
(87, 'Paraguay', 'paraguay.gif'),
(89, 'Thailand', 'thailand.gif'),
(88, 'Union of Soviet Socialist Republics', 'ussr.gif'),
(90, 'Senegal', 'senegal.gif'),
(91, 'Togo', 'togo.gif'),
(92, 'North Korea', 'northkorea.gif'),
(93, 'Croatia', 'croatia.gif'),
(94, 'Estonia', 'estonia.gif'),
(95, 'Colombia', 'colombia.gif'),
(96, 'Lebanon', 'lebanon.gif'),
(97, 'Latvia', 'latvia.gif'),
(98, 'Costa Rica', 'costarica.gif'),
(99, 'Egypt', 'egypt.gif'),
(100, 'Bulgaria', 'bulgaria.gif');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
`id` int(10) unsigned NOT NULL,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forid` tinyint(4) DEFAULT '0',
  `postcount` int(10) unsigned NOT NULL DEFAULT '0',
  `topiccount` int(10) unsigned NOT NULL DEFAULT '0',
  `minclassread` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `minclasswrite` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `minclasscreate` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `place` int(10) NOT NULL DEFAULT '-1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_mods`
--

CREATE TABLE IF NOT EXISTS `forum_mods` (
`id` int(10) NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `fid` int(10) NOT NULL DEFAULT '0',
  `user` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_parents`
--

CREATE TABLE IF NOT EXISTS `forum_parents` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `minclassview` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `forid` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_polls`
--

CREATE TABLE IF NOT EXISTS `forum_polls` (
`id` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL DEFAULT '0',
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `option0` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option1` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option2` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option3` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option4` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option5` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option6` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option7` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option8` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option9` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option10` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option11` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option12` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option13` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option14` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option15` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option16` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option17` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option18` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `option19` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sort` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_poll_answers`
--

CREATE TABLE IF NOT EXISTS `forum_poll_answers` (
`id` int(10) unsigned NOT NULL,
  `pollid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `selection` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_subs`
--

CREATE TABLE IF NOT EXISTS `forum_subs` (
`id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `topicid` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
`id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `friendid` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
`id` int(10) unsigned NOT NULL,
  `sender` int(10) unsigned NOT NULL DEFAULT '0',
  `receiver` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `subject` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No Subject',
  `msg` text COLLATE utf8_unicode_ci,
  `unread` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `poster` bigint(20) unsigned NOT NULL DEFAULT '0',
  `location` smallint(6) NOT NULL DEFAULT '1',
  `saved` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`id` int(10) unsigned NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `headline` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'TBDEV.NET News'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `peers`
--

CREATE TABLE IF NOT EXISTS `peers` (
`id` int(10) unsigned NOT NULL,
  `torrent` int(10) unsigned NOT NULL DEFAULT '0',
  `info_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `passkey` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `peer_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `compact` varchar(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ip` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `to_go` bigint(20) unsigned NOT NULL DEFAULT '0',
  `seeder` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `started` int(11) NOT NULL,
  `last_action` int(11) NOT NULL,
  `connectable` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `agent` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `finishedat` int(10) unsigned NOT NULL DEFAULT '0',
  `downloadoffset` bigint(20) unsigned NOT NULL DEFAULT '0',
  `uploadoffset` bigint(20) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pmboxes`
--

CREATE TABLE IF NOT EXISTS `pmboxes` (
`id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `boxnumber` tinyint(4) NOT NULL DEFAULT '2',
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
`id` int(10) unsigned NOT NULL,
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `added` int(11) DEFAULT '0',
  `body` mediumtext COLLATE utf8_unicode_ci,
  `parsed_body` mediumtext COLLATE utf8_unicode_ci,
  `editedby` int(10) unsigned NOT NULL DEFAULT '0',
  `editedat` int(11) DEFAULT '0',
  `post_history` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `posticon` int(2) NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `readposts`
--

CREATE TABLE IF NOT EXISTS `readposts` (
`id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `topicid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpostread` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reputation`
--

CREATE TABLE IF NOT EXISTS `reputation` (
`reputationid` int(11) unsigned NOT NULL,
  `reputation` int(10) NOT NULL DEFAULT '0',
  `whoadded` int(10) NOT NULL DEFAULT '0',
  `reason` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dateadd` int(10) NOT NULL DEFAULT '0',
  `postid` int(10) NOT NULL DEFAULT '0',
  `userid` mediumint(8) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reputationlevel`
--

CREATE TABLE IF NOT EXISTS `reputationlevel` (
`reputationlevelid` int(11) unsigned NOT NULL,
  `minimumreputation` int(10) NOT NULL DEFAULT '0',
  `level` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `reputationlevel`
--

INSERT INTO `reputationlevel` (`reputationlevelid`, `minimumreputation`, `level`) VALUES
(1, -999999, 'is infamous around these parts'),
(2, -50, 'can only hope to improve'),
(3, -10, 'has a little shameless behaviour in the past'),
(4, 0, 'is an unknown quantity at this point'),
(5, 10, 'is on a distinguished road'),
(6, 50, 'will become famous soon enough'),
(7, 150, 'has a spectacular aura about'),
(8, 250, 'is a jewel in the rough'),
(9, 350, 'is just really nice'),
(10, 450, 'is a glorious beacon of light'),
(11, 550, 'is a name known to all'),
(12, 650, 'is a splendid one to behold'),
(13, 1000, 'has much to be proud of'),
(14, 1500, 'has a brilliant future'),
(15, 2000, 'has a reputation beyond repute');

-- --------------------------------------------------------

--
-- Table structure for table `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
`id` int(11) NOT NULL,
  `cid` int(3) unsigned NOT NULL DEFAULT '0',
  `heading` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `ctime` int(11) unsigned NOT NULL DEFAULT '0',
  `mtime` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `rules`
--

INSERT INTO `rules` (`id`, `cid`, `heading`, `body`, `ctime`, `mtime`) VALUES
(1, 1, ':: ::General rules - ''Breaking these rules can and ...', ' FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH\r\n \r\nFILTER_FLAG_ALLOW_FRACTION, FILTER_FLAG_ALLOW_THOUSAND, FILTER_FLAG_ALLOW_SCIENTIFIC\r\n \r\nFILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_HIGH\r\nFILTER_FLAG_NO_ENCODE_QUOTES, FILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP\r\n \r\n \r\nFILTER_FLAG_STRIP_LOW, FILTER_FLAG_STRIP_HIGH, FILTER_FLAG_ENCODE_LOW, FILTER_FLAG_ENCODE_HIGH, FILTER_FLAG_ENCODE_AMP ', 1214338879, 1293054699),
(6, 14, 'What''s this for?', 'We''ve noticed that you''ve been inactive for over 10 minute(s).\r\nWe''ve stopped running the Shoutbox due to your inactivity.\r\nIf you are back again, please click the I''m Back button below. ', 1293321576, 0),
(2, 1, ':: ::Forum Rules blah', ' # Please, feel free to answer any questions but leave the moderating to the moderators.\r\n     # Don''t use all capital letters, excessive !!! (exclamation marks) or ??? (question marks)... it seems like you''re shouting.\r\n     # No posting of users stats without their consent is allowed in the forums or torrent comments regardless of ratio or class.  \r\n     # No trashing of other peoples topics.\r\n     # No systematic foul language (and none at all on titles).\r\n     # No double posting. If you wish to post again, and yours is the last post in the thread please use the EDIT function, instead of posting a double.    \r\n     # No bumping... (All bumped threads will be Locked.)  \r\n     # No direct links to internet sites in the forums.      \r\n     # No images larger than 400x400, and preferably web-optimised. Use the [imgw] tag for larger images.\r\n     # No advertising, mechandising or promotions of any sort are allowed on the site.    \r\n     # Do not tell people to read the Rules, the FAQ, or comment on their ratios and torrents.    \r\n     # No consistent off-topic posts allowed in the forums. (i.e. SPAM or hijacking)  \r\n     # The Trading/Requesting of invites to other sites is forbidden in the forums.  \r\n     # Do not post links to other torrent sites or torrents on those sites.    \r\n     # Users are not allowed, under any circumstance to create their own polls in the forum.    \r\n     # No self-congratulatory topics are allowed in the forums.    \r\n     # Do not quote excessively. One quote of a quote box is sufficient.    \r\n     # Please ensure all questions are posted in the correct section!     (Game questions in the Games section, Apps questions in the Apps section, etc.)    \r\n     # Please, feel free to answer any questions.. However remain respectful to the people you help ....nobodys better than anyone else.    \r\n     # Last, please read the FAQ before asking any question', 1214339023, 1293054699),
(3, 3, ':: ::Uploaders Rules', 'All uploaders are subject to follow the below rules in order to be a part of the  uploader team. We realize that it''s quite a list, and for new uploaders, it might seem a bit overwhelming, but as you spend time here, they''ll become second hat.\r\n\r\nTo apply to become a site uploader use the uploaders application form, contact staff to get the link.\r\n\r\nTorrents that do not follow the rules below will be deleted.  If you have any questions about the below rules, please feel free to PM them and I will clarify as best I can.\r\n\r\nWelcome to the team and happy uploading!\r\n\r\n# All Uploaders must upload a minimum of 3 unique torrents each week to retain their Uploader status.  Failure to comply will result in a demotion, and a minimum of a 2 week blackout period where they will not be able to return to the Uploader team.  If, after the 2 weeks, the Uploader can prove they will be active, they will be reinstated.  A second instance of inactivity will be cause for permanent removal from the Uploader team.  Extenuating circumstances will be considered if it is the cause of inactivity.  If you are going to be away, please let a staff member know so that your account is not affected.\r\n# All torrents must be rarred, no matter what the size or type.  The ONLY exception to this is MP3s.  Guidelines for rarring your own releases are as follows:\r\n', 1214339203, 1293048490),
(4, 4, ':: ::Free leech rules', '      From time to time we will have freeleech for 48hours. This means that when you download from site it will not count against your download ratio.\r\n\r\n      Whatever you seed back will add to your upload ratio.\r\n\r\nThis is a good opportunity for members with ratio''s below 1.0 to bring them back into line\r\n\r\nAnyone who hit and runs on a freeleech torrent will receive a mandatory 2 week warning. You must seed all torrents downloaded to  100% or for a minimum of 48 hours this is for free leech torrents only.\r\n\r\n', 1214339464, 1293048590),
(5, 5, ':: ::Downloading rules', 'No comments on torrents you are not about to download\r\nOnce download is complete, remember to seed for as long as possible or for a minimum of 36 hours or a ratio of 1:1\r\nLow ratios will be given the three strike warning from staff and can lead to a total ban', 1214339531, 0),
(8, 11, 'Downloaded', 'Remember, if you see any specific instance of this software running publicly, it''s within your rights under gpl to garner a copy of that derivative from the person responsible for that webserver.', 1293322039, 0);

-- --------------------------------------------------------

--
-- Table structure for table `rules_categories`
--

CREATE TABLE IF NOT EXISTS `rules_categories` (
`cid` int(3) unsigned NOT NULL,
  `rcat_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `min_class_read` int(2) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `rules_categories`
--

INSERT INTO `rules_categories` (`cid`, `rcat_name`, `min_class_read`) VALUES
(1, ':: ::General Site Rules', 0),
(2, ':: ::Forum Rules', 0),
(3, ':: ::Uploaders Rules', 2),
(4, ':: ::Free leech rules', 0),
(5, ':: ::Downloading rules', 0),
(14, 'heading No.14', 6),
(12, 'Remember', 0),
(11, 'it''s within your rights under gpl', 0);

-- --------------------------------------------------------

--
-- Table structure for table `searchcloud`
--

CREATE TABLE IF NOT EXISTS `searchcloud` (
`id` int(10) unsigned NOT NULL,
  `searchedfor` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `howmuch` int(10) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `searchcloud`
--

INSERT INTO `searchcloud` (`id`, `searchedfor`, `howmuch`) VALUES
(1, 'bob', 1),
(2, 'testing', 4),
(3, 'blackadder', 1),
(4, '24', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sitelog`
--

CREATE TABLE IF NOT EXISTS `sitelog` (
`id` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `txt` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stylesheets`
--

CREATE TABLE IF NOT EXISTS `stylesheets` (
`id` int(10) unsigned NOT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `stylesheets`
--

INSERT INTO `stylesheets` (`id`, `uri`, `name`) VALUES
(1, '1', '(default)'),
(4, '2', 'Groovy Too');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
`id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `topicid` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
`id` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locked` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `forumid` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `sticky` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `pollid` int(10) unsigned NOT NULL DEFAULT '0',
  `anonymous` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `torrents`
--

CREATE TABLE IF NOT EXISTS `torrents` (
`id` int(10) unsigned NOT NULL,
  `info_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `save_as` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `search_text` text COLLATE utf8_unicode_ci NOT NULL,
  `descr` text COLLATE utf8_unicode_ci NOT NULL,
  `ori_descr` text COLLATE utf8_unicode_ci NOT NULL,
  `category` int(10) unsigned NOT NULL DEFAULT '0',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL,
  `type` enum('single','multi') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'single',
  `numfiles` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` int(10) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `times_completed` int(10) unsigned NOT NULL DEFAULT '0',
  `leechers` int(10) unsigned NOT NULL DEFAULT '0',
  `seeders` int(10) unsigned NOT NULL DEFAULT '0',
  `last_action` int(11) NOT NULL,
  `visible` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `banned` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `owner` int(10) unsigned NOT NULL DEFAULT '0',
  `numratings` int(10) unsigned NOT NULL DEFAULT '0',
  `ratingsum` int(10) unsigned NOT NULL DEFAULT '0',
  `nfo` text COLLATE utf8_unicode_ci NOT NULL,
  `client_created_by` char(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(10) unsigned NOT NULL,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `passhash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `secret` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `passkey` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('pending','confirmed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `added` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `last_access` int(11) NOT NULL,
  `forum_access` int(11) NOT NULL DEFAULT '0',
  `editsecret` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `privacy` enum('strong','normal','low') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'normal',
  `stylesheet` int(10) DEFAULT '1',
  `info` text COLLATE utf8_unicode_ci,
  `signature` text COLLATE utf8_unicode_ci,
  `signatures` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `acceptpms` enum('yes','friends','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `class` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `language` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `avatar` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `av_w` smallint(3) unsigned NOT NULL DEFAULT '0',
  `av_h` smallint(3) unsigned NOT NULL DEFAULT '0',
  `uploaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `downloaded` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `country` int(10) unsigned NOT NULL DEFAULT '0',
  `notifs` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `modcomment` text COLLATE utf8_unicode_ci NOT NULL,
  `enabled` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `avatars` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `donor` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `donoruntil` int(11) NOT NULL DEFAULT '0',
  `warned` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `warneduntil` int(11) NOT NULL DEFAULT '0',
  `torrentsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `topicsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `postsperpage` int(3) unsigned NOT NULL DEFAULT '0',
  `deletepms` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `savepms` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `reputation` int(10) NOT NULL DEFAULT '10',
  `time_offset` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `dst_in_use` tinyint(1) NOT NULL DEFAULT '0',
  `auto_correct_dst` tinyint(1) NOT NULL DEFAULT '1',
  `forum_mod` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `forums_mod` varchar(320) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `subscription_pm` enum('yes','no') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'no',
  `mood` int(10) NOT NULL DEFAULT '1',
  `anonymous` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `passhash`, `secret`, `passkey`, `email`, `status`, `added`, `last_login`, `last_access`, `forum_access`, `editsecret`, `privacy`, `stylesheet`, `info`, `signature`, `signatures`, `acceptpms`, `ip`, `class`, `language`, `avatar`, `av_w`, `av_h`, `uploaded`, `downloaded`, `title`, `country`, `notifs`, `modcomment`, `enabled`, `avatars`, `donor`, `donoruntil`, `warned`, `warneduntil`, `torrentsperpage`, `topicsperpage`, `postsperpage`, `deletepms`, `savepms`, `reputation`, `time_offset`, `dst_in_use`, `auto_correct_dst`, `forum_mod`, `forums_mod`, `subscription_pm`, `mood`, `anonymous`) VALUES
(1, 'ZajferX', '64d34b806ef8f14ebf6237f604c2898f', 'YJ)5f', '', 'zajferx@gmail.com', 'confirmed', 0, 1403773748, 1403773918, 0, '', 'normal', 1, NULL, NULL, 'yes', 'yes', '::1', 6, 'en', '', 0, 0, 0, 0, '', 0, '', '', 'yes', 'yes', 'no', 0, 'no', 0, 0, 0, 0, 'yes', 'no', 10, '0', 1, 1, 'no', '', 'no', 1, 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachmentdownloads`
--
ALTER TABLE `attachmentdownloads`
 ADD PRIMARY KEY (`id`), ADD KEY `fileid_userid` (`fileid`,`userid`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
 ADD PRIMARY KEY (`id`), ADD KEY `topicid` (`topicid`), ADD KEY `postid` (`postid`);

--
-- Indexes for table `avps`
--
ALTER TABLE `avps`
 ADD PRIMARY KEY (`arg`);

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
 ADD PRIMARY KEY (`id`), ADD KEY `first_last` (`first`,`last`);

--
-- Indexes for table `blocks`
--
ALTER TABLE `blocks`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `userfriend` (`userid`,`blockid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cleanup`
--
ALTER TABLE `cleanup`
 ADD PRIMARY KEY (`clean_id`), ADD KEY `clean_time` (`clean_time`);

--
-- Indexes for table `cleanup_log`
--
ALTER TABLE `cleanup_log`
 ADD PRIMARY KEY (`clog_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`), ADD KEY `torrent` (`torrent`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
 ADD PRIMARY KEY (`id`), ADD KEY `torrent` (`torrent`), ADD FULLTEXT KEY `filename` (`filename`);

--
-- Indexes for table `forums`
--
ALTER TABLE `forums`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum_mods`
--
ALTER TABLE `forum_mods`
 ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`fid`);

--
-- Indexes for table `forum_parents`
--
ALTER TABLE `forum_parents`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum_polls`
--
ALTER TABLE `forum_polls`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum_poll_answers`
--
ALTER TABLE `forum_poll_answers`
 ADD PRIMARY KEY (`id`), ADD KEY `pollid` (`pollid`);

--
-- Indexes for table `forum_subs`
--
ALTER TABLE `forum_subs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `userfriend` (`userid`,`friendid`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
 ADD PRIMARY KEY (`id`), ADD KEY `receiver` (`receiver`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`id`), ADD KEY `added` (`added`);

--
-- Indexes for table `peers`
--
ALTER TABLE `peers`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`), ADD KEY `torrent` (`torrent`), ADD KEY `torrent_seeder` (`torrent`,`seeder`), ADD KEY `last_action` (`last_action`), ADD KEY `connectable` (`connectable`), ADD KEY `userid` (`userid`), ADD KEY `passkey` (`passkey`), ADD KEY `torrent_connect` (`torrent`,`connectable`);

--
-- Indexes for table `pmboxes`
--
ALTER TABLE `pmboxes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
 ADD PRIMARY KEY (`id`), ADD KEY `topicid` (`topicid`), ADD KEY `userid` (`userid`), ADD FULLTEXT KEY `body` (`body`);

--
-- Indexes for table `readposts`
--
ALTER TABLE `readposts`
 ADD PRIMARY KEY (`id`), ADD KEY `topicid` (`topicid`);

--
-- Indexes for table `reputation`
--
ALTER TABLE `reputation`
 ADD PRIMARY KEY (`reputationid`), ADD KEY `userid` (`userid`), ADD KEY `whoadded` (`whoadded`), ADD KEY `multi` (`postid`,`userid`), ADD KEY `dateadd` (`dateadd`);

--
-- Indexes for table `reputationlevel`
--
ALTER TABLE `reputationlevel`
 ADD PRIMARY KEY (`reputationlevelid`), ADD KEY `reputationlevel` (`minimumreputation`);

--
-- Indexes for table `rules`
--
ALTER TABLE `rules`
 ADD PRIMARY KEY (`id`), ADD KEY `cat_id` (`cid`);

--
-- Indexes for table `rules_categories`
--
ALTER TABLE `rules_categories`
 ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `searchcloud`
--
ALTER TABLE `searchcloud`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `searchedfor` (`searchedfor`);

--
-- Indexes for table `sitelog`
--
ALTER TABLE `sitelog`
 ADD PRIMARY KEY (`id`), ADD KEY `added` (`added`);

--
-- Indexes for table `stylesheets`
--
ALTER TABLE `stylesheets`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
 ADD PRIMARY KEY (`id`), ADD KEY `userid` (`userid`), ADD KEY `subject` (`subject`), ADD KEY `lastpost` (`lastpost`), ADD KEY `locked_sticky` (`locked`,`sticky`);

--
-- Indexes for table `torrents`
--
ALTER TABLE `torrents`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `info_hash` (`info_hash`), ADD KEY `owner` (`owner`), ADD KEY `visible` (`visible`), ADD KEY `category_visible` (`category`,`visible`), ADD FULLTEXT KEY `ft_search` (`search_text`,`ori_descr`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD KEY `ip` (`ip`), ADD KEY `uploaded` (`uploaded`), ADD KEY `downloaded` (`downloaded`), ADD KEY `country` (`country`), ADD KEY `last_access` (`last_access`), ADD KEY `enabled` (`enabled`), ADD KEY `warned` (`warned`), ADD KEY `pkey` (`passkey`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachmentdownloads`
--
ALTER TABLE `attachmentdownloads`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bans`
--
ALTER TABLE `bans`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `blocks`
--
ALTER TABLE `blocks`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `cleanup`
--
ALTER TABLE `cleanup`
MODIFY `clean_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `cleanup_log`
--
ALTER TABLE `cleanup_log`
MODIFY `clog_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forums`
--
ALTER TABLE `forums`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forum_mods`
--
ALTER TABLE `forum_mods`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forum_parents`
--
ALTER TABLE `forum_parents`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forum_polls`
--
ALTER TABLE `forum_polls`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forum_poll_answers`
--
ALTER TABLE `forum_poll_answers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `forum_subs`
--
ALTER TABLE `forum_subs`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `peers`
--
ALTER TABLE `peers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pmboxes`
--
ALTER TABLE `pmboxes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `readposts`
--
ALTER TABLE `readposts`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reputation`
--
ALTER TABLE `reputation`
MODIFY `reputationid` int(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reputationlevel`
--
ALTER TABLE `reputationlevel`
MODIFY `reputationlevelid` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `rules`
--
ALTER TABLE `rules`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `rules_categories`
--
ALTER TABLE `rules_categories`
MODIFY `cid` int(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `searchcloud`
--
ALTER TABLE `searchcloud`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sitelog`
--
ALTER TABLE `sitelog`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stylesheets`
--
ALTER TABLE `stylesheets`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `torrents`
--
ALTER TABLE `torrents`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

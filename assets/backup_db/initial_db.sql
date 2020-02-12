-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2016 at 10:28 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aes_saas`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(200) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `user_agent` varchar(199) NOT NULL,
  `last_activity` varchar(199) NOT NULL,
  `user_data` longtext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('a4fbb5fc2f4ef2e57011da2486c9de14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0', '1455013538', 'a:2:{s:9:"user_data";s:0:"";s:19:"flash:old:login_msg";s:25:"Invalid email or password";}');

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE IF NOT EXISTS `domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `last_scraped_time` datetime NOT NULL,
  `is_available` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `domain`
--


-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `url_id` int(11) NOT NULL,
  `search_engine_url_id` int(11) NOT NULL,
  `found_email` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `email`
--


-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE IF NOT EXISTS `email_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `email_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `email_provider`
--

CREATE TABLE IF NOT EXISTS `email_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_name` varchar(250) NOT NULL,
  `provider_address` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `email_provider`
--

INSERT INTO `email_provider` (`id`, `provider_name`, `provider_address`) VALUES
(1, 'gmail.com', 'www.gmail.com'),
(2, 'yahoo.com', 'www.yahoo.com'),
(3, 'hotmail.com', 'hotmail.com'),
(4, 'outlook.com', 'www.outlook.com'),
(5, 'mail.com', 'www.mail.com'),
(6, 'rediff.com', 'https://mail.rediff.com'),
(7, 'yandex.com', 'https://mail.yandex.com'),
(8, 'gmx.com', 'http://www.gmx.com'),
(9, 'inbox.com', 'http://www.inbox.com'),
(10, 'fastmail.com', 'https://www.fastmail.com'),
(11, 'hushmail.com', 'https://www.hushmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `forget_password`
--

CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `forget_password`
--


-- --------------------------------------------------------

--
-- Table structure for table `fuzzy_string_replace`
--

CREATE TABLE IF NOT EXISTS `fuzzy_string_replace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `search_string` varchar(250) NOT NULL,
  `replaced_by` varchar(250) NOT NULL,
  `deleted` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `fuzzy_string_replace`
--

INSERT INTO `fuzzy_string_replace` (`id`, `user_id`, `search_string`, `replaced_by`, `deleted`) VALUES
(1, 1, '[at]', '@', 0),
(2, 1, ' dot ', '.', 0),
(3, 1, '[dot]', '.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_status`
--

CREATE TABLE IF NOT EXISTS `page_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `http_code` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL,
  `total_time` varchar(50) NOT NULL,
  `namelookup_time` varchar(50) NOT NULL,
  `connect_time` varchar(50) NOT NULL,
  `speed_download` varchar(50) NOT NULL,
  `check_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `page_status`
--


-- --------------------------------------------------------

--
-- Table structure for table `payment_config`
--

CREATE TABLE IF NOT EXISTS `payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `monthly_fee` double NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `payment_config`
--

INSERT INTO `payment_config` (`id`, `paypal_email`, `monthly_fee`, `deleted`) VALUES
(3, 'test@gmail.com', 100, '0');

-- --------------------------------------------------------

--
-- Table structure for table `proxy`
--

CREATE TABLE IF NOT EXISTS `proxy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `proxy_ip` varchar(20) NOT NULL,
  `port` varchar(20) NOT NULL,
  `is_available` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `proxy`
--


-- --------------------------------------------------------

--
-- Table structure for table `search_engine_search`
--

CREATE TABLE IF NOT EXISTS `search_engine_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `search_keyword` varchar(250) NOT NULL,
  `search_in` varchar(250) NOT NULL,
  `last_scraped_time` datetime NOT NULL,
  `search_engine_name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `search_engine_search`
--


-- --------------------------------------------------------

--
-- Table structure for table `searh_engine`
--

CREATE TABLE IF NOT EXISTS `searh_engine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `search_engine_name` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `searh_engine`
--

INSERT INTO `searh_engine` (`id`, `search_engine_name`, `address`) VALUES
(1, 'Google', 'www.google.com'),
(2, 'Bing', 'www.bing.com');

-- --------------------------------------------------------

--
-- Table structure for table `social_network`
--

CREATE TABLE IF NOT EXISTS `social_network` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `social_network_name` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `social_network`
--

INSERT INTO `social_network` (`id`, `social_network_name`, `address`) VALUES
(1, 'facebook.com', 'www.facebook.com'),
(2, 'twitter.com', 'twitter.com'),
(3, 'linkedin.com', 'linkedin.com'),
(4, 'pinterest.com', 'pinterest.com'),
(5, 'tumblr.com', 'tumblr.com'),
(6, 'reddit.com', 'reddit.com'),
(7, 'flickr.com', 'flickr.com'),
(8, 'instagram.com', 'instagram.com');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `paypal_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_date` varchar(250) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `transaction_history`
--


-- --------------------------------------------------------

--
-- Table structure for table `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `url_name` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `last_scraped_time` datetime NOT NULL,
  `is_available` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `url`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `password` varchar(99) NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activation_code` int(10) NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `expired_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `activation_code`, `deleted`, `expired_date`) VALUES
(1, 'Admin', 'admin@gmail.com', '017********', '', '', 'Admin', '1', '2016-02-09 10:27:10', 0, '0', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `whois_search`
--

CREATE TABLE IF NOT EXISTS `whois_search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `owner_email` varchar(250) NOT NULL,
  `tech_email` varchar(250) NOT NULL,
  `admin_email` varchar(250) NOT NULL,
  `is_registered` varchar(50) NOT NULL,
  `namve_servers` varchar(250) NOT NULL,
  `created_at` date NOT NULL,
  `sponsor` varchar(250) NOT NULL,
  `changed_at` varchar(250) NOT NULL,
  `expire_at` varchar(250) NOT NULL,
  `scraped_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `whois_search`
--

ALTER TABLE `search_engine_search` ADD `country` VARCHAR( 100 ) NULL ,
	ADD `language` VARCHAR( 100 ) NULL ; 
	
ALTER TABLE `payment_config` ADD `currency` ENUM( 'USD', 'AUD', 'CAD', 'EUR', 'ILS', 'NZD', 'RUB', 'SGD', 'SEK' ) NOT NULL AFTER `monthly_fee`; 




ALTER TABLE  `users` ADD  `package_id` INT( 11 ) NOT NULL AFTER  `expired_date`;
CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) CHARACTER SET latin1 NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT "0",
  `validity` int(11) NOT NULL,
  `is_default` enum("0","1") NOT NULL,
  `deleted` enum("0","1") CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `is_default`, `deleted`) VALUES
(1, "Trial", "2,1,8,7,9,5,3,4,6", '{"2":"0","1":"0","8":"0","7":"0","9":"0","5":"0","3":"0","4":"0","6":"0"}', '{"2":"0","1":"0","8":"0","7":"0","9":"0","5":"0","3":"0","4":"0","6":"0"}', "Trial", "7", "1", "0");


CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `deleted` enum("0","1") NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;


INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES
(1, "Crawl Website", "0"),
(2, "Crawl URL", "0"),
(3, "Search in Search Engine", "0"),
(4, "Search in Text/XML/JSON", "0"),
(5, "Search in Doc/Docx/Pdf", "0"),
(6, "Whois Search", "0"),
(7, "Email Validation Check", "0"),
(8, "Duplicate Email Filter", "0"),
(9, "Page Status Check", "0");


CREATE TABLE IF NOT EXISTS `usage_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `transaction_history` ADD `package_id` INT NOT NULL AFTER `cycle_start_date` ;

CREATE
 ALGORITHM = UNDEFINED
 VIEW `view_usage_log`
 (id,module_id,user_id,usage_month,usage_year,usage_count)
 AS select * from usage_log where `usage_month`=MONTH(curdate()) and `usage_year`= YEAR(curdate()) ;
 ALTER TABLE  `payment_config` DROP  `monthly_fee`;


ALTER TABLE `transaction_history` ADD `stripe_card_source` TEXT NOT NULL ;

ALTER TABLE `payment_config` ADD `stripe_secret_key` VARCHAR( 150 ) NOT NULL AFTER `paypal_email` ;

ALTER TABLE `payment_config` ADD `stripe_publishable_key` VARCHAR( 150 ) NOT NULL AFTER `stripe_secret_key` ;


ALTER TABLE  `whois_search` ADD  `admin_name` VARCHAR( 250 ) NULL AFTER  `admin_email` ,
ADD  `registrant_name` VARCHAR( 250 ) NULL AFTER  `admin_name` ,
ADD  `admin_phone` VARCHAR( 100 ) NULL AFTER  `registrant_name` ,
ADD  `registrant_phone` VARCHAR( 100 ) NULL AFTER  `admin_phone`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

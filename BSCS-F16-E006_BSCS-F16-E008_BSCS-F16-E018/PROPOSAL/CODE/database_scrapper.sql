-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2020 at 04:53 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_scrapper`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(200) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `user_agent` varchar(199) NOT NULL,
  `last_activity` varchar(199) NOT NULL,
  `user_data` longtext CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('3ce72ac543a437971ab9aba4ff4ae79e', '52.114.6.38', 'Mozilla/5.0 (Windows NT 6.1; WOW64) SkypeUriPreview Preview/0.5', '1576251332', ''),
('405e511dc12a486ded167f1234ef51a3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', '1576562570', 'a:8:{s:9:\"user_data\";s:0:\"\";s:9:\"logged_in\";i:1;s:8:\"username\";s:17:\"Prosstar Scrapper\";s:9:\"user_type\";s:5:\"Admin\";s:7:\"user_id\";s:1:\"1\";s:11:\"download_id\";i:1576562588;s:11:\"expiry_date\";s:10:\"0000-00-00\";s:12:\"package_info\";a:0:{}}'),
('5cc9653b988541e3083526e9c4c4c72d', '52.114.6.38', 'Mozilla/5.0 (Windows NT 6.1; WOW64) SkypeUriPreview Preview/0.5', '1576251286', ''),
('5fcd5c21bb32357d1c0ff695a81f7548', '172.58.221.37', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/', '1576275266', ''),
('70e1da2d4813b7f630a6d604ea491221', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36', '1578214242', 'a:8:{s:9:\"user_data\";s:0:\"\";s:9:\"logged_in\";i:1;s:8:\"username\";s:17:\"Prosstar Scrapper\";s:9:\"user_type\";s:5:\"Admin\";s:7:\"user_id\";s:1:\"1\";s:11:\"download_id\";i:1578315685;s:11:\"expiry_date\";s:10:\"0000-00-00\";s:12:\"package_info\";a:0:{}}'),
('761046a7dec27ea1976e05bfce540f7e', '52.114.6.38', 'Mozilla/5.0 (Windows NT 6.1; WOW64) SkypeUriPreview Preview/0.5', '1576251332', ''),
('9d3ac8c009cdd5fc18b6a76094004f38', '74.125.208.11', 'Mozilla/5.0 (Linux; Android 6.0.1; vivo 1606) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Mobile Safari/', '1576479054', ''),
('a4fbb5fc2f4ef2e57011da2486c9de14', '::1', 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:43.0) Gecko/20100101 Firefox/43.0', '1455013538', 'a:2:{s:9:\"user_data\";s:0:\"\";s:19:\"flash:old:login_msg\";s:25:\"Invalid email or password\";}'),
('c6e8931b95e22bdc9b1a21d62e2ef5db', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', '1576568071', ''),
('cd0e3c4f942b7df4f1af5006cd0c30f0', '172.58.221.37', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/', '1576275277', ''),
('d5ffc8f355629bf4e6276f34a35d9072', '108.34.133.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/', '1576251295', ''),
('ff5781018ca686ef8aa2b6eee33b8323', '39.52.64.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', '1576251337', 'a:9:{s:9:\"user_data\";s:0:\"\";s:9:\"logged_in\";i:1;s:8:\"username\";s:17:\"Prosstar Scrapper\";s:9:\"user_type\";s:5:\"Admin\";s:7:\"user_id\";s:1:\"1\";s:11:\"download_id\";i:1576251363;s:11:\"expiry_date\";s:10:\"0000-00-00\";s:12:\"package_info\";a:0:{}s:23:\"change_user_password_id\";s:1:\"1\";}');

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE `domain` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `last_scraped_time` datetime NOT NULL,
  `is_available` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE `email` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `url_id` int(11) NOT NULL,
  `search_engine_url_id` int(11) NOT NULL,
  `found_email` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_provider`
--

CREATE TABLE `email_provider` (
  `id` int(11) NOT NULL,
  `provider_name` varchar(250) NOT NULL,
  `provider_address` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

CREATE TABLE `forget_password` (
  `id` int(12) NOT NULL,
  `confirmation_code` varchar(15) CHARACTER SET latin1 NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fuzzy_string_replace`
--

CREATE TABLE `fuzzy_string_replace` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `search_string` varchar(250) NOT NULL,
  `replaced_by` varchar(250) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fuzzy_string_replace`
--

INSERT INTO `fuzzy_string_replace` (`id`, `user_id`, `search_string`, `replaced_by`, `deleted`) VALUES
(1, 1, '[at]', '@', 0),
(2, 1, ' dot ', '.', 0),
(3, 1, '[dot]', '.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_name`, `deleted`) VALUES
(1, 'Crawl Website', '0'),
(2, 'Crawl URL', '0'),
(3, 'Search in Search Engine', '0'),
(4, 'Search in Text/XML/JSON', '0'),
(5, 'Search in Doc/Docx/Pdf', '0'),
(6, 'Whois Search', '0'),
(7, 'Email Validation Check', '0'),
(8, 'Duplicate Email Filter', '0'),
(9, 'Page Status Check', '0');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id` int(11) NOT NULL,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) CHARACTER SET latin1 NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `is_default` enum('0','1') NOT NULL,
  `deleted` enum('0','1') CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `is_default`, `deleted`) VALUES
(1, 'Trial', '2,1,8,7,9,5,3,4,6', '{\"2\":\"0\",\"1\":\"0\",\"8\":\"0\",\"7\":\"0\",\"9\":\"0\",\"5\":\"0\",\"3\":\"0\",\"4\":\"0\",\"6\":\"0\"}', '{\"2\":\"0\",\"1\":\"0\",\"8\":\"0\",\"7\":\"0\",\"9\":\"0\",\"5\":\"0\",\"3\":\"0\",\"4\":\"0\",\"6\":\"0\"}', 'Trial', 7, '1', '0');

-- --------------------------------------------------------

--
-- Table structure for table `page_status`
--

CREATE TABLE `page_status` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `http_code` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL,
  `total_time` varchar(50) NOT NULL,
  `namelookup_time` varchar(50) NOT NULL,
  `connect_time` varchar(50) NOT NULL,
  `speed_download` varchar(50) NOT NULL,
  `check_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_config`
--

CREATE TABLE `payment_config` (
  `id` int(11) NOT NULL,
  `paypal_email` varchar(250) NOT NULL,
  `stripe_secret_key` varchar(150) NOT NULL,
  `stripe_publishable_key` varchar(150) NOT NULL,
  `currency` enum('USD','AUD','CAD','EUR','ILS','NZD','RUB','SGD','SEK') NOT NULL,
  `deleted` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_config`
--

INSERT INTO `payment_config` (`id`, `paypal_email`, `stripe_secret_key`, `stripe_publishable_key`, `currency`, `deleted`) VALUES
(3, 'test@gmail.com', '', '', 'USD', '0');

-- --------------------------------------------------------

--
-- Table structure for table `proxy`
--

CREATE TABLE `proxy` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `proxy_ip` varchar(20) NOT NULL,
  `port` varchar(20) NOT NULL,
  `is_available` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `search_engine_search`
--

CREATE TABLE `search_engine_search` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `search_keyword` varchar(250) NOT NULL,
  `search_in` varchar(250) NOT NULL,
  `last_scraped_time` datetime NOT NULL,
  `search_engine_name` varchar(250) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `searh_engine`
--

CREATE TABLE `searh_engine` (
  `id` int(11) NOT NULL,
  `search_engine_name` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

CREATE TABLE `social_network` (
  `id` int(11) NOT NULL,
  `social_network_name` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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

CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL,
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
  `package_id` int(11) NOT NULL,
  `cycle_expired_date` date NOT NULL,
  `stripe_card_source` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `url`
--

CREATE TABLE `url` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `url_name` varchar(250) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `last_scraped_time` datetime NOT NULL,
  `is_available` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usage_log`
--

CREATE TABLE `usage_log` (
  `id` bigint(20) NOT NULL,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `usage_month` int(11) NOT NULL,
  `usage_year` year(4) NOT NULL,
  `usage_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
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
  `package_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `address`, `user_type`, `status`, `add_date`, `activation_code`, `deleted`, `expired_date`, `package_id`) VALUES
(1, 'Prosstar Scrapper', 'mansha.qarib777@gmail.com', '0232323023', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'Block B House #110', 'Admin', '1', '2016-02-09 17:27:10', 0, '0', '0000-00-00', 0),
(2, 'Mansha', 'ma56nsha.qarib777@gmail.com', '0232323023', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'mansha qarib', 'Member', '0', '2019-12-12 22:27:09', 128230, '0', '2019-12-19', 1),
(3, 'gdgdg', 'mansha78.qarib777@gmail.com', '12312321', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'jjhjk', 'Member', '1', '2019-12-12 22:41:15', 118721, '0', '2019-12-19', 1),
(4, 'Mansha', 'mansha.qarib777@gma12il.com', '0912839', '5ebe2294ecd0e0f08eab7690d2a6ee69', 'ashdjk', 'Member', '1', '2019-12-17 06:29:23', 130304, '0', '2019-12-24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `whois_search`
--

CREATE TABLE `whois_search` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `domain_name` varchar(250) NOT NULL,
  `owner_email` varchar(250) NOT NULL,
  `tech_email` varchar(250) NOT NULL,
  `admin_email` varchar(250) NOT NULL,
  `admin_name` varchar(250) DEFAULT NULL,
  `registrant_name` varchar(250) DEFAULT NULL,
  `admin_phone` varchar(100) DEFAULT NULL,
  `registrant_phone` varchar(100) DEFAULT NULL,
  `is_registered` varchar(50) NOT NULL,
  `namve_servers` varchar(250) NOT NULL,
  `created_at` date NOT NULL,
  `sponsor` varchar(250) NOT NULL,
  `changed_at` varchar(250) NOT NULL,
  `expire_at` varchar(250) NOT NULL,
  `scraped_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `domain`
--
ALTER TABLE `domain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email`
--
ALTER TABLE `email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_provider`
--
ALTER TABLE `email_provider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forget_password`
--
ALTER TABLE `forget_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fuzzy_string_replace`
--
ALTER TABLE `fuzzy_string_replace`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_status`
--
ALTER TABLE `page_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_config`
--
ALTER TABLE `payment_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proxy`
--
ALTER TABLE `proxy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_engine_search`
--
ALTER TABLE `search_engine_search`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `searh_engine`
--
ALTER TABLE `searh_engine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_network`
--
ALTER TABLE `social_network`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `url`
--
ALTER TABLE `url`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usage_log`
--
ALTER TABLE `usage_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whois_search`
--
ALTER TABLE `whois_search`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `domain`
--
ALTER TABLE `domain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email`
--
ALTER TABLE `email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_config`
--
ALTER TABLE `email_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_provider`
--
ALTER TABLE `email_provider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `forget_password`
--
ALTER TABLE `forget_password`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuzzy_string_replace`
--
ALTER TABLE `fuzzy_string_replace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page_status`
--
ALTER TABLE `page_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_config`
--
ALTER TABLE `payment_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `proxy`
--
ALTER TABLE `proxy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_engine_search`
--
ALTER TABLE `search_engine_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `searh_engine`
--
ALTER TABLE `searh_engine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `social_network`
--
ALTER TABLE `social_network`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `url`
--
ALTER TABLE `url`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usage_log`
--
ALTER TABLE `usage_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `whois_search`
--
ALTER TABLE `whois_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

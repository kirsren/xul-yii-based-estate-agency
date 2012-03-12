-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Hoszt: localhost
-- Létrehozás ideje: 2012. márc. 12. 01:39
-- Szerver verzió: 5.1.37
-- PHP verzió: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Adatbázis: `szakdoga_test`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet: `agents`
--

CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `username` varchar(12) NOT NULL,
  `rank` varchar(10) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- A tábla adatainak kiíratása `agents`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `idnumber` int(11) NOT NULL,
  `description` text NOT NULL,
  `agent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- A tábla adatainak kiíratása `clients`
--

INSERT INTO `clients` (`id`, `name`, `idnumber`, `description`, `agent`) VALUES
(1, 'Lengyel Zsolt', 423535, '', 1),
(2, 'Kiss Márton', 36457456, '', 4);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `estates`
--

CREATE TABLE IF NOT EXISTS `estates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `rooms` tinyint(4) NOT NULL,
  `heating` varchar(20) NOT NULL,
  `type` varchar(10) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `agent` int(11) NOT NULL,
  `client` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `estates`
--

INSERT INTO `estates` (`id`, `name`, `description`, `price`, `rooms`, `heating`, `type`, `city`, `address`, `agent`, `client`) VALUES
(1, 'Elsőm', 'Leírása', 500, 5, 'Padló', 'Tégla', 'Szeged', 'Makk utca 4', 1, 1),
(2, 'Kecskemét kertes', 'jó állapotban lévő', 323, 3, '4', 'lakás', 'Kecskemét', 'Petőfi u. 43', 4, 1),
(3, 'Szegedi ingatlan', 'Lorem ipsum dolor site ament', 43, 2, 'vegyes', 'panel', 'Szeged', 'Füle utca 3', 4, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- A tábla adatainak kiíratása `items`
--

INSERT INTO `items` (`id`, `title`, `content`, `date`) VALUES
(1, 'Első', 'Első tartalma', '2011-12-15 16:46:47'),
(2, 'Második', 'Második tartalma', '2011-12-15 16:46:47'),
(3, 'Első', 'Első tartalma', '2011-12-15 16:46:52'),
(4, 'Második', 'Második tartalma', '2011-12-15 16:46:52');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) unsigned NOT NULL,
  `to_user_id` int(10) unsigned NOT NULL,
  `title` varchar(45) NOT NULL,
  `message` text,
  `message_read` tinyint(1) NOT NULL,
  `draft` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_messages_users` (`from_user_id`),
  KEY `fk_messages_users1` (`to_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- A tábla adatainak kiíratása `messages`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `profile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `privacy` enum('protected','private','public') NOT NULL,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`profile_id`),
  KEY `fk_profiles_users` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- A tábla adatainak kiíratása `profiles`
--

INSERT INTO `profiles` (`profile_id`, `user_id`, `timestamp`, `privacy`, `lastname`, `firstname`, `email`) VALUES
(1, 1, '2012-01-30 15:23:01', 'protected', 'admin', 'admin', 'webmaster@example.com'),
(2, 2, '2012-01-30 15:23:01', 'protected', 'demo', 'demo', 'demo@example.com'),
(3, 3, '2012-01-30 21:16:58', 'protected', 'Lengyel', 'Zsolt', 'sse@gd.hu'),
(4, 3, '2012-01-30 21:17:45', 'protected', 'Lengyel', 'Zsolt', 'sse@gd.hu'),
(5, 4, '2012-01-30 22:25:22', 'protected', 'Nagy', 'Terézia', 'terike@ceg.hu');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `profile_fields`
--

CREATE TABLE IF NOT EXISTS `profile_fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `field_group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `hint` text NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL,
  `range` varchar(255) NOT NULL,
  `error_message` varchar(255) NOT NULL,
  `other_validator` varchar(255) NOT NULL,
  `default` varchar(255) NOT NULL,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `profile_fields`
--

INSERT INTO `profile_fields` (`id`, `field_group_id`, `varname`, `title`, `hint`, `field_type`, `field_size`, `field_size_min`, `required`, `match`, `range`, `error_message`, `other_validator`, `default`, `position`, `visible`) VALUES
(1, 0, 'email', 'E-Mail', '', 'VARCHAR', 255, 0, 1, '', '', '', '', '', 0, 2),
(2, 0, 'firstname', 'First name', '', 'VARCHAR', 255, 0, 1, '', '', '', '', '', 0, 2),
(3, 0, 'lastname', 'Last name', '', 'VARCHAR', 255, 0, 1, '', '', '', '', '', 0, 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `profile_fields_group`
--

CREATE TABLE IF NOT EXISTS `profile_fields_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `position` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- A tábla adatainak kiíratása `profile_fields_group`
--


-- --------------------------------------------------------

--
-- Tábla szerkezet: `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- A tábla adatainak kiíratása `roles`
--

INSERT INTO `roles` (`id`, `title`, `description`) VALUES
(1, 'UserCreator', 'This users can create new Users'),
(2, 'UserRemover', 'This users can remove other Users'),
(3, 'Agent', '');

-- --------------------------------------------------------

--
-- Tábla szerkezet: `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `activationKey` varchar(128) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `lastvisit` int(10) NOT NULL DEFAULT '0',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `activationKey`, `createtime`, `lastvisit`, `superuser`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 1327933405, 1331510718, 1, 1),
(2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', '', 1327933405, 0, 0, 1),
(3, 'zsolti', '5f4dcc3b5aa765d61d8327deb882cf99', 'b9866508fd6571a976e9df4d4f3811f4', 1327954642, 1327954642, 0, 1),
(4, 'terike', '5f4dcc3b5aa765d61d8327deb882cf99', 'a332b86dfa49e3a2b0dd230a6f710f6b', 1327958746, 1327958746, 0, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `user_has_role`
--

CREATE TABLE IF NOT EXISTS `user_has_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- A tábla adatainak kiíratása `user_has_role`
--

INSERT INTO `user_has_role` (`id`, `user_id`, `role_id`) VALUES
(5, 1, 3),
(6, 4, 3);

-- --------------------------------------------------------

--
-- Tábla szerkezet: `user_has_user`
--

CREATE TABLE IF NOT EXISTS `user_has_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL,
  `child_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- A tábla adatainak kiíratása `user_has_user`
--


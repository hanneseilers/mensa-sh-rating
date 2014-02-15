-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Sep 2013 um 13:30
-- Server Version: 5.5.32
-- PHP-Version: 5.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `35110m24661_2`
--
CREATE DATABASE IF NOT EXISTS `35110m24661_2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `35110m24661_2`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `idlocations` int(11) NOT NULL AUTO_INCREMENT,
  `location` text,
  PRIMARY KEY (`idlocations`),
  UNIQUE KEY `idlocations_UNIQUE` (`idlocations`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `locations`
--

INSERT INTO `locations` (`idlocations`, `location`) VALUES
(1, 'Flensburg'),
(2, 'Heide'),
(3, 'Kiel'),
(4, 'Lübeck'),
(5, 'Osterrönfeld'),
(6, 'Wedel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `meals`
--

CREATE TABLE IF NOT EXISTS `meals` (
  `idmeals` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `pig` tinyint(1) NOT NULL,
  `cow` tinyint(1) NOT NULL,
  `vegetarian` tinyint(1) NOT NULL,
  `vegan` tinyint(1) NOT NULL,
  `alc` tinyint(1) DEFAULT NULL,
  `mensen_idmensen` int(11) NOT NULL,
  `mensen_locations_idlocations` int(11) NOT NULL,
  PRIMARY KEY (`idmeals`,`mensen_idmensen`,`mensen_locations_idlocations`),
  UNIQUE KEY `idratings_UNIQUE` (`idmeals`),
  KEY `fk_meals_mensen1_idx` (`mensen_idmensen`,`mensen_locations_idlocations`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mensen`
--

CREATE TABLE IF NOT EXISTS `mensen` (
  `idmensen` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `locations_idlocations` int(11) NOT NULL,
  PRIMARY KEY (`idmensen`,`locations_idlocations`),
  UNIQUE KEY `idmensen_UNIQUE` (`idmensen`),
  KEY `fk_mensen_locations_idx` (`locations_idlocations`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `mensen`
--

INSERT INTO `mensen` (`idmensen`, `name`, `locations_idlocations`) VALUES
(1, 'Mensa', 1),
(2, 'Cafeteria Munketoft', 1),
(3, 'Mensa FHW Heide', 2),
(4, 'Mensa 1 am Westring', 3),
(5, 'Mensa 2', 3),
(6, 'Bresterie in der Mensa 2', 3),
(7, 'Mensa Kesselhaus', 3),
(8, 'Mensa-Gaarden', 3),
(9, 'Schwentine-Mensa', 3),
(10, 'Mensa', 4),
(11, 'Mensa Osterrönfeld', 5),
(12, 'Mensa und Cafetria Wedel', 6);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `idratings` int(11) NOT NULL AUTO_INCREMENT,
  `rating` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `hash` longtext NOT NULL,
  `comment` longtext,
  `meals_idmeals` int(11) NOT NULL,
  PRIMARY KEY (`idratings`,`meals_idmeals`),
  UNIQUE KEY `idratings_UNIQUE` (`idratings`),
  KEY `fk_ratings_meals1_idx` (`meals_idmeals`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `meals`
--
ALTER TABLE `meals`
  ADD CONSTRAINT `fk_meals_mensen1` FOREIGN KEY (`mensen_idmensen`, `mensen_locations_idlocations`) REFERENCES `mensen` (`idmensen`, `locations_idlocations`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `mensen`
--
ALTER TABLE `mensen`
  ADD CONSTRAINT `fk_mensen_locations` FOREIGN KEY (`locations_idlocations`) REFERENCES `locations` (`idlocations`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_meals1` FOREIGN KEY (`meals_idmeals`) REFERENCES `meals` (`idmeals`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

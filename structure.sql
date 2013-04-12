-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 12 Avril 2013 à 21:52
-- Version du serveur: 5.5.25
-- Version de PHP: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `mail`
--

-- --------------------------------------------------------

--
-- Structure de la table `gesmail`
--

CREATE TABLE `gesmail` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Asso` varchar(100) NOT NULL COMMENT 'Login de l''asso',
  `Extension` varchar(100) NOT NULL COMMENT 'Partie après le tiret du nom de la boîte',
  `Type` enum('alias','ml') NOT NULL COMMENT 'Type de boîte',
  `MLPassword` varchar(24) DEFAULT NULL COMMENT 'Mot de passe de la ML, NULL pour les alias ou si la ML n''a pas encore été créée',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Asso_2` (`Asso`,`Extension`),
  KEY `Asso` (`Asso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste des alias et ML enregistrés dans gesmail.' ;

-- --------------------------------------------------------

--
-- Structure de la table `mailman_mysql`
--

CREATE TABLE `mailman_mysql` (
  `listname` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `hide` enum('Y','N') NOT NULL DEFAULT 'N',
  `nomail` enum('Y','N') NOT NULL DEFAULT 'N',
  `ack` enum('Y','N') NOT NULL DEFAULT 'Y',
  `not_metoo` enum('Y','N') NOT NULL DEFAULT 'Y',
  `digest` enum('Y','N') NOT NULL DEFAULT 'N',
  `plain` enum('Y','N') NOT NULL DEFAULT 'N',
  `password` varchar(255) NOT NULL DEFAULT '!',
  `lang` varchar(255) NOT NULL DEFAULT 'en',
  `name` varchar(255) DEFAULT NULL,
  `one_last_digest` enum('Y','N') NOT NULL DEFAULT 'N',
  `user_options` bigint(20) NOT NULL DEFAULT '0',
  `delivery_status` int(10) NOT NULL DEFAULT '0',
  `topics_userinterest` varchar(255) DEFAULT NULL,
  `delivery_status_timestamp` datetime DEFAULT '0000-00-00 00:00:00',
  `bi_cookie` varchar(255) DEFAULT NULL,
  `bi_score` double NOT NULL DEFAULT '0',
  `bi_noticesleft` double NOT NULL DEFAULT '0',
  `bi_lastnotice` date NOT NULL DEFAULT '0000-00-00',
  `bi_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`listname`,`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Inscrits à des ML. Structure définie par mailman.';

-- --------------------------------------------------------

--
-- Structure de la table `postfix_alias`
--

CREATE TABLE `postfix_alias` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(128) NOT NULL DEFAULT '' COMMENT 'Boîte complète (de la forme asso-extension)',
  `destination` varchar(128) NOT NULL DEFAULT '' COMMENT 'Adresse complète de destination',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`,`destination`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Les inscrits à un alias. Structure dans la conf de postfix.' ;

-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 30. Sep 2015 um 00:30
-- Server Version: 5.5.44
-- PHP-Version: 5.4.41-0+deb7u1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `homecontrol`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `action_log`
--

CREATE TABLE IF NOT EXISTS `action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL,
  `zeit` int(30) NOT NULL,
  `request_dump` text NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `colors`
--

CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `farbwert` varchar(20) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `colors`
--

INSERT INTO `colors` (`id`, `name`, `farbwert`, `page_id`, `geaendert`) VALUES
(1, 'text', '#454545', 0, '2012-10-25 15:33:00'),
(2, 'link', '#9999bb', 0, '2015-01-01 23:15:22'),
(3, 'hover', '#39a0f8', 0, '2015-01-01 23:04:56'),
(4, 'titel', '#1976D2', 0, '2015-01-01 23:03:51'),
(5, 'menu', '#9999bb', 0, '2015-01-01 23:15:28'),
(6, 'background', '#efefef', 0, '2015-01-01 23:07:44'),
(7, 'panel_background', '#efefef', 0, '2014-10-26 01:10:14'),
(8, 'Tabelle_Hintergrund_1', '#e1e1e1', 0, '2014-10-26 01:11:23'),
(9, 'Tabelle_Hintergrund_2', '#d5d5d5', 0, '2014-10-26 01:09:42'),
(10, 'main_background', '#ffffff', 0, '2012-10-28 17:17:32'),
(11, 'button_background', '#cccccc', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dbcombos`
--

CREATE TABLE IF NOT EXISTS `dbcombos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tab_name` varchar(50) NOT NULL,
  `col_name` varchar(50) NOT NULL,
  `combo_tab` varchar(50) NOT NULL,
  `combo_code_col` varchar(50) NOT NULL,
  `combo_text_col` varchar(250) NOT NULL,
  `onlyinsert` enum('true','false') NOT NULL DEFAULT 'false',
  `combo_where` text NOT NULL,
  `combo_orderby` varchar(50) NOT NULL,
  `distinct_jn` enum('J','N') NOT NULL DEFAULT 'J',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `dbcombos`
--

INSERT INTO `dbcombos` (`id`, `tab_name`, `col_name`, `combo_tab`, `combo_code_col`, `combo_text_col`, `onlyinsert`, `combo_where`, `combo_orderby`, `distinct_jn`) VALUES
(1, 'geplant', 'status', 'geplant_status', 'tag', 'name', 'true', '', 'name', 'J'),
(3, 'koordinatenzuordnung', 'str_id', 'strassenschluessel', 'id', 'name', 'true', '', 'name', 'J'),
(4, 'stadt_angebot', 'ansprech', 'adressen', 'id', 'concat(name, '' '', strasse) as adr', 'true', 'ansprechpartner=''J''', '', 'J'),
(6, 'stadt_institution', 'adresse', 'adressen', 'id', 'CONCAT(name, '' - '', plz, '' '', strasse, '' '', hausnummer) as adresse', 'true', 'ansprechpartner=''N''', '', 'J'),
(7, 'links', 'topic', 'links', 'topic', 'topic', 'true', 'link is not null and descr is not null and link != ''-'' and descr != ''-''', 'topic', 'J'),
(9, 'menu', 'parent', 'menu', 'text', 'text', 'true', '', 'text', 'J'),
(10, 'stadt_kategorien', 'symbol', 'stadt_symbole', 'id', 'tooltip', 'true', '', 'tooltip', 'J'),
(11, 'stadt_institution', 'kategorie', 'stadt_kategorien', 'id', 'name', 'true', '', 'name', 'J'),
(12, 'user', 'status', 'userstatus', 'id', 'title', 'false', '', 'title', 'J'),
(13, 'testbericht', 'institution_id', 'stadt_institution i, adressen a', 'i.id', 'CONCAT(i.name, '' - '', a.strasse, '' '', a.hausnummer) AS adresse', 'false', 'i.adresse = a.id order by i.name', '', 'J'),
(14, 'stadt_angebot', 'institutionid', 'stadt_institution i, adressen a', 'i.id', 'CONCAT(i.name, '' - '', a.strasse, '' '', a.hausnummer) AS adresse', 'false', 'i.adresse = a.id ', '', 'J'),
(15, 'menu', 'status', 'userstatus', 'id', 'title', 'false', '', 'title', 'J'),
(16, 'stadt_angebot', 'kategorie', 'stadt_angebot_kategorie', 'id', 'name', 'false', '', 'name', 'J'),
(17, 'run_links', 'parent', 'menu', 'text', 'text', 'false', '', 'text', 'J'),
(18, 'run_links', 'prog_grp_id', 'programm_gruppen', 'id', 'name', 'false', '', 'name', 'J'),
(19, 'berechtigung', 'user_id', 'user', 'id', 'concat(Vorname, '' '',Nachname) as nme', 'false', 'Vorname != ''Developer'' and \r\nVorname != ''Superuser''', '', 'J'),
(20, 'berechtigung', 'user_grp_id', 'user_groups', 'id', 'name', 'false', '', 'name', 'J'),
(21, 'berechtigung', 'run_link_id', 'run_links', 'id', 'name', 'false', '', 'name', 'J'),
(22, 'berechtigung', 'prog_grp_id', 'programm_gruppen', 'id', 'name', 'false', '', 'name', 'J'),
(23, 'terminserie', 'monat', 'default_combo_values', 'code', 'value', 'false', 'combo_name = ''Monate''', 'value', 'J'),
(24, 'terminserie', 'jaehrlichwochentag', 'default_combo_values', 'code', 'value', 'false', 'combo_name = ''tage''', 'value', 'J'),
(25, 'user', 'user_group_id', 'user_groups', 'id', 'name', 'false', '', 'name', 'J'),
(26, 'adressen', 'ortsteil', 'ortsteile', 'id', 'name', 'false', 'plz in (select plz from adressen where id=#id#)', 'name', 'J'),
(27, 'kopftexte', 'runlink', 'run_links', 'name', 'name', 'false', '', 'name', 'J'),
(28, 'kopftexte', 'parent', 'run_links', 'parent', 'parent', 'false', '', 'parent', 'J'),
(29, 'adressen', 'strasse', 'strassenschluessel', 'name', 'name', 'false', 'plz = #plz#', '', 'J'),
(30, 'homecontrol_shortcut_items', 'shortcut_id', 'homecontrol_shortcut', 'id', 'name', 'false', '', 'name', 'J'),
(31, 'homecontrol_shortcut_items', 'config_id', 'homecontrol_config', 'id', 'name', 'false', '', 'name', 'J'),
(32, 'homecontrol_shortcut_items', 'zimmer_id', 'homecontrol_zimmer', 'id', 'name', 'false', '', 'name', 'J'),
(33, 'homecontrol_shortcut_items', 'etagen_id', ' homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(34, 'homecontrol_zimmer', 'etagen_id', 'homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(35, 'homecontrol_config', 'control_art', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J'),
(36, 'homecontrol_config', 'etage', 'homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(37, 'homecontrol_config', 'zimmer', 'homecontrol_zimmer', 'id', 'name', 'false', '', 'name', 'J'),
(38, 'homecontrol_shortcut_items', 'art_id', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J'),
(39, 'homecontrol_cron_items', 'shortcut_id', 'homecontrol_shortcut', 'id', 'name', 'false', '', 'name', 'J'),
(40, 'homecontrol_cron_items', 'config_id', 'homecontrol_config', 'id', 'name', 'false', '', 'name', 'J'),
(41, 'homecontrol_cron_items', 'zimmer_id', 'homecontrol_zimmer', 'id', 'name', 'false', '', 'name', 'J'),
(42, 'homecontrol_cron_items', 'etagen_id', 'homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(43, 'homecontrol_cron_items', 'art_id', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J'),
(44, 'homecontrol_sensor_items', 'sensor_id', 'homecontrol_sensor', 'id', 'name', 'false', '', 'name', 'J'),
(45, 'homecontrol_sensor_items', 'config_id', 'homecontrol_config', 'id', 'name', 'false', '', 'name', 'J'),
(46, 'homecontrol_sensor_items', 'zimmer_id', 'homecontrol_zimmer', 'id', 'name', 'false', '', 'name', 'J'),
(47, 'homecontrol_sensor_items', 'etagen_id', ' homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(48, 'homecontrol_sensor_items', 'art_id', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J'),
(49, 'homecontrol_zimmer', 'etage_id', 'homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(50, 'homecontrol_alarm_items', 'shortcut_id', 'homecontrol_alarm', 'id', 'name', 'false', '', 'name', 'J'),
(51, 'homecontrol_alarm_items', 'config_id', 'homecontrol_config', 'id', 'name', 'false', '', 'name', 'J'),
(52, 'homecontrol_alarm_items', 'zimmer_id', 'homecontrol_zimmer', 'id', 'name', 'false', '', 'name', 'J'),
(53, 'homecontrol_alarm_items', 'etagen_id', 'homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(54, 'homecontrol_alarm_items', 'art_id', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J'),
(55, 'homecontrol_regeln_items', 'shortcut_id', 'homecontrol_alarm', 'id', 'name', 'false', '', 'name', 'J'),
(56, 'homecontrol_regeln_items', 'config_id', 'homecontrol_config', 'id', 'name', 'false', '', 'name', 'J'),
(57, 'homecontrol_regeln_items', 'zimmer_id', 'homecontrol_zimmer', 'id', 'name', 'false', '', 'name', 'J'),
(58, 'homecontrol_regeln_items', 'etagen_id', 'homecontrol_etagen', 'id', 'name', 'false', '', 'name', 'J'),
(59, 'homecontrol_regeln_items', 'art_id', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `default_combo_values`
--

CREATE TABLE IF NOT EXISTS `default_combo_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combo_name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `default_combo_values`
--

INSERT INTO `default_combo_values` (`id`, `combo_name`, `code`, `value`) VALUES
(1, 'tage', '1', 'Montag'),
(2, 'tage', '2', 'Dienstag'),
(3, 'tage', '3', 'Mittwoch'),
(4, 'tage', '4', 'Donnerstag'),
(5, 'tage', '5', 'Freitag'),
(6, 'tage', '6', 'Samstag'),
(7, 'tage', '7', 'Sonntag'),
(8, 'Monate', '1', 'Januar'),
(9, 'Monate', '2', 'Februar'),
(10, 'Monate', '3', 'MÃ¤rz'),
(11, 'Monate', '4', 'April'),
(12, 'Monate', '5', 'Mai'),
(13, 'Monate', '6', 'Juni'),
(14, 'Monate', '7', 'Juli'),
(15, 'Monate', '8', 'August'),
(16, 'Monate', '9', 'September'),
(17, 'Monate', '10', 'Oktober'),
(18, 'Monate', '11', 'November'),
(19, 'Monate', '12', 'Dezember'),
(20, 'DatumTagzahl', '1', '1'),
(21, 'DatumTagzahl', '2', '2'),
(22, 'DatumTagzahl', '3', '3'),
(23, 'DatumTagzahl', '4', '4'),
(24, 'DatumTagzahl', '5', '5'),
(25, 'DatumTagzahl', '6', '6'),
(26, 'DatumTagzahl', '7', '7'),
(27, 'DatumTagzahl', '8', '8'),
(28, 'DatumTagzahl', '9', '9'),
(29, 'DatumTagzahl', '10', '10'),
(30, 'DatumTagzahl', '11', '11'),
(31, 'DatumTagzahl', '12', '12'),
(32, 'DatumTagzahl', '13', '13'),
(33, 'DatumTagzahl', '14', '14'),
(34, 'DatumTagzahl', '15', '15'),
(35, 'DatumTagzahl', '16', '16'),
(36, 'DatumTagzahl', '17', '17'),
(37, 'DatumTagzahl', '18', '18'),
(38, 'DatumTagzahl', '19', '19'),
(39, 'DatumTagzahl', '20', '20'),
(40, 'DatumTagzahl', '21', '21'),
(41, 'DatumTagzahl', '22', '22'),
(42, 'DatumTagzahl', '23', '23'),
(43, 'DatumTagzahl', '24', '24'),
(44, 'DatumTagzahl', '25', '25'),
(45, 'DatumTagzahl', '26', '26'),
(46, 'DatumTagzahl', '27', '27'),
(47, 'DatumTagzahl', '28', '28'),
(48, 'DatumTagzahl', '29', '29'),
(49, 'DatumTagzahl', '30', '30'),
(50, 'DatumTagzahl', '31', '31');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `default_pageconfig`
--

CREATE TABLE IF NOT EXISTS `default_pageconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `default_pageconfig`
--

INSERT INTO `default_pageconfig` (`id`, `name`, `value`, `page_id`) VALUES
(1, 'pagetitel', 'Meine Homepage', 0),
(2, 'pageowner', '', 0),
(3, 'background_pic', '', 0),
(4, 'banner_pic', '', 0),
(5, 'sessiontime', '0', 0),
(6, 'logging_aktiv', 'true', 0),
(7, 'debugoutput_aktiv', 'false', 0),
(10, 'classes_autoupdate', 'false', 0),
(11, 'pagedeveloper', 'Daniel Scheidler', 0),
(12, 'pagedesigner', 'Daniel Scheidler', 0),
(13, 'hauptmenu_button_image', 'pics/hauptmenu_button.jpg', 0),
(14, 'max_rowcount_for_dbtable', '50', 0),
(15, 'suchbegriffe', '', 0),
(16, 'NotifyTargetMail', 'd.scheidler@web.de', 0),
(17, 'KontaktformularTargetMail', 'd.scheidler@web.de', 0),
(18, 'changeMode', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fixtexte`
--

CREATE TABLE IF NOT EXISTS `fixtexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `form_insert_validation`
--

CREATE TABLE IF NOT EXISTS `form_insert_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chkVal` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chkVal` (`chkVal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_alarm`
--

CREATE TABLE IF NOT EXISTS `homecontrol_alarm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cam_trigger_jn` enum('J','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `foto_senden_jn` enum('J','N') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_alarmgeber_art`
--

CREATE TABLE IF NOT EXISTS `homecontrol_alarmgeber_art` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_alarmgeber_art`
--

INSERT INTO `homecontrol_alarmgeber_art` (`id`, `name`, `pic`, `geaendert`) VALUES
(1, 'Sirene', 'pics/Sirene.png', '2015-09-01 22:15:09'),
(2, 'Alarm-Licht', 'pics/Alarmlicht.png', '2015-09-01 22:18:06');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_alarm_geber`
--

CREATE TABLE IF NOT EXISTS `homecontrol_alarm_geber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `x` int(10) NOT NULL DEFAULT '-1',
  `y` int(10) NOT NULL DEFAULT '-1',
  `etage_id` int(11) NOT NULL,
  `zimmer_id` int(11) NOT NULL,
  `alarmgeber_art` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_alarm_items`
--

CREATE TABLE IF NOT EXISTS `homecontrol_alarm_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alarm_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_item_uk` (`alarm_id`,`config_id`,`zimmer_id`,`etagen_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_art`
--

CREATE TABLE IF NOT EXISTS `homecontrol_art` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `zweite_funkid_jn` set('J','N') NOT NULL DEFAULT 'N',
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_art`
--

INSERT INTO `homecontrol_art` (`id`, `name`, `zweite_funkid_jn`, `pic`, `geaendert`) VALUES
(1, 'Steckdose', 'N', 'pics/Steckdose.png', '2014-11-28 11:44:33'),
(2, 'Jalousie', 'N', 'pics/Jalousien.png', '2014-11-28 11:44:56'),
(3, 'Lampe', 'N', 'pics/Gluehbirne.png', '2014-11-28 11:45:17'),
(4, 'Heizung', 'N', 'pics/Heizung.png', '2014-11-28 11:45:27');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_condition`
--

CREATE TABLE IF NOT EXISTS `homecontrol_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_condition`
--

INSERT INTO `homecontrol_condition` (`id`, `name`, `value`) VALUES
(1, '<', '<'),
(3, '>', '>'),
(4, '<=', '<='),
(5, '>=', '>='),
(6, '=', '=');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_config`
--

CREATE TABLE IF NOT EXISTS `homecontrol_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `funk_id` int(3) NOT NULL,
  `funk_id2` int(3) DEFAULT NULL,
  `beschreibung` text NOT NULL,
  `control_art` int(11) NOT NULL DEFAULT '1',
  `etage` int(3) NOT NULL DEFAULT '0',
  `zimmer` int(11) DEFAULT NULL,
  `x` int(4) NOT NULL DEFAULT '0',
  `y` int(4) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


--
-- Tabellenstruktur für Tabelle `homecontrol_cron`
--

CREATE TABLE IF NOT EXISTS `homecontrol_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `beschreibung` text,
  `montag` enum('J','N') NOT NULL,
  `dienstag` enum('J','N') NOT NULL,
  `mittwoch` enum('J','N') NOT NULL,
  `donnerstag` enum('J','N') NOT NULL,
  `freitag` enum('J','N') NOT NULL,
  `samstag` enum('J','N') NOT NULL,
  `sonntag` enum('J','N') NOT NULL,
  `stunde` int(2) NOT NULL,
  `minute` int(2) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hc_cron_name_uk` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;



--
-- Stellvertreter-Struktur des Views `homecontrol_cronview`
--
CREATE TABLE IF NOT EXISTS `homecontrol_cronview` (
`wochentag` varchar(10)
,`tagnr` bigint(20)
,`id` int(11)
,`name` varchar(30)
,`beschreibung` text
,`montag` varchar(1)
,`dienstag` varchar(1)
,`mittwoch` varchar(1)
,`donnerstag` varchar(1)
,`freitag` varchar(1)
,`samstag` varchar(1)
,`sonntag` varchar(1)
,`stunde` int(11)
,`minute` int(11)
,`geaendert` timestamp
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_cron_items`
--

CREATE TABLE IF NOT EXISTS `homecontrol_cron_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_item_uk` (`cron_id`,`config_id`,`zimmer_id`,`etagen_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_cron_pause`
--

CREATE TABLE IF NOT EXISTS `homecontrol_cron_pause` (
  `cron_id` int(11) NOT NULL,
  `pause_time` int(30) NOT NULL,
  PRIMARY KEY (`cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_etagen`
--

CREATE TABLE IF NOT EXISTS `homecontrol_etagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_uk` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


--
-- Tabellenstruktur für Tabelle `homecontrol_modes`
--

CREATE TABLE IF NOT EXISTS `homecontrol_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `selectable` set('J','N') NOT NULL,
  `beschreibung` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_modes`
--

INSERT INTO `homecontrol_modes` (`id`, `name`, `selectable`, `beschreibung`) VALUES
(1, 'default', 'N', 'Standard-Modus (Einträge gelten für alle Modes)'),
(2, 'anwesend', 'J', 'Anwesenheits-Modus'),
(3, 'abwesend', 'J', 'Abwesenheits-Modus'),
(4, 'urlaub', 'J', 'Urlaubs-Modus');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_noframe`
--

CREATE TABLE IF NOT EXISTS `homecontrol_noframe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_regeln`
--

CREATE TABLE IF NOT EXISTS `homecontrol_regeln` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_regeln_items`
--

CREATE TABLE IF NOT EXISTS `homecontrol_regeln_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regel_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `regel_id` (`regel_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_sensor`
--

CREATE TABLE IF NOT EXISTS `homecontrol_sensor` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `beschreibung` text,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastSignal` int(30) NOT NULL,
  `lastValue` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_sensor_log`
--

CREATE TABLE IF NOT EXISTS `homecontrol_sensor_log` (
  `sensor_id` int(11) NOT NULL,
  `value` int(9) NOT NULL,
  `update_time` int(30) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sensor_id`,`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_sensor_log`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_shortcut`
--

CREATE TABLE IF NOT EXISTS `homecontrol_shortcut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `beschreibung` text NOT NULL,
  `show_shortcut` enum('J','N') NOT NULL DEFAULT 'J',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Stellvertreter-Struktur des Views `homecontrol_shortcutview`
--
CREATE TABLE IF NOT EXISTS `homecontrol_shortcutview` (
`id` varchar(23)
,`shortcut_id` int(11)
,`shortcut_name` varchar(30)
,`beschreibung` text
,`item_id` int(11)
,`art` int(11)
,`zimmer` int(11)
,`etage_id` int(11)
,`funkwahl` set('1','2')
,`on_off` set('on','off')
,`config_id` int(11)
,`name` varchar(30)
,`funk_id` int(3)
,`funk_id2` int(3)
,`x` int(4)
,`y` int(4)
,`pic` varchar(200)
,`geaendert` timestamp
);
-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_shortcut_items`
--

CREATE TABLE IF NOT EXISTS `homecontrol_shortcut_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortcut_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortcut_item_uk` (`shortcut_id`,`config_id`,`zimmer_id`,`etagen_id`),
  KEY `shortcut_id` (`shortcut_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_term`
--

CREATE TABLE IF NOT EXISTS `homecontrol_term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_id` int(11) NOT NULL,
  `trigger_subid` int(11) NOT NULL DEFAULT '0',
  `trigger_type` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `term_type` int(11) NOT NULL,
  `sensor_id` int(11) DEFAULT NULL,
  `min` int(2) DEFAULT NULL,
  `std` int(2) DEFAULT NULL,
  `value` int(9) DEFAULT NULL,
  `termcondition` varchar(50) NOT NULL,
  `status` set('J','N') DEFAULT NULL,
  `montag` set('J','N') NOT NULL DEFAULT 'N',
  `dienstag` set('J','N') NOT NULL DEFAULT 'N',
  `mittwoch` set('J','N') NOT NULL DEFAULT 'N',
  `donnerstag` set('J','N') NOT NULL DEFAULT 'N',
  `freitag` set('J','N') NOT NULL DEFAULT 'N',
  `samstag` set('J','N') NOT NULL DEFAULT 'N',
  `sonntag` set('J','N') NOT NULL DEFAULT 'N',
  `order_nr` int(5) NOT NULL,
  `and_or` set('and','or') NOT NULL DEFAULT 'and',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastSensorintervall` int(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `trigger_id` (`trigger_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_term_trigger_type`
--

CREATE TABLE IF NOT EXISTS `homecontrol_term_trigger_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_term_trigger_type`
--

INSERT INTO `homecontrol_term_trigger_type` (`id`, `name`) VALUES
(3, 'Alarm'),
(2, 'Cron'),
(1, 'Regel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_term_type`
--

CREATE TABLE IF NOT EXISTS `homecontrol_term_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `homecontrol_term_type`
--

INSERT INTO `homecontrol_term_type` (`id`, `name`) VALUES
(1, 'Sensorwert'),
(2, 'Sensor'),
(3, 'Zeit'),
(4, 'Wochentag');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_zimmer`
--

CREATE TABLE IF NOT EXISTS `homecontrol_zimmer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `etage_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_etage_uk` (`name`,`etage_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kopftexte`
--

CREATE TABLE IF NOT EXISTS `kopftexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runlink` varchar(250) NOT NULL,
  `text` text,
  `parent` varchar(50) DEFAULT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `runlink_name` (`runlink`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `kopftexte`
--

INSERT INTO `kopftexte` (`id`, `runlink`, `text`, `parent`, `geaendert`) VALUES
(1, 'start', '\r\n', 'Treffpunkt', '2010-02-20 16:15:19'),
(3, 'forum', 'Hier im Forum habt ihr die MÃ¶glichkeit alles nach Themen-Gruppiert zu besprechen.\r\n\r\nWenn euch Themengruppen fehlen sollten, wendet euch einfach an einen der Administratoren.\r\n\r\n', 'Treffpunkt', '2008-10-12 10:26:47'),
(4, 'todo', 'Hier seht ihr eine Ãœbersicht aller noch ausstehenden Ã„nderungen an der Seite.\r\n\r\nWenn euch auch noch etwas auffÃ¤llt, was falsch lÃ¤uft oder was an Informationen fehlt, tragt es doch einfach hier ein.\r\n\r\nDie Entwicklung wird sich schnellstmÃ¶glich damit befassen.\r\nWird der Vorschlag fÃ¼r sinnvoll angesehen, wird er auch so gut und so schnell es geht umgesetzt!\r\n\r\n', NULL, '2008-10-14 23:20:47'),
(5, 'test', 'testing', NULL, '0000-00-00 00:00:00'),
(6, 'kontakt', 'Wenn Sie uns eine Nachricht zukommen lassen mÃ¶chten, haben Sie mit diesem Formular die mÃ¶glichkeit uns eine Email schreiben.\r\nWir werden uns schnellstmÃ¶glich mit Ihnen in Verbindung setzen.\r\n', NULL, '0000-00-00 00:00:00'),
(9, 'bbUpload', 'In diesem Bereich kÃ¶nnt Ihr eure eigenen Bilder ins Bilderbuch einfÃ¼gen.\r\n\r\n[fett]1. rechtsklick "Add New Folder"  um ein neues Verzeichniss anzulegen.[/fett]\r\nDer Name dieses Verzeichnisses wird spÃ¤ter im Bilderbuch als Name der Bildergruppe angezeigt.\r\n\r\n[fett]2. das neue Verzeichniss auswÃ¤hlen und "Dateien hinzufÃ¼gen"[/fett]\r\n\r\n[fett]3. In der Vorschau die Bilder Ã¼berprÃ¼fen und ggf. in JPG oder PNG Konvertieren oder aus der Liste entfernen[/fett]\r\n\r\n[fett]4. Bilder "Hochladen"[/fett]\r\n\r\n[red][fett]Achtung![/fett] Ein spÃ¤teres auswÃ¤hlen der angelegten Kategorie ist nach dem Hochladen nicht mehr mÃ¶glich! Es kÃ¶nnen nachtrÃ¤glich somit keine Bilder mehr hinzugefÃ¼gt werden.[/red]\r\n\r\n', 'Bilder', '2009-03-16 23:19:25');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(100) DEFAULT NULL,
  `descr` longtext,
  `topic` varchar(50) DEFAULT NULL,
  `autor` varchar(50) NOT NULL DEFAULT '',
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `links`
--

INSERT INTO `links` (`id`, `link`, `descr`, `topic`, `autor`, `geaendert`) VALUES
(26, 'http://www.mozilla-europe.org/de/products/firefox/', 'Der preisgekrÃ¶nte Browser ist jetzt schneller, noch sicherer und komplett anpassbar an Ihr Online-Leben. \r\nDownloaden Sie Firefox jetzt (wenn Sie ihn nicht schon haben) und holen Sie das Beste aus dem Netz!', 'Download-Empfehlungen', 'Developer X', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` varchar(25) DEFAULT NULL,
  `User` varchar(30) NOT NULL DEFAULT '',
  `Ip` varchar(20) DEFAULT NULL,
  `Action` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lookupwerte`
--

CREATE TABLE IF NOT EXISTS `lookupwerte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tab_name` varchar(50) NOT NULL,
  `col_name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `text` varchar(50) NOT NULL,
  `validation_flag` varchar(50) NOT NULL,
  `sprache` varchar(2) NOT NULL DEFAULT 'de',
  `sortnr` int(5) NOT NULL DEFAULT '0',
  `default` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `lookupwerte`
--

INSERT INTO `lookupwerte` (`id`, `tab_name`, `col_name`, `code`, `text`, `validation_flag`, `sprache`, `sortnr`, `default`) VALUES
(1, 'terminserie', 'serienmuster', '1', 'TÃ¤glich', '', 'de', 0, 'Y'),
(2, 'terminserie', 'serienmuster', '2', 'WÃ¶chentlich', '', 'de', 0, 'N'),
(3, 'terminserie', 'serienmuster', '3', 'Monatlich', '', 'de', 0, 'N'),
(4, 'terminserie', 'serienmuster', '4', 'JÃ¤hrlich', '', 'de', 0, 'N'),
(6, 'homecontrol_shortcut_items', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N'),
(7, 'homecontrol_shortcut_items', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J'),
(8, 'homecontrol_cron_items', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N'),
(9, 'homecontrol_cron_items', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J'),
(10, 'homecontrol_sensor_items', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N'),
(11, 'homecontrol_sensor_items', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J'),
(12, 'homecontrol_shortcutview', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N'),
(13, 'homecontrol_shortcutview', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J'),
(14, 'homecontrol_alarm_items', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N'),
(15, 'homecontrol_alarm_items', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J'),
(16, 'homecontrol_regeln_items', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J'),
(17, 'homecontrol_regeln_items', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parent` varchar(30) NOT NULL,
  `link` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(5) DEFAULT NULL,
  `target` varchar(25) NOT NULL DEFAULT '_top',
  `tooltip` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sortnr` int(11) NOT NULL DEFAULT '9999',
  `name` varchar(50) NOT NULL DEFAULT 'main',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text` (`text`,`name`),
  KEY `parent_gruppe` (`parent`),
  KEY `sortnr` (`sortnr`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Daten für Tabelle `menu`
--

INSERT INTO `menu` (`id`, `text`, `parent`, `link`, `status`, `target`, `tooltip`, `sortnr`, `name`, `geaendert`) VALUES
(116, 'Shortcuts', 'Einstellungen', '?menuParent=Einstellungen&run=shortcutConfig', 'admin', '_top', 'Hier können die Schnellwahl Aktionen konfiguriert werden.', 85, 'Hauptmenue', '2015-09-28 21:37:15'),
(105, 'Login', '', '?run=login', NULL, '_top', 'Hier können Sie sich an- oder abmelden', 0, 'Fussmenue', '2014-07-20 18:26:34'),
(115, 'Geraete', 'Einstellungen', '?menuParent=Einstellungen&run=homeconfig', 'admin', '_top', 'Hier können die Geräte konfiguriert werden.', 5, 'Hauptmenue', '2015-08-23 17:05:47'),
(117, 'Shortcuts', '', '?menuParent=Shortcuts&run=shortcuts', '', '_top', 'Konfigurierte Modi mit einem Klick', 10, 'Mobilmenue', '2015-01-01 20:19:24'),
(133, 'Alarmanlage', 'Einstellungen', '?menuParent=Einstellungen&run=alarmConfig', 'admin', '_top', 'Hier können die Einstellungen für das Verhalten der Alarmanlage konfiguriert werden.', 90, 'Hauptmenue', '2015-08-23 17:05:47'),
(119, 'Zeitplan', 'Einstellungen', '?menuParent=Einstellungen&run=cronConfig', 'admin', '_top', 'Hier können die automatischen Jobs konfiguriert werden.', 50, 'Hauptmenue', '2015-09-28 21:35:34'),
(120, 'Sensoren', 'Einstellungen', '?menuParent=Einstellungen&run=sensorConfig', 'admin', '_top', 'Hier können die Aktionen für Sensoren konfiguriert werden.', 30, 'Hauptmenue', '2015-08-29 15:02:33'),
(121, 'Einstellungen', '', '?menuParent=Einstellungen&run=mainSettings', 'admin', '_top', 'Hier kann das gesamte System konfiguriert werden', 200, 'Kopfmenue', '2015-08-27 21:37:34'),
(122, 'Steuerung', '', '?menuParent=Steuerung&run=start', NULL, '_top', '', 10, 'Kopfmenue', '2015-03-16 06:15:44'),
(124, 'Steuerung', '', '?menuParent=Steuerung&run=start', '', '_top', 'Steuerung', 0, 'Mobilmenue', '2015-01-01 20:18:40'),
(126, 'Sensoren', '', '?menuParent=Sensoren&run=sensorList', '', '_top', 'Sensoren', 5, 'Mobilmenue', '2015-01-05 08:09:19'),
(127, 'Sensorwerte', '', '?menuParent=Sensoren&run=sensorList', '', '_top', 'Sensoren', 150, 'Kopfmenue', '2015-08-24 21:34:02'),
(128, 'Timeline', '', '?menuParent=Einstellungen&menuParent=Timeline&run=cronView', NULL, '_top', 'Hier werden die Events der nächsten 24 Stunden angezeigt und können für die nächste Ausführung pausiert werden.', 50, 'Kopfmenue', '2015-08-23 17:04:06'),
(129, 'Sensor-Log', '', '?menuParent=Sensor-Log&run=sensorlogView', NULL, '_top', 'Hier werden die Logdaten der Sensoren angezeigt', 70, 'Kopfmenue', '2014-11-11 21:09:42'),
(130, 'Gebaeude', 'Einstellungen', '?menuParent=Einstellungen&run=gebaeudeConfig', 'admin', '_top', 'Hier werden die Etagen und Raeume konfiguriert', 2, 'Hauptmenue', '2015-08-23 19:15:23'),
(131, 'Basis', 'Einstellungen', '?menuParent=Einstellungen&run=mainSettings', 'admin', '_top', 'Basis-Einstellungen', 1, 'Hauptmenue', '2015-08-25 21:06:41'),
(134, 'Cam', '', '?run=camPics', 'admin', '_top', 'Bewegungserkennung - Bilder', 9999, 'Kopfmenue', '2015-09-03 23:32:15'),
(135, 'Timeline', '', '?menuParent=Einstellungen&menuParent=Timeline&run=cronView', NULL, '_top', 'Hier werden die Events der nächsten 24 Stunden angezeigt und können für die nächste Ausführung pausiert werden.', 50, 'Mobilmenue', '2015-08-23 17:04:06'),
(136, 'Automatisierung', 'Einstellungen', '?menuParent=Einstellungen&run=automationConfig', 'admin', '_top', 'In diesem Bereich werden Automatisierungen in Abhängigkeit der Sensorwerte konfiguriert.', 85, 'Hauptmenue', '2015-09-28 21:35:34');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pageconfig`
--

CREATE TABLE IF NOT EXISTS `pageconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pageconfig`
--

INSERT INTO `pageconfig` (`id`, `name`, `value`, `page_id`, `geaendert`, `label`) VALUES
(1, 'pagetitel', 'Haussteuerung', 0, '2015-09-28 00:34:39', NULL),
(2, 'pageowner', 'SEITENINHABER', 0, '2013-01-08 02:32:07', NULL),
(3, 'background_pic', '', 0, '2008-09-18 15:19:00', NULL),
(4, 'banner_pic', 'pics/banner/13.jpg', 0, '2008-11-13 23:20:50', NULL),
(5, 'sessiontime', '0', 0, '0000-00-00 00:00:00', NULL),
(6, 'logging_aktiv', 'true', 0, '0000-00-00 00:00:00', NULL),
(7, 'debugoutput_aktiv', 'false', 0, '2008-10-25 11:37:21', NULL),
(11, 'classes_autoupdate', 'false', 0, '0000-00-00 00:00:00', NULL),
(12, 'pagedeveloper', 'Daniel Scheidler\r\n\r\n[fett]Email:[/fett]    support@smarthomeyourself.de\r\n', 0, '2013-01-08 02:39:44', NULL),
(13, 'pagedesigner', 'Daniel Scheidler', 0, '2013-01-08 02:40:04', NULL),
(15, 'background_repeat', 'repeat', 0, '0000-00-00 00:00:00', NULL),
(14, 'hauptmenu_button_image', 'pics/hauptmenu_button.jpg', 0, '0000-00-00 00:00:00', NULL),
(16, 'max_rowcount_for_dbtable', '25', 0, '2008-10-14 23:14:17', NULL),
(17, 'hauptmenu_button_image_hover', 'pics/hauptmenu_button_hover.jpg', 0, '2008-10-20 07:18:56', NULL),
(18, 'suchbegriffe', 'Haussteuerung, Arduino, Funk', 0, '2012-10-29 12:13:48', NULL),
(22, 'arduino_url', '192.168.1.12/rawCmd', 0, '2014-09-10 21:15:01', NULL),
(19, 'google_maps_API_key', '', 0, '2013-01-08 02:41:58', NULL),
(20, 'NotifyTargetMail', 'danielscheidler@gmail.com', 0, '2015-08-30 23:10:27', 'Benachrichtigungs-Email'),
(21, 'KontaktformularTargetMail', 'info@mail.de', 0, '2013-01-08 02:33:52', NULL),
(23, 'timezoneadditional', '2', 0, '0000-00-00 00:00:00', NULL),
(24, 'loginForSwitchNeed', 'N', 0, '2015-09-28 19:39:09', 'Login zum schalten benötigt'),
(26, 'abwesendSimulation', 'N', 0, '2015-08-26 22:46:27', 'Anwesenheits-Simulation'),
(27, 'abwesendMotion', 'N', 0, '2015-08-26 22:46:27', 'Kamera-Bewegungserkennung'),
(28, 'anwesendMotion', 'N', 0, '2015-08-26 22:46:27', 'Kamera-Bewegungserkennung'),
(29, 'sessionDauer', '6000', 0, '2015-08-29 17:06:18', 'Zeit bis zum erneuten Login in Sekunden'),
(30, 'motionDauer', '9', 0, '2015-09-28 00:37:09', 'Tage die Bewegungs-Bilder behalten'),
(31, 'sensorlogDauer', '60', 0, '2015-08-28 07:03:00', 'Tage die Sensor-Log Daten behalten'),
(32, 'abwesendAlarm', 'N', 0, '2015-08-26 23:23:41', NULL),
(33, 'currentMode', '2', 0, '2015-09-11 16:51:04', NULL),
(34, 'timelineDuration', '3', 0, '2015-09-28 00:33:59', NULL),
(35, 'loginForTimelinePauseNeed', 'N', 0, '2015-09-28 19:45:14', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `programm_gruppen`
--

CREATE TABLE IF NOT EXISTS `programm_gruppen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `text` varchar(250) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `programm_gruppen`
--

INSERT INTO `programm_gruppen` (`id`, `name`, `text`, `geaendert`) VALUES
(3, 'Bilder', 'Alles was zum Bilderbuch gehÃ¶rt', '0000-00-00 00:00:00'),
(4, 'Einstellungen', 'Einstellungsmasken und Administrative Links', '0000-00-00 00:00:00'),
(5, 'Allgemeines', 'Hier kommt alles rein, was generell zur VerfÃ¼gung steht', '0000-00-00 00:00:00'),
(6, 'Mein Profil', 'Alles rund ums Userprofil', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `public_vars`
--

CREATE TABLE IF NOT EXISTS `public_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gruppe` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `titel` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `sortnr` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `public_vars`
--

INSERT INTO `public_vars` (`id`, `gruppe`, `name`, `titel`, `text`, `sortnr`) VALUES
(1, 'texte', 'impressum', 'Inhalt des Onlineangebotes', 'Der Autor Ã¼bernimmt keinerlei GewÃ¤hr fÃ¼r die AktualitÃ¤t, Korrektheit, VollstÃ¤ndigkeit oder QualitÃ¤t der bereitgestellten Informationen. HaftungsansprÃ¼che gegen den Autor, welche sich auf SchÃ¤den materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollstÃ¤ndiger Informationen verursacht wurden, sind grundsÃ¤tzlich ausgeschlossen, sofern seitens des Autors kein nachweislich vorsÃ¤tzliches oder grob fahrlÃ¤ssiges Verschulden vorliegt. Alle Angebote sind freibleibend und unverbindlich. Der Autor behÃ¤lt es sich ausdrÃ¼cklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte AnkÃ¼ndigung zu verÃ¤ndern, zu ergÃ¤nzen, zu lÃ¶schen oder die VerÃ¶ffentlichung zeitweise oder endgÃ¼ltig einzustellen.', 1),
(2, 'texte', 'impressum', 'Verweise und Links', 'Bei direkten oder indirekten Verweisen auf fremde Webseiten ("Hyperlinks"), die auÃŸerhalb des Verantwortungsbereiches des Autors liegen, wÃ¼rde eine Haftungsverpflichtung ausschlieÃŸlich in dem Fall in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch mÃ¶glich und zumutbar wÃ¤re, die Nutzung im Falle rechtswidriger Inhalte zu verhindern. Der Autor erklÃ¤rt hiermit ausdrÃ¼cklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukÃ¼nftige Gestaltung, die Inhalte oder die Urheberschaft der gelinkten/verknÃ¼pften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrÃ¼cklich von allen Inhalten aller gelinkten /verknÃ¼pften Seiten, die nach der Linksetzung verÃ¤ndert wurden. Diese Feststellung gilt fÃ¼r alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie fÃ¼r FremdeintrÃ¤ge in vom Autor eingerichteten GÃ¤stebÃ¼chern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe mÃ¶glich sind. FÃ¼r illegale, fehlerhafte oder unvollstÃ¤ndige Inhalte und insbesondere fÃ¼r SchÃ¤den, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der Ã¼ber Links auf die jeweilige VerÃ¶ffentlichung lediglich verweist.\r\n', 2),
(3, 'texte', 'impressum', 'Urheber- und Kennzeichenrecht', 'Der Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurÃ¼ckzugreifen. Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschÃ¼tzten Marken- und Warenzeichen unterliegen uneingeschrÃ¤nkt den Bestimmungen des jeweils gÃ¼ltigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen EigentÃ¼mer. Allein aufgrund der bloÃŸen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschÃ¼tzt sind! Das Copyright fÃ¼r verÃ¶ffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. Eine VervielfÃ¤ltigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrÃ¼ckliche Zustimmung des Autors nicht gestattet.', 3),
(4, 'texte', 'impressum', 'Datenschutz', 'Sofern innerhalb des Internetangebotes die MÃ¶glichkeit zur Eingabe persÃ¶nlicher oder geschÃ¤ftlicher Daten (Kontodaten, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrÃ¼cklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch mÃ¶glich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet. Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben verÃ¶ffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur Ãœbersendung von nicht ausdrÃ¼cklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei VerstÃ¶ssen gegen dieses Verbot sind ausdrÃ¼cklich vorbehalten.', 4),
(5, 'texte', 'impressum', 'Rechtswirksamkeit', 'Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollstÃ¤ndig entsprechen sollten, bleiben die Ã¼brigen Teile des Dokumentes in ihrem Inhalt und ihrer GÃ¼ltigkeit davon unberÃ¼hrt.', 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `run_links`
--

CREATE TABLE IF NOT EXISTS `run_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `link` varchar(250) NOT NULL,
  `target` varchar(50) NOT NULL DEFAULT 'mainpage',
  `parent` varchar(50) NOT NULL,
  `prog_grp_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`name`,`parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `run_links`
--

INSERT INTO `run_links` (`id`, `name`, `link`, `target`, `parent`, `prog_grp_id`, `geaendert`) VALUES
(12, 'impressum', 'includes/Impressum.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(1, 'start', 'includes/Startseite.php', 'mainpage', '', 0, '2010-02-20 14:16:00'),
(19, 'changeMyProfile', 'includes/user/user_change.php', 'mainpage', '', 6, '2008-09-11 19:49:04'),
(20, 'doUserpicUpload', 'includes/user/userpic_upload2.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(21, 'userpicUpload', 'includes/user/userpic_upload.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(22, 'userRequestPw', 'includes/user/user_request_pw.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(24, 'showUserList', 'includes/user/user_liste.php', 'mainpage', '', 0, '2010-02-20 14:16:00'),
(29, 'showUserProfil', 'includes/user/show_userprofil.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(30, 'userListe', 'includes/user/user_liste.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(36, 'login', 'includes/Login.php', 'mainpage', '', 0, '2008-11-16 20:08:45'),
(41, 'redaktionsgruppe', 'includes/empty.php', 'mainpage', '', 1, '2010-02-20 14:16:00'),
(52, 'shortcutConfig', 'includes/ShortcutConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(50, 'imageUploaderPopup', 'includes/ImageUploaderPopup.php', 'mainpage', '', 0, '2009-06-27 07:01:28'),
(51, 'homeconfig', 'includes/ControlConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(53, 'shortcuts', 'includes/ShortcutSidebar.php', 'mainpage', '', 0, '2012-12-31 00:39:15'),
(2, 'mobile_start', 'mobile_includes/Startseite.php', 'mainpage', '', 0, '2010-02-20 14:16:00'),
(54, 'cronConfig', 'includes/CronConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(55, 'sensorConfig', 'includes/SensorConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(56, 'sensoren', 'includes/SensorenEdit.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(57, 'sensorList', 'includes/Sensoren.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(58, 'cronView', 'includes/CronView.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(59, 'sensorlogView', 'includes/SensorLogViewer.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(60, 'gebaeudeConfig', 'includes/GebaeudeConfig.php', 'mainpage', '', 0, '2015-08-23 16:58:17'),
(61, 'mainSettings', 'includes/MainSettings.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(62, 'network', 'includes/NetworkConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(63, 'alarmConfig', 'includes/AlarmConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(64, 'camPics', 'includes/CamPics.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(66, 'automationConfig', 'includes/AutomationConfig.php', 'mainpage', '', 0, '2010-02-20 14:16:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `site-enter`
--

CREATE TABLE IF NOT EXISTS `site-enter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `site-enter`
--

INSERT INTO `site-enter` (`id`, `name`, `value`) VALUES
(1, 'Box in', '0'),
(2, 'Box Out', '1'),
(3, 'Circle in', '2'),
(4, 'Circle out', '3'),
(5, 'Wipe up', '4'),
(6, 'Wipe down', '5'),
(7, 'Wipe right', '6'),
(8, 'Wipe left', '7'),
(9, 'Vertical Blinds', '8'),
(10, 'Horizontal Blinds', '9'),
(11, 'Checkerboard across', '10'),
(12, 'Checkerboard down', '11'),
(13, 'Random Disolve', '12'),
(14, 'Split vertical in', '13'),
(15, 'Split vertical out', '14'),
(16, 'Split horizontal in', '15'),
(17, 'Split horizontal out', '16'),
(18, 'Strips left down', '17'),
(19, 'Strips left up', '18'),
(20, 'Strips right down', '19'),
(21, 'Strips right up', '20'),
(22, 'Random bars horizontal', '21'),
(23, 'Random bars vertical', '22'),
(24, 'Random', '23');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `smileys`
--

CREATE TABLE IF NOT EXISTS `smileys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Title` (`title`),
  UNIQUE KEY `Link` (`link`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `smileys`
--

INSERT INTO `smileys` (`id`, `title`, `link`) VALUES
(1, ':-)', 'pics/smileys/grins.gif'),
(4, ':-P', 'pics/smileys/baeh.gif'),
(11, 'cry', 'pics/smileys/crying.gif'),
(13, 'lol', 'pics/smileys/biglaugh.gif'),
(16, ':-@', 'pics/smileys/motz.gif'),
(17, ':-O', 'pics/smileys/confused.gif'),
(20, ':-D', 'pics/smileys/auslach.gif'),
(26, 'rofl', 'pics/smileys/rofl.gif');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(15) NOT NULL DEFAULT '',
  `html` varchar(150) NOT NULL DEFAULT '',
  `btn` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `tag1` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tags`
--

INSERT INTO `tags` (`id`, `tag`, `html`, `btn`) VALUES
(1, 'cybi', '<a href=''http://www.cyborgone.de'' target=''cybi''><img src=''http://cyborgone.de/pics/banner13.gif'' width=''200'' border=''0''></a>', 'n'),
(2, 'fett', '<b>', 'J'),
(3, '/fett', '</b>', 'J'),
(4, 'unter', '<u>', 'J'),
(5, '/unter', '</u>', 'J'),
(6, 'normal', '<font size=''2''>', 'J'),
(7, '/normal', '</font>', 'J'),
(8, 'klein', '<font size=''1''>', 'J'),
(9, '/klein', '</font>', 'J'),
(10, 'mittel', '<font size=''3''>', 'J'),
(11, '/mittel', '</font>', 'J'),
(12, 'blue', '<font color=''blue''>', 'J'),
(13, 'red', '<font color=''red''>', 'J'),
(14, 'green', '<font color=''green''>', 'J'),
(15, 'gray', '<font color=''gray''>', 'J'),
(16, '/gray', '</font>', 'J'),
(17, '/red', '</font>', 'J'),
(18, '/blue', '</font>', 'J'),
(19, '/green', '</font>', 'J'),
(20, 'quote', '<table border=''1'' cellpadding=''0'' cellspacing=''0''><tr><td class=''zitat''><i>', 'N'),
(21, 'hr', '<hr>', 'J'),
(22, '/quote', '</i></td></tr></table>', 'N'),
(23, 'changed', '<br><br><i><u><b>GeÃ¤ndert:', 'N'),
(24, '/changed', '</b></u></i>', 'N'),
(25, 'bild_500', '<img src=''', 'J'),
(26, '/bild_500', ''' width=''500''>', 'J'),
(28, 'bild_150', '<img src=''', 'J'),
(29, '/bild_150', ''' width=''150''>', 'J'),
(30, 'code', '<textarea cols=''70'' rows=''10'' readonly>', 'J'),
(31, '/code', '</textarea>', 'N'),
(32, 'yellow', '<font color=''yellow''>', 'N'),
(33, '/yellow', '</font>', 'N'),
(34, 'groÃŸ', '<font size=''4''>', 'J'),
(35, '/groÃŸ', '</font>', 'J'),
(36, 'mitte', '<center>', NULL),
(37, '/mitte', '</center>', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `update_log`
--

CREATE TABLE IF NOT EXISTS `update_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `descr` text NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Vorname` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Nachname` varchar(50) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Name` varchar(100) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Geburtstag` date NOT NULL DEFAULT '0000-00-00',
  `Strasse` varchar(50) CHARACTER SET latin1 DEFAULT '-',
  `Plz` varchar(50) CHARACTER SET latin1 DEFAULT '-',
  `Ort` varchar(50) CHARACTER SET latin1 DEFAULT '-',
  `Email` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `Telefon` varchar(50) CHARACTER SET latin1 DEFAULT '-',
  `Fax` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Handy` varchar(50) CHARACTER SET latin1 DEFAULT '-',
  `Icq` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `Aim` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `Homepage` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `User` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `Pw` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Nation` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `Status` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT 'waitForActivate',
  `user_group_id` int(11) NOT NULL DEFAULT '1',
  `Newsletter` enum('true','false') CHARACTER SET latin1 DEFAULT 'true',
  `Signatur` text CHARACTER SET latin1,
  `Lastlogin` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `Posts` int(10) DEFAULT '0',
  `Beschreibung` text CHARACTER SET latin1,
  `pic` varchar(150) CHARACTER SET latin1 NOT NULL DEFAULT 'unknown.jpg',
  `pnnotify` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'Y',
  `autoforumnotify` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'Y',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `emailJN` enum('J','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `icqJN` enum('J','N') CHARACTER SET latin1 NOT NULL DEFAULT 'J',
  `telefonJN` enum('J','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `Level` double NOT NULL,
  `EP` double NOT NULL,
  `Gold` double NOT NULL,
  `Holz` double NOT NULL,
  `Erz` double NOT NULL,
  `Felsen` double NOT NULL,
  `Wasser` double NOT NULL,
  `Nahrung` double NOT NULL,
  `aktiv` set('J','N') CHARACTER SET latin1 NOT NULL DEFAULT 'N',
  `activationString` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `angelegt` date NOT NULL COMMENT 'timestamp angelegt',
  `clan_id` int(11) DEFAULT NULL,
  `rasse_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `User` (`User`),
  KEY `Name` (`Name`(8))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userstatus`
--

CREATE TABLE IF NOT EXISTS `userstatus` (
  `id` varchar(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `userstatus`
--

INSERT INTO `userstatus` (`id`, `title`) VALUES
('gast', 'Gast'),
('user', 'Hauptbenutzer'),
('admin', 'Administrator');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `beschreibung` varchar(250) NOT NULL,
  `pic` varchar(150) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur des Views `homecontrol_cronview`
--
DROP TABLE IF EXISTS `homecontrol_cronview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `homecontrol_cronview` AS select 'Montag' AS `wochentag`,1 AS `tagnr`,`hc`.`id` AS `id`,`hc`.`name` AS `name`,`hc`.`beschreibung` AS `beschreibung`,`hc`.`montag` AS `montag`,`hc`.`dienstag` AS `dienstag`,`hc`.`mittwoch` AS `mittwoch`,`hc`.`donnerstag` AS `donnerstag`,`hc`.`freitag` AS `freitag`,`hc`.`samstag` AS `samstag`,`hc`.`sonntag` AS `sonntag`,`hc`.`stunde` AS `stunde`,`hc`.`minute` AS `minute`,`hc`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc` where (`hc`.`montag` = 'J') union select 'Dienstag' AS `My_exp_Dienstag`,2 AS `2`,`hc1`.`id` AS `id`,`hc1`.`name` AS `name`,`hc1`.`beschreibung` AS `beschreibung`,`hc1`.`montag` AS `montag`,`hc1`.`dienstag` AS `dienstag`,`hc1`.`mittwoch` AS `mittwoch`,`hc1`.`donnerstag` AS `donnerstag`,`hc1`.`freitag` AS `freitag`,`hc1`.`samstag` AS `samstag`,`hc1`.`sonntag` AS `sonntag`,`hc1`.`stunde` AS `stunde`,`hc1`.`minute` AS `minute`,`hc1`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc1` where (`hc1`.`dienstag` = 'J') union select 'Mittwoch' AS `My_exp_Mittwoch`,3 AS `3`,`hc2`.`id` AS `id`,`hc2`.`name` AS `name`,`hc2`.`beschreibung` AS `beschreibung`,`hc2`.`montag` AS `montag`,`hc2`.`dienstag` AS `dienstag`,`hc2`.`mittwoch` AS `mittwoch`,`hc2`.`donnerstag` AS `donnerstag`,`hc2`.`freitag` AS `freitag`,`hc2`.`samstag` AS `samstag`,`hc2`.`sonntag` AS `sonntag`,`hc2`.`stunde` AS `stunde`,`hc2`.`minute` AS `minute`,`hc2`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc2` where (`hc2`.`mittwoch` = 'J') union select 'Donnerstag' AS `My_exp_Donnerstag`,4 AS `4`,`hc3`.`id` AS `id`,`hc3`.`name` AS `name`,`hc3`.`beschreibung` AS `beschreibung`,`hc3`.`montag` AS `montag`,`hc3`.`dienstag` AS `dienstag`,`hc3`.`mittwoch` AS `mittwoch`,`hc3`.`donnerstag` AS `donnerstag`,`hc3`.`freitag` AS `freitag`,`hc3`.`samstag` AS `samstag`,`hc3`.`sonntag` AS `sonntag`,`hc3`.`stunde` AS `stunde`,`hc3`.`minute` AS `minute`,`hc3`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc3` where (`hc3`.`donnerstag` = 'J') union select 'Freitag' AS `My_exp_Freitag`,5 AS `5`,`hc4`.`id` AS `id`,`hc4`.`name` AS `name`,`hc4`.`beschreibung` AS `beschreibung`,`hc4`.`montag` AS `montag`,`hc4`.`dienstag` AS `dienstag`,`hc4`.`mittwoch` AS `mittwoch`,`hc4`.`donnerstag` AS `donnerstag`,`hc4`.`freitag` AS `freitag`,`hc4`.`samstag` AS `samstag`,`hc4`.`sonntag` AS `sonntag`,`hc4`.`stunde` AS `stunde`,`hc4`.`minute` AS `minute`,`hc4`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc4` where (`hc4`.`freitag` = 'J') union select 'Samstag' AS `My_exp_Samstag`,6 AS `6`,`hc5`.`id` AS `id`,`hc5`.`name` AS `name`,`hc5`.`beschreibung` AS `beschreibung`,`hc5`.`montag` AS `montag`,`hc5`.`dienstag` AS `dienstag`,`hc5`.`mittwoch` AS `mittwoch`,`hc5`.`donnerstag` AS `donnerstag`,`hc5`.`freitag` AS `freitag`,`hc5`.`samstag` AS `samstag`,`hc5`.`sonntag` AS `sonntag`,`hc5`.`stunde` AS `stunde`,`hc5`.`minute` AS `minute`,`hc5`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc5` where (`hc5`.`samstag` = 'J') union select 'Sonntag' AS `My_exp_Sonntag`,0 AS `0`,`hc6`.`id` AS `id`,`hc6`.`name` AS `name`,`hc6`.`beschreibung` AS `beschreibung`,`hc6`.`montag` AS `montag`,`hc6`.`dienstag` AS `dienstag`,`hc6`.`mittwoch` AS `mittwoch`,`hc6`.`donnerstag` AS `donnerstag`,`hc6`.`freitag` AS `freitag`,`hc6`.`samstag` AS `samstag`,`hc6`.`sonntag` AS `sonntag`,`hc6`.`stunde` AS `stunde`,`hc6`.`minute` AS `minute`,`hc6`.`geaendert` AS `geaendert` from `homecontrol_cron` `hc6` where (`hc6`.`sonntag` = 'J');

-- --------------------------------------------------------

--
-- Struktur des Views `homecontrol_shortcutview`
--
DROP TABLE IF EXISTS `homecontrol_shortcutview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `homecontrol_shortcutview` AS select concat(`s`.`id`,'-',`c`.`id`) AS `id`,`s`.`id` AS `shortcut_id`,`s`.`name` AS `shortcut_name`,`s`.`beschreibung` AS `beschreibung`,`i`.`id` AS `item_id`,`i`.`art_id` AS `art`,`c`.`zimmer` AS `zimmer`,`z`.`etage_id` AS `etage_id`,`i`.`funkwahl` AS `funkwahl`,`i`.`on_off` AS `on_off`,`c`.`id` AS `config_id`,`c`.`name` AS `name`,`c`.`funk_id` AS `funk_id`,`c`.`funk_id2` AS `funk_id2`,`c`.`x` AS `x`,`c`.`y` AS `y`,`a`.`pic` AS `pic`,`c`.`geaendert` AS `geaendert` from ((`homecontrol_shortcut` `s` join `homecontrol_shortcut_items` `i`) join ((`homecontrol_config` `c` left join `homecontrol_zimmer` `z` on((`z`.`id` = `c`.`zimmer`))) left join `homecontrol_art` `a` on((`c`.`control_art` = `a`.`id`)))) where ((`i`.`shortcut_id` = `s`.`id`) and ((`c`.`id` = `i`.`config_id`) or isnull(`i`.`config_id`)) and ((`c`.`control_art` = `i`.`art_id`) or isnull(`i`.`art_id`)) and ((`c`.`zimmer` = `i`.`zimmer_id`) or isnull(`i`.`zimmer_id`)) and ((`c`.`etage` = `i`.`etagen_id`) or isnull(`i`.`etagen_id`)) and ((isnull(`i`.`config_id`) and (`i`.`zimmer_id` is not null) and (not(exists(select 'X' from `homecontrol_shortcut_items` `iZ` where ((`iZ`.`shortcut_id` = `s`.`id`) and (`iZ`.`config_id` = `c`.`id`)))))) or (`i`.`config_id` is not null) or isnull(`i`.`zimmer_id`)) and ((isnull(`i`.`config_id`) and (`i`.`etagen_id` is not null) and (not(exists(select 'X' from `homecontrol_shortcut_items` `iZ` where ((`iZ`.`shortcut_id` = `s`.`id`) and ((`iZ`.`config_id` = `c`.`id`) or (`iZ`.`zimmer_id` = `i`.`zimmer_id`))))))) or (`i`.`config_id` is not null) or (`i`.`zimmer_id` is not null) or isnull(`i`.`etagen_id`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

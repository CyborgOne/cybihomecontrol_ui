-- phpMyAdmin SQL Dump
-- version 3.3.5
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 08. Januar 2013 um 09:47
-- Server Version: 5.1.36
-- PHP-Version: 5.3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `action_log`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `colors`
--

INSERT INTO `colors` (`id`, `name`, `farbwert`, `page_id`, `geaendert`) VALUES
(1, 'text', '#454545', 0, '2012-10-25 15:33:00'),
(2, 'link', '#88bb88', 0, '2012-10-25 15:33:14'),
(3, 'hover', '#4444bb', 0, '2012-10-25 15:30:11'),
(4, 'titel', '#88bbff', 0, '2012-10-25 15:28:51'),
(5, 'menu', '#bbbbdd', 0, '2012-10-25 15:32:14'),
(6, 'background', '#bbbbdd', 0, '2012-10-28 17:17:32'),
(7, 'panel_background', '#eeeeee', 0, '2010-02-20 16:41:12'),
(8, 'Tabelle_Hintergrund_1', '#f5f5f5', 0, '2008-11-23 14:29:59'),
(9, 'Tabelle_Hintergrund_2', '#dddddd', 0, '2010-02-20 16:41:40');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

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
(38, 'homecontrol_shortcut_items', 'art_id', 'homecontrol_art', 'id', 'name', 'false', '', 'name', 'J');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

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
(10, 'Monate', '3', 'März'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

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
(17, 'KontaktformularTargetMail', 'd.scheidler@web.de', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `fixtexte`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `form_insert_validation`
--

CREATE TABLE IF NOT EXISTS `form_insert_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chkVal` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chkVal` (`chkVal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `form_insert_validation`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `homecontrol_art`
--

INSERT INTO `homecontrol_art` (`id`, `name`, `zweite_funkid_jn`, `pic`, `geaendert`) VALUES
(1, 'Steckdose', 'N', 'pics/homecontrol/steckdose_100.jpg', '0000-00-00 00:00:00'),
(2, 'Jalousie', 'N', 'pics/homecontrol/jalousien_100.jpg', '0000-00-00 00:00:00'),
(3, 'Lampe', 'N', 'pics/homecontrol/gluehbirne_100.jpg', '0000-00-00 00:00:00'),
(4, 'Heizung', 'N', 'pics/homecontrol/heizung_100.jpg', '0000-00-00 00:00:00');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `homecontrol_config`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `homecontrol_etagen`
--

INSERT INTO `homecontrol_etagen` (`id`, `name`, `pic`, `geaendert`) VALUES
(1, 'Keller', 'pics/raumplan_keller.jpg', '0000-00-00 00:00:00'),
(2, 'EG', 'pics/raumplan_eg.png', '2012-10-29 00:36:19'),
(3, '1. OG', 'pics/raumplan_1og.png', '2012-10-29 00:36:19'),
(4, 'Dachgeschoss', 'pics/raumplan_2og.jpg', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_shortcut`
--

CREATE TABLE IF NOT EXISTS `homecontrol_shortcut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `beschreibung` text NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `homecontrol_shortcut`
--


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
  UNIQUE KEY `shortcut_item_uk` (`shortcut_id`,`config_id`,`zimmer_id`,`etagen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `homecontrol_shortcut_items`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homecontrol_zimmer`
--

CREATE TABLE IF NOT EXISTS `homecontrol_zimmer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `etage_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_etage_uk` (`name`,`etage_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `homecontrol_zimmer`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `kopftexte`
--

INSERT INTO `kopftexte` (`id`, `runlink`, `text`, `parent`, `geaendert`) VALUES
(1, 'start', '\r\n', 'Treffpunkt', '2010-02-20 16:15:19'),
(3, 'forum', 'Hier im Forum habt ihr die Möglichkeit alles nach Themen-Gruppiert zu besprechen.\r\n\r\nWenn euch Themengruppen fehlen sollten, wendet euch einfach an einen der Administratoren.\r\n\r\n', 'Treffpunkt', '2008-10-12 10:26:47'),
(4, 'todo', 'Hier seht ihr eine Übersicht aller noch ausstehenden Änderungen an der Seite.\r\n\r\nWenn euch auch noch etwas auffällt, was falsch läuft oder was an Informationen fehlt, tragt es doch einfach hier ein.\r\n\r\nDie Entwicklung wird sich schnellstmöglich damit befassen.\r\nWird der Vorschlag für sinnvoll angesehen, wird er auch so gut und so schnell es geht umgesetzt!\r\n\r\n', NULL, '2008-10-14 23:20:47'),
(5, 'test', 'testing', NULL, '0000-00-00 00:00:00'),
(6, 'kontakt', 'Wenn Sie uns eine Nachricht zukommen lassen möchten, haben Sie mit diesem Formular die möglichkeit uns eine Email schreiben.\r\nWir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.\r\n', NULL, '0000-00-00 00:00:00'),
(9, 'bbUpload', 'In diesem Bereich könnt Ihr eure eigenen Bilder ins Bilderbuch einfügen.\r\n\r\n[fett]1. rechtsklick "Add New Folder"  um ein neues Verzeichniss anzulegen.[/fett]\r\nDer Name dieses Verzeichnisses wird später im Bilderbuch als Name der Bildergruppe angezeigt.\r\n\r\n[fett]2. das neue Verzeichniss auswählen und "Dateien hinzufügen"[/fett]\r\n\r\n[fett]3. In der Vorschau die Bilder überprüfen und ggf. in JPG oder PNG Konvertieren oder aus der Liste entfernen[/fett]\r\n\r\n[fett]4. Bilder "Hochladen"[/fett]\r\n\r\n[red][fett]Achtung![/fett] Ein späteres auswählen der angelegten Kategorie ist nach dem Hochladen nicht mehr möglich! Es können nachträglich somit keine Bilder mehr hinzugefügt werden.[/red]\r\n\r\n', 'Bilder', '2009-03-16 23:19:25');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Daten für Tabelle `links`
--

INSERT INTO `links` (`id`, `link`, `descr`, `topic`, `autor`, `geaendert`) VALUES
(26, 'http://www.mozilla-europe.org/de/products/firefox/', 'Der preisgekrönte Browser ist jetzt schneller, noch sicherer und komplett anpassbar an Ihr Online-Leben. \r\nDownloaden Sie Firefox jetzt (wenn Sie ihn nicht schon haben) und holen Sie das Beste aus dem Netz!', 'Download-Empfehlungen', 'Developer X', '0000-00-00 00:00:00');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1507 ;

--
-- Daten für Tabelle `log`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `lookupwerte`
--

INSERT INTO `lookupwerte` (`id`, `tab_name`, `col_name`, `code`, `text`, `validation_flag`, `sprache`, `sortnr`, `default`) VALUES
(1, 'terminserie', 'serienmuster', '1', 'Täglich', '', 'de', 0, 'Y'),
(2, 'terminserie', 'serienmuster', '2', 'Wöchentlich', '', 'de', 0, 'N'),
(3, 'terminserie', 'serienmuster', '3', 'Monatlich', '', 'de', 0, 'N'),
(4, 'terminserie', 'serienmuster', '4', 'Jährlich', '', 'de', 0, 'N'),
(6, 'homecontrol_shortcut_items', 'on_off', 'on', 'Einschalten', '', 'de', 0, 'N'),
(7, 'homecontrol_shortcut_items', 'on_off', 'off', 'Ausschalten', '', 'de', 0, 'J');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(30) NOT NULL DEFAULT '',
  `parent` varchar(30) NOT NULL,
  `link` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(5) DEFAULT NULL,
  `target` varchar(25) NOT NULL DEFAULT '_top',
  `tooltip` text NOT NULL,
  `sortnr` int(11) NOT NULL DEFAULT '9999',
  `name` varchar(50) NOT NULL DEFAULT 'main',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text` (`text`,`name`),
  KEY `parent_gruppe` (`parent`),
  KEY `sortnr` (`sortnr`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=119 ;

--
-- Daten für Tabelle `menu`
--

INSERT INTO `menu` (`id`, `text`, `parent`, `link`, `status`, `target`, `tooltip`, `sortnr`, `name`, `geaendert`) VALUES
(1, 'Haus-Steuerung', '', '?run=start', '', '_top', 'Hier findet ihr die neuesten Neuigkeiten', 0, 'Hauptmenue', '2012-10-25 09:47:57'),
(116, 'Shortcut Einstellung', '', '?run=shortcutConfig', NULL, '_top', 'Hier können die Schnellwahl Aktionen konfiguriert werden.', 50, 'Hauptmenue', '2012-10-30 01:35:12'),
(105, 'Login', '', '?run=login', NULL, '_top', 'Hier können Sie sich an- oder abmelden', 0, 'Fussmenue', '2012-10-24 11:08:12'),
(115, 'Konfiguration', '', '?run=homeconfig', NULL, '_top', 'Hier können die Geräte konfiguriert werden.', 25, 'Hauptmenue', '2012-10-29 12:59:45'),
(117, 'Shortcuts', '', '?run=shortcuts', '', '_top', 'Konfigurierte Modi mit einem Klick', 10, 'Mobilmenue', '2012-12-31 03:13:46'),
(118, 'Haus-Steuerung', '', '?run=start', '', '_top', 'Hier findet ihr die neuesten Neuigkeiten', 0, 'Mobilmenue', '2012-10-25 09:47:57');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Daten für Tabelle `pageconfig`
--

INSERT INTO `pageconfig` (`id`, `name`, `value`, `page_id`, `geaendert`) VALUES
(1, 'pagetitel', 'Haussteuerung', 0, '2012-10-29 12:12:00'),
(2, 'pageowner', 'SEITENINHABER', 0, '2013-01-08 02:32:07'),
(3, 'background_pic', '', 0, '2008-09-18 15:19:00'),
(4, 'banner_pic', 'pics/banner/13.jpg', 0, '2008-11-13 23:20:50'),
(5, 'sessiontime', '0', 0, '0000-00-00 00:00:00'),
(6, 'logging_aktiv', 'true', 0, '0000-00-00 00:00:00'),
(7, 'debugoutput_aktiv', 'false', 0, '2008-10-25 11:37:21'),
(11, 'classes_autoupdate', 'false', 0, '0000-00-00 00:00:00'),
(12, 'pagedeveloper', 'Daniel Scheidler\r\n\r\n[fett]Email:[/fett]    DanielScheidler@gmail.com\r\n', 0, '2013-01-08 02:39:44'),
(13, 'pagedesigner', 'Daniel Scheidler', 0, '2013-01-08 02:40:04'),
(15, 'background_repeat', 'repeat', 0, '0000-00-00 00:00:00'),
(14, 'hauptmenu_button_image', 'pics/hauptmenu_button.jpg', 0, '0000-00-00 00:00:00'),
(16, 'max_rowcount_for_dbtable', '25', 0, '2008-10-14 23:14:17'),
(17, 'hauptmenu_button_image_hover', 'pics/hauptmenu_button_hover.jpg', 0, '2008-10-20 07:18:56'),
(18, 'suchbegriffe', 'Haussteuerung, Arduino, Funk', 0, '2012-10-29 12:13:48'),
(22, 'arduino_url', '192.168.1.15/index.html', 0, '2012-11-11 16:37:42'),
(19, 'google_maps_API_key', '', 0, '2013-01-08 02:41:58'),
(20, 'NotifyTargetMail', 'test@mail.de', 0, '2013-01-08 02:33:39'),
(21, 'KontaktformularTargetMail', 'test@mail.de', 0, '2013-01-08 02:33:52');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `programm_gruppen`
--

INSERT INTO `programm_gruppen` (`id`, `name`, `text`, `geaendert`) VALUES
(3, 'Bilder', 'Alles was zum Bilderbuch gehört', '0000-00-00 00:00:00'),
(4, 'Einstellungen', 'Einstellungsmasken und Administrative Links', '0000-00-00 00:00:00'),
(5, 'Allgemeines', 'Hier kommt alles rein, was generell zur Verfügung steht', '0000-00-00 00:00:00'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `public_vars`
--

INSERT INTO `public_vars` (`id`, `gruppe`, `name`, `titel`, `text`, `sortnr`) VALUES
(1, 'texte', 'impressum', 'Inhalt des Onlineangebotes', 'Der Autor übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche gegen den Autor, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen, sofern seitens des Autors kein nachweislich vorsätzliches oder grob fahrlässiges Verschulden vorliegt. Alle Angebote sind freibleibend und unverbindlich. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.', 1),
(2, 'texte', 'impressum', 'Verweise und Links', 'Bei direkten oder indirekten Verweisen auf fremde Webseiten ("Hyperlinks"), die außerhalb des Verantwortungsbereiches des Autors liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern. Der Autor erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige Gestaltung, die Inhalte oder die Urheberschaft der gelinkten/verknüpften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller gelinkten /verknüpften Seiten, die nach der Linksetzung verändert wurden. Diese Feststellung gilt für alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie für Fremdeinträge in vom Autor eingerichteten Gästebüchern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe möglich sind. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.\r\n', 2),
(3, 'texte', 'impressum', 'Urheber- und Kennzeichenrecht', 'Der Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen. Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschützten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind! Das Copyright für veröffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Autors nicht gestattet.', 3),
(4, 'texte', 'impressum', 'Datenschutz', 'Sofern innerhalb des Internetangebotes die Möglichkeit zur Eingabe persönlicher oder geschäftlicher Daten (Kontodaten, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrücklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch möglich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet. Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben veröffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur Übersendung von nicht ausdrücklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei Verstössen gegen dieses Verbot sind ausdrücklich vorbehalten.', 4),
(5, 'texte', 'impressum', 'Rechtswirksamkeit', 'Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollständig entsprechen sollten, bleiben die übrigen Teile des Dokumentes in ihrem Inhalt und ihrer Gültigkeit davon unberührt.', 5);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Daten für Tabelle `run_links`
--

INSERT INTO `run_links` (`id`, `name`, `link`, `target`, `parent`, `prog_grp_id`, `geaendert`) VALUES
(12, 'impressum', 'includes/Impressum.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(1, 'start', 'includes/Startseite.php', 'mainpage', '', 0, '2010-02-20 16:16:00'),
(19, 'changeMyProfile', 'includes/user/user_change.php', 'mainpage', '', 6, '2008-09-11 23:49:04'),
(20, 'doUserpicUpload', 'includes/user/userpic_upload2.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(21, 'userpicUpload', 'includes/user/userpic_upload.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(22, 'userRequestPw', 'includes/user/user_request_pw.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(24, 'showUserList', 'includes/user/user_liste.php', 'mainpage', '', 0, '2010-02-20 16:16:00'),
(29, 'showUserProfil', 'includes/user/show_userprofil.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(30, 'userListe', 'includes/user/user_liste.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(36, 'login', 'includes/Login.php', 'mainpage', '', 0, '2008-11-16 22:08:45'),
(41, 'redaktionsgruppe', 'includes/empty.php', 'mainpage', '', 1, '2010-02-20 16:16:00'),
(52, 'shortcutConfig', 'includes/ShortcutConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(50, 'imageUploaderPopup', 'includes/ImageUploaderPopup.php', 'mainpage', '', 0, '2009-06-27 11:01:28'),
(51, 'homeconfig', 'includes/ControlConfig.php', 'mainpage', '', 0, '0000-00-00 00:00:00'),
(53, 'shortcuts', 'includes/ShortcutSidebar.php', 'mainpage', '', 0, '2012-12-31 02:39:15');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

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
(23, 'changed', '<br><br><i><u><b>Geändert:', 'N'),
(24, '/changed', '</b></u></i>', 'N'),
(25, 'bild_500', '<img src=''', 'J'),
(26, '/bild_500', ''' width=''500''>', 'J'),
(28, 'bild_150', '<img src=''', 'J'),
(29, '/bild_150', ''' width=''150''>', 'J'),
(30, 'code', '<textarea cols=''70'' rows=''10'' readonly>', 'J'),
(31, '/code', '</textarea>', 'N'),
(32, 'yellow', '<font color=''yellow''>', 'N'),
(33, '/yellow', '</font>', 'N'),
(34, 'groß', '<font size=''4''>', 'J'),
(35, '/groß', '</font>', 'J'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `update_log`
--


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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `Vorname`, `Nachname`, `Name`, `Geburtstag`, `Strasse`, `Plz`, `Ort`, `Email`, `Telefon`, `Fax`, `Handy`, `Icq`, `Aim`, `Homepage`, `User`, `Pw`, `Nation`, `Status`, `user_group_id`, `Newsletter`, `Signatur`, `Lastlogin`, `Posts`, `Beschreibung`, `pic`, `pnnotify`, `autoforumnotify`, `geaendert`, `emailJN`, `icqJN`, `telefonJN`, `Level`, `EP`, `Gold`, `Holz`, `Erz`, `Felsen`, `Wasser`, `Nahrung`, `aktiv`, `activationString`, `angelegt`, `clan_id`, `rasse_id`) VALUES
(1, 'Admin', 'Istrator', 'Administrator', '0000-00-00', '-', '-', '-', NULL, '-', '', '-', NULL, NULL, NULL, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', NULL, 'user', 1, 'true', NULL, NULL, 0, NULL, 'unknown.jpg', 'Y', 'Y', '2013-01-08 02:45:41', 'N', 'N', 'N', 0, 0, 0, 0, 0, 0, 0, 0, 'J', NULL, '0000-00-00', NULL, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `user_groups`
--


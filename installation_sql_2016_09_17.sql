
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `action_log` (
  `id` int(11) NOT NULL,
  `sessionid` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL,
  `zeit` int(30) NOT NULL,
  `request_dump` text NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `farbwert` varchar(20) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `colors` VALUES (1,'text','#454545',0,'2012-10-25 17:33:00');
INSERT INTO `colors` VALUES (2,'link','#9999bb',0,'2015-01-02 00:15:22');
INSERT INTO `colors` VALUES (3,'hover','#39a0f8',0,'2015-01-02 00:04:56');
INSERT INTO `colors` VALUES (4,'titel','#1976D2',0,'2015-01-02 00:03:51');
INSERT INTO `colors` VALUES (5,'menu','#9999bb',0,'2015-01-02 00:15:28');
INSERT INTO `colors` VALUES (6,'background','#efefef',0,'2015-01-02 00:07:44');
INSERT INTO `colors` VALUES (7,'panel_background','#efefef',0,'2014-10-26 02:10:14');
INSERT INTO `colors` VALUES (8,'Tabelle_Hintergrund_1','#e1e1e1',0,'2014-10-26 02:11:23');
INSERT INTO `colors` VALUES (9,'Tabelle_Hintergrund_2','#d5d5d5',0,'2014-10-26 02:09:42');
INSERT INTO `colors` VALUES (10,'main_background','#ffffff',0,'2012-10-28 18:17:32');
INSERT INTO `colors` VALUES (11,'button_background','#cccccc',0,'0000-00-00 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `dbcombos` (
  `id` int(11) NOT NULL,
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
);

INSERT INTO `dbcombos` VALUES (1,'geplant','status','geplant_status','tag','name','true','','name','J');
INSERT INTO `dbcombos` VALUES (3,'koordinatenzuordnung','str_id','strassenschluessel','id','name','true','','name','J');
INSERT INTO `dbcombos` VALUES (4,'stadt_angebot','ansprech','adressen','id','concat(name, \' \', strasse) as adr','true','ansprechpartner=\'J\'','','J');
INSERT INTO `dbcombos` VALUES (6,'stadt_institution','adresse','adressen','id','CONCAT(name, \' - \', plz, \' \', strasse, \' \', hausnummer) as adresse','true','ansprechpartner=\'N\'','','J');
INSERT INTO `dbcombos` VALUES (7,'links','topic','links','topic','topic','true','link is not null and descr is not null and link != \'-\' and descr != \'-\'','topic','J');
INSERT INTO `dbcombos` VALUES (9,'menu','parent','menu','text','text','true','','text','J');
INSERT INTO `dbcombos` VALUES (10,'stadt_kategorien','symbol','stadt_symbole','id','tooltip','true','','tooltip','J');
INSERT INTO `dbcombos` VALUES (11,'stadt_institution','kategorie','stadt_kategorien','id','name','true','','name','J');
INSERT INTO `dbcombos` VALUES (12,'user','status','userstatus','id','title','false','','title','J');
INSERT INTO `dbcombos` VALUES (13,'testbericht','institution_id','stadt_institution i, adressen a','i.id','CONCAT(i.name, \' - \', a.strasse, \' \', a.hausnummer) AS adresse','false','i.adresse = a.id order by i.name','','J');
INSERT INTO `dbcombos` VALUES (14,'stadt_angebot','institutionid','stadt_institution i, adressen a','i.id','CONCAT(i.name, \' - \', a.strasse, \' \', a.hausnummer) AS adresse','false','i.adresse = a.id ','','J');
INSERT INTO `dbcombos` VALUES (15,'menu','status','userstatus','id','title','false','','title','J');
INSERT INTO `dbcombos` VALUES (16,'stadt_angebot','kategorie','stadt_angebot_kategorie','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (17,'run_links','parent','menu','text','text','false','','text','J');
INSERT INTO `dbcombos` VALUES (18,'run_links','prog_grp_id','programm_gruppen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (19,'berechtigung','user_id','user','id','concat(Vorname, \' \',Nachname) as nme','false','Vorname != \'Developer\' and \r\nVorname != \'Superuser\'','','J');
INSERT INTO `dbcombos` VALUES (20,'berechtigung','user_grp_id','user_groups','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (21,'berechtigung','run_link_id','run_links','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (22,'berechtigung','prog_grp_id','programm_gruppen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (23,'terminserie','monat','default_combo_values','code','value','false','combo_name = \'Monate\'','value','J');
INSERT INTO `dbcombos` VALUES (24,'terminserie','jaehrlichwochentag','default_combo_values','code','value','false','combo_name = \'tage\'','value','J');
INSERT INTO `dbcombos` VALUES (25,'user','user_group_id','user_groups','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (26,'adressen','ortsteil','ortsteile','id','name','false','plz in (select plz from adressen where id=#id#)','name','J');
INSERT INTO `dbcombos` VALUES (27,'kopftexte','runlink','run_links','name','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (28,'kopftexte','parent','run_links','parent','parent','false','','parent','J');
INSERT INTO `dbcombos` VALUES (29,'adressen','strasse','strassenschluessel','name','name','false','plz = #plz#','','J');
INSERT INTO `dbcombos` VALUES (30,'homecontrol_shortcut_items','shortcut_id','homecontrol_shortcut','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (31,'homecontrol_shortcut_items','config_id','homecontrol_config','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (32,'homecontrol_shortcut_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (33,'homecontrol_shortcut_items','etagen_id',' homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (34,'homecontrol_zimmer','etagen_id','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (35,'homecontrol_config','control_art','homecontrol_art','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (36,'homecontrol_config','etage','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (37,'homecontrol_config','zimmer','homecontrol_zimmer','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (38,'homecontrol_shortcut_items','art_id','homecontrol_art','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (39,'homecontrol_cron_items','shortcut_id','homecontrol_shortcut','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (40,'homecontrol_cron_items','config_id','homecontrol_config','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (41,'homecontrol_cron_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (42,'homecontrol_cron_items','etagen_id','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (43,'homecontrol_cron_items','art_id','homecontrol_art','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (44,'homecontrol_sensor_items','sensor_id','homecontrol_sensor','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (45,'homecontrol_sensor_items','config_id','homecontrol_config','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (46,'homecontrol_sensor_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (47,'homecontrol_sensor_items','etagen_id',' homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (48,'homecontrol_sensor_items','art_id','homecontrol_art','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (49,'homecontrol_zimmer','etage_id','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (50,'homecontrol_alarm_items','shortcut_id','homecontrol_alarm','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (51,'homecontrol_alarm_items','config_id','homecontrol_config','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (52,'homecontrol_alarm_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (53,'homecontrol_alarm_items','etagen_id','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (54,'homecontrol_alarm_items','art_id','homecontrol_art','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (55,'homecontrol_regeln_items','shortcut_id','homecontrol_alarm','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (56,'homecontrol_regeln_items','config_id','homecontrol_config','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (57,'homecontrol_regeln_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (58,'homecontrol_regeln_items','etagen_id','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (59,'homecontrol_regeln_items','art_id','homecontrol_art','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (60,'homecontrol_sender','etage','homecontrol_etagen','id','name','false','','name','J');
INSERT INTO `dbcombos` VALUES (61,'homecontrol_sender','zimmer','homecontrol_zimmer','id','concat(name, \" - \", (select name from homecontrol_etagen where homecontrol_zimmer.etage_id = homecontrol_etagen.id))','false','','etage_id, name','J');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `default_combo_values` (
  `id` int(11) NOT NULL,
  `combo_name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `default_combo_values` VALUES (1,'tage','1','Montag');
INSERT INTO `default_combo_values` VALUES (2,'tage','2','Dienstag');
INSERT INTO `default_combo_values` VALUES (3,'tage','3','Mittwoch');
INSERT INTO `default_combo_values` VALUES (4,'tage','4','Donnerstag');
INSERT INTO `default_combo_values` VALUES (5,'tage','5','Freitag');
INSERT INTO `default_combo_values` VALUES (6,'tage','6','Samstag');
INSERT INTO `default_combo_values` VALUES (7,'tage','7','Sonntag');
INSERT INTO `default_combo_values` VALUES (8,'Monate','1','Januar');
INSERT INTO `default_combo_values` VALUES (9,'Monate','2','Februar');
INSERT INTO `default_combo_values` VALUES (10,'Monate','3','März');
INSERT INTO `default_combo_values` VALUES (11,'Monate','4','April');
INSERT INTO `default_combo_values` VALUES (12,'Monate','5','Mai');
INSERT INTO `default_combo_values` VALUES (13,'Monate','6','Juni');
INSERT INTO `default_combo_values` VALUES (14,'Monate','7','Juli');
INSERT INTO `default_combo_values` VALUES (15,'Monate','8','August');
INSERT INTO `default_combo_values` VALUES (16,'Monate','9','September');
INSERT INTO `default_combo_values` VALUES (17,'Monate','10','Oktober');
INSERT INTO `default_combo_values` VALUES (18,'Monate','11','November');
INSERT INTO `default_combo_values` VALUES (19,'Monate','12','Dezember');
INSERT INTO `default_combo_values` VALUES (20,'DatumTagzahl','1','1');
INSERT INTO `default_combo_values` VALUES (21,'DatumTagzahl','2','2');
INSERT INTO `default_combo_values` VALUES (22,'DatumTagzahl','3','3');
INSERT INTO `default_combo_values` VALUES (23,'DatumTagzahl','4','4');
INSERT INTO `default_combo_values` VALUES (24,'DatumTagzahl','5','5');
INSERT INTO `default_combo_values` VALUES (25,'DatumTagzahl','6','6');
INSERT INTO `default_combo_values` VALUES (26,'DatumTagzahl','7','7');
INSERT INTO `default_combo_values` VALUES (27,'DatumTagzahl','8','8');
INSERT INTO `default_combo_values` VALUES (28,'DatumTagzahl','9','9');
INSERT INTO `default_combo_values` VALUES (29,'DatumTagzahl','10','10');
INSERT INTO `default_combo_values` VALUES (30,'DatumTagzahl','11','11');
INSERT INTO `default_combo_values` VALUES (31,'DatumTagzahl','12','12');
INSERT INTO `default_combo_values` VALUES (32,'DatumTagzahl','13','13');
INSERT INTO `default_combo_values` VALUES (33,'DatumTagzahl','14','14');
INSERT INTO `default_combo_values` VALUES (34,'DatumTagzahl','15','15');
INSERT INTO `default_combo_values` VALUES (35,'DatumTagzahl','16','16');
INSERT INTO `default_combo_values` VALUES (36,'DatumTagzahl','17','17');
INSERT INTO `default_combo_values` VALUES (37,'DatumTagzahl','18','18');
INSERT INTO `default_combo_values` VALUES (38,'DatumTagzahl','19','19');
INSERT INTO `default_combo_values` VALUES (39,'DatumTagzahl','20','20');
INSERT INTO `default_combo_values` VALUES (40,'DatumTagzahl','21','21');
INSERT INTO `default_combo_values` VALUES (41,'DatumTagzahl','22','22');
INSERT INTO `default_combo_values` VALUES (42,'DatumTagzahl','23','23');
INSERT INTO `default_combo_values` VALUES (43,'DatumTagzahl','24','24');
INSERT INTO `default_combo_values` VALUES (44,'DatumTagzahl','25','25');
INSERT INTO `default_combo_values` VALUES (45,'DatumTagzahl','26','26');
INSERT INTO `default_combo_values` VALUES (46,'DatumTagzahl','27','27');
INSERT INTO `default_combo_values` VALUES (47,'DatumTagzahl','28','28');
INSERT INTO `default_combo_values` VALUES (48,'DatumTagzahl','29','29');
INSERT INTO `default_combo_values` VALUES (49,'DatumTagzahl','30','30');
INSERT INTO `default_combo_values` VALUES (50,'DatumTagzahl','31','31');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `fixtexte` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `form_insert_validation` (
  `id` int(11) NOT NULL,
  `chkVal` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chkVal` (`chkVal`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_alarm` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `cam_trigger_jn` enum('J','N') NOT NULL DEFAULT 'N',
  `email` varchar(50) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  `foto_senden_jn` enum('J','N') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_alarmgeber_art` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `homecontrol_alarmgeber_art` VALUES (1,'Sirene','pics/Sirene.png','2015-09-02 00:15:09');
INSERT INTO `homecontrol_alarmgeber_art` VALUES (2,'Alarm-Licht','pics/Alarmlicht.png','2015-09-02 00:18:06');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_alarm_geber` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `x` int(10) DEFAULT '-1',
  `y` int(10) DEFAULT '-1',
  `etage_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `alarmgeber_art` int(11) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_alarm_items` (
  `id` int(11) NOT NULL,
  `alarm_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_item_uk` (`alarm_id`,`config_id`,`zimmer_id`,`etagen_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_art` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `zweite_funkid_jn` set('J','N') NOT NULL DEFAULT 'N',
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `homecontrol_art` VALUES (1,'Steckdose','N','pics/Steckdose.png','2014-11-28 12:44:33');
INSERT INTO `homecontrol_art` VALUES (2,'Jalousie','N','pics/Jalousien.png','2014-11-28 12:44:56');
INSERT INTO `homecontrol_art` VALUES (3,'Lampe','N','pics/Gluehbirne.png','2014-11-28 12:45:17');
INSERT INTO `homecontrol_art` VALUES (4,'Heizung','N','pics/Heizung.png','2014-11-28 12:45:27');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_condition` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `homecontrol_condition` VALUES (1,'<','<');
INSERT INTO `homecontrol_condition` VALUES (3,'>','>');
INSERT INTO `homecontrol_condition` VALUES (4,'<=','<=');
INSERT INTO `homecontrol_condition` VALUES (5,'>=','>=');
INSERT INTO `homecontrol_condition` VALUES (6,'=','=');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_config` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `funk_id` int(3) NOT NULL,
  `funk_id2` int(3) DEFAULT NULL,
  `beschreibung` text NOT NULL,
  `control_art` int(11) NOT NULL DEFAULT '1',
  `etage` int(3) NOT NULL DEFAULT '0',
  `zimmer` int(11) DEFAULT NULL,
  `x` int(4) NOT NULL DEFAULT '0',
  `y` int(4) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL,
  `dimmer` set('J','N') NOT NULL DEFAULT 'N',
  `sender_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_cron` (
  `id` int(11) NOT NULL,
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
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hc_cron_name_uk` (`name`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_cron_items` (
  `id` int(11) NOT NULL,
  `cron_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_item_uk` (`cron_id`,`config_id`,`zimmer_id`,`etagen_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_cron_pause` (
  `cron_id` int(11) NOT NULL,
  `pause_time` int(30) NOT NULL,
  PRIMARY KEY (`cron_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_etagen`;
CREATE TABLE `homecontrol_etagen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_uk` (`name`)
) TYPE=MyISAM AUTO_INCREMENT=6;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_modes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `selectable` set('J','N') NOT NULL,
  `beschreibung` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `homecontrol_modes` VALUES (1,'default','N','Standard-Modus (Eintr�ge gelten f�r alle Modes)');
INSERT INTO `homecontrol_modes` VALUES (2,'anwesend','J','Anwesenheits-Modus');
INSERT INTO `homecontrol_modes` VALUES (3,'abwesend','J','Abwesenheits-Modus');
INSERT INTO `homecontrol_modes` VALUES (4,'urlaub','J','Urlaubs-Modus');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_noframe` (
  `id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_regeln` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `beschreibung` text,
  `reverse_switch` enum('J','N') NOT NULL DEFAULT 'J',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_regeln_items` (
  `id` int(11) NOT NULL,
  `regel_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `regel_id` (`regel_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_sender` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `etage` int(11) NOT NULL,
  `zimmer` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `range_von` int(11) NOT NULL,
  `range_bis` int(11) NOT NULL,
  `default_jn` enum('J','N') NOT NULL DEFAULT 'N',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_sensor` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `beschreibung` text,
  `status_sensor` enum('J','N') NOT NULL COMMENT 'J: Sensor der nur einen Status (1 oder 0) zur�ckliefert',
  `geaendert` timestamp NOT NULL,
  `lastSignal` int(30) NOT NULL,
  `lastValue` int(9) NOT NULL,
  `sensor_art` int(11) NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `etage` int(11) NOT NULL,
  `zimmer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_sensor_arten` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status_sensor_jn` set('J','N') NOT NULL DEFAULT 'N',
  `pic` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `homecontrol_sensor_arten` VALUES (1,'Bewegungsmelder','J','pics/Bewegungsmelder.png');
INSERT INTO `homecontrol_sensor_arten` VALUES (2,'Temperatur-Sensor','N','pics/TemperaturSensor.png');
INSERT INTO `homecontrol_sensor_arten` VALUES (3,'Helligkeits-Sensor','N','pics/HelligkeitsSensor.png');
INSERT INTO `homecontrol_sensor_arten` VALUES (4,'Regen-Sensor','J','pics/RegenSensor.png');
INSERT INTO `homecontrol_sensor_arten` VALUES (5,'Rauchsensor','N','pics/RauchSensor.png');
INSERT INTO `homecontrol_sensor_arten` VALUES (6,'Luftfeuchtigkeitssensor','N','pics/LuftfeuchtigkeitsSensor.png');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_sensor_log` (
  `sensor_id` int(11) NOT NULL,
  `value` int(9) NOT NULL,
  `update_time` int(30) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`sensor_id`,`update_time`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_shortcut` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `beschreibung` text NOT NULL,
  `show_shortcut` enum('J','N') NOT NULL DEFAULT 'J',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_shortcut_items` (
  `id` int(11) NOT NULL,
  `shortcut_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortcut_item_uk` (`shortcut_id`,`config_id`,`zimmer_id`,`etagen_id`),
  KEY `shortcut_id` (`shortcut_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_term` (
  `id` int(11) NOT NULL,
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
  `geaendert` timestamp NOT NULL,
  `lastSensorintervall` int(8) NOT NULL,
  `trigger_jn` set('J','N') NOT NULL DEFAULT 'J',
  PRIMARY KEY (`id`),
  KEY `trigger_id` (`trigger_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_term_trigger_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `homecontrol_term_trigger_type` VALUES (3,'Alarm');
INSERT INTO `homecontrol_term_trigger_type` VALUES (2,'Cron');
INSERT INTO `homecontrol_term_trigger_type` VALUES (1,'Regel');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_term_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `homecontrol_term_type` VALUES (1,'Sensorwert');
INSERT INTO `homecontrol_term_type` VALUES (2,'Sensor');
INSERT INTO `homecontrol_term_type` VALUES (3,'Zeit');
INSERT INTO `homecontrol_term_type` VALUES (4,'Wochentag');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `homecontrol_zimmer` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `etage_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_etage_uk` (`name`,`etage_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `kopftexte` (
  `id` int(11) NOT NULL,
  `runlink` varchar(250) NOT NULL,
  `text` text,
  `parent` varchar(50) DEFAULT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `runlink_name` (`runlink`)
);

INSERT INTO `kopftexte` VALUES (1,'start','\r\n','Treffpunkt','2010-02-20 17:15:19');
INSERT INTO `kopftexte` VALUES (3,'forum','Hier im Forum habt ihr die Möglichkeit alles nach Themen-Gruppiert zu besprechen.\r\n\r\nWenn euch Themengruppen fehlen sollten, wendet euch einfach an einen der Administratoren.\r\n\r\n','Treffpunkt','2008-10-12 12:26:47');
INSERT INTO `kopftexte` VALUES (4,'todo','Hier seht ihr eine Übersicht aller noch ausstehenden Änderungen an der Seite.\r\n\r\nWenn euch auch noch etwas auffällt, was falsch läuft oder was an Informationen fehlt, tragt es doch einfach hier ein.\r\n\r\nDie Entwicklung wird sich schnellstmöglich damit befassen.\r\nWird der Vorschlag für sinnvoll angesehen, wird er auch so gut und so schnell es geht umgesetzt!\r\n\r\n',NULL,'2008-10-15 01:20:47');
INSERT INTO `kopftexte` VALUES (5,'test','testing',NULL,'0000-00-00 00:00:00');
INSERT INTO `kopftexte` VALUES (6,'kontakt','Wenn Sie uns eine Nachricht zukommen lassen möchten, haben Sie mit diesem Formular die möglichkeit uns eine Email schreiben.\r\nWir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.\r\n',NULL,'0000-00-00 00:00:00');
INSERT INTO `kopftexte` VALUES (9,'bbUpload','In diesem Bereich könnt Ihr eure eigenen Bilder ins Bilderbuch einfügen.\r\n\r\n[fett]1. rechtsklick \"Add New Folder\"  um ein neues Verzeichniss anzulegen.[/fett]\r\nDer Name dieses Verzeichnisses wird später im Bilderbuch als Name der Bildergruppe angezeigt.\r\n\r\n[fett]2. das neue Verzeichniss auswählen und \"Dateien hinzufügen\"[/fett]\r\n\r\n[fett]3. In der Vorschau die Bilder überprüfen und ggf. in JPG oder PNG Konvertieren oder aus der Liste entfernen[/fett]\r\n\r\n[fett]4. Bilder \"Hochladen\"[/fett]\r\n\r\n[red][fett]Achtung![/fett] Ein späteres auswählen der angelegten Kategorie ist nach dem Hochladen nicht mehr möglich! Es können nachträglich somit keine Bilder mehr hinzugefügt werden.[/red]\r\n\r\n','Bilder','2009-03-17 00:19:25');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `links` (
  `id` int(6) unsigned NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `descr` longtext,
  `topic` varchar(50) DEFAULT NULL,
  `autor` varchar(50) NOT NULL DEFAULT '',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `Date` varchar(25) DEFAULT NULL,
  `User` varchar(30) NOT NULL DEFAULT '',
  `Ip` varchar(20) DEFAULT NULL,
  `Action` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `lookupwerte` (
  `id` int(11) NOT NULL,
  `tab_name` varchar(50) NOT NULL,
  `col_name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `text` varchar(50) NOT NULL,
  `validation_flag` varchar(50) NOT NULL,
  `sprache` varchar(2) NOT NULL DEFAULT 'de',
  `sortnr` int(5) NOT NULL DEFAULT '0',
  `default` varchar(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
);

INSERT INTO `lookupwerte` VALUES (1,'terminserie','serienmuster','1','Täglich','','de',0,'Y');
INSERT INTO `lookupwerte` VALUES (2,'terminserie','serienmuster','2','Wöchentlich','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (3,'terminserie','serienmuster','3','Monatlich','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (4,'terminserie','serienmuster','4','Jährlich','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (6,'homecontrol_shortcut_items','on_off','on','Einschalten','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (7,'homecontrol_shortcut_items','on_off','off','Ausschalten','','de',0,'J');
INSERT INTO `lookupwerte` VALUES (8,'homecontrol_cron_items','on_off','on','Einschalten','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (9,'homecontrol_cron_items','on_off','off','Ausschalten','','de',0,'J');
INSERT INTO `lookupwerte` VALUES (10,'homecontrol_sensor_items','on_off','on','Einschalten','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (11,'homecontrol_sensor_items','on_off','off','Ausschalten','','de',0,'J');
INSERT INTO `lookupwerte` VALUES (12,'homecontrol_shortcutview','on_off','on','Einschalten','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (13,'homecontrol_shortcutview','on_off','off','Ausschalten','','de',0,'J');
INSERT INTO `lookupwerte` VALUES (14,'homecontrol_alarm_items','on_off','on','Einschalten','','de',0,'N');
INSERT INTO `lookupwerte` VALUES (15,'homecontrol_alarm_items','on_off','off','Ausschalten','','de',0,'J');
INSERT INTO `lookupwerte` VALUES (16,'homecontrol_regeln_items','on_off','off','Ausschalten','','de',0,'J');
INSERT INTO `lookupwerte` VALUES (17,'homecontrol_regeln_items','on_off','on','Einschalten','','de',0,'N');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `text` varchar(30) NOT NULL DEFAULT '',
  `parent` varchar(30) NOT NULL,
  `link` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(5) DEFAULT NULL,
  `target` varchar(25) NOT NULL DEFAULT '_top',
  `tooltip` text NOT NULL,
  `sortnr` int(11) NOT NULL DEFAULT '9999',
  `name` varchar(50) NOT NULL DEFAULT 'main',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `text` (`text`,`name`),
  KEY `parent_gruppe` (`parent`),
  KEY `sortnr` (`sortnr`)
);

INSERT INTO `menu` VALUES (116,'Shortcuts','Einstellungen','?menuParent=Einstellungen&run=shortcutConfig','admin','_top','Hier k�nnen die Schnellwahl Aktionen konfiguriert werden.',85,'Hauptmenue','2015-09-28 23:37:15');
INSERT INTO `menu` VALUES (105,'Login','','?run=login',NULL,'_top','Hier k�nnen Sie sich an- oder abmelden',0,'Fussmenue','2014-07-20 20:26:34');
INSERT INTO `menu` VALUES (115,'Geraete','Einstellungen','?menuParent=Einstellungen&run=homeconfig','admin','_top','Hier k�nnen die Ger�te konfiguriert werden.',5,'Hauptmenue','2015-08-23 19:05:47');
INSERT INTO `menu` VALUES (117,'Shortcuts','','?menuParent=Shortcuts&run=shortcuts','','_top','Konfigurierte Modi mit einem Klick',10,'Mobilmenue','2015-01-01 21:19:24');
INSERT INTO `menu` VALUES (133,'Alarmanlage','Einstellungen','?menuParent=Einstellungen&run=alarmConfig','admin','_top','Hier k�nnen die Einstellungen f�r das Verhalten der Alarmanlage konfiguriert werden.',90,'Hauptmenue','2015-08-23 19:05:47');
INSERT INTO `menu` VALUES (119,'Zeitplan','Einstellungen','?menuParent=Einstellungen&run=cronConfig','admin','_top','Hier k�nnen die automatischen Jobs konfiguriert werden.',50,'Hauptmenue','2015-09-28 23:35:34');
INSERT INTO `menu` VALUES (120,'Sensoren','Einstellungenxx','?menuParent=Einstellungen&run=sensorConfig','xxxx','_top','Hier k�nnen die Aktionen f�r Sensoren konfiguriert werden.',30,'Hauptmenue','2016-08-17 01:52:27');
INSERT INTO `menu` VALUES (121,'Einstellungen','','?menuParent=Einstellungen&run=mainSettings','admin','_top','Hier kann das gesamte System konfiguriert werden',200,'Kopfmenue','2015-08-27 23:37:34');
INSERT INTO `menu` VALUES (122,'Steuerung','','?menuParent=Steuerung&run=start',NULL,'_top','',10,'Kopfmenue','2015-03-16 07:15:44');
INSERT INTO `menu` VALUES (124,'Steuerung','','?menuParent=Steuerung&run=start','','_top','Steuerung',0,'Mobilmenue','2015-01-01 21:18:40');
INSERT INTO `menu` VALUES (126,'Sensoren','','?menuParent=Sensoren&run=sensorList','','_top','Sensoren',5,'Mobilmenue','2015-01-05 09:09:19');
INSERT INTO `menu` VALUES (127,'Sensorwerte','','?menuParent=Sensoren&run=sensorList','','_top','Sensoren',150,'Kopfmenue','2015-08-24 23:34:02');
INSERT INTO `menu` VALUES (128,'Timeline','','?menuParent=Einstellungen&menuParent=Timeline&run=cronView',NULL,'_top','Hier werden die Events der n�chsten 24 Stunden angezeigt und k�nnen f�r die n�chste Ausf�hrung pausiert werden.',50,'Kopfmenue','2015-08-23 19:04:06');
INSERT INTO `menu` VALUES (129,'Sensor-Log','','?menuParent=Sensor-Log&run=sensorlogView',NULL,'_top','Hier werden die Logdaten der Sensoren angezeigt',70,'Kopfmenue','2014-11-11 22:09:42');
INSERT INTO `menu` VALUES (130,'Gebaeude','Einstellungen','?menuParent=Einstellungen&run=gebaeudeConfig','admin','_top','Hier werden die Etagen und Raeume konfiguriert',2,'Hauptmenue','2015-08-23 21:15:23');
INSERT INTO `menu` VALUES (131,'Basis','Einstellungen','?menuParent=Einstellungen&run=mainSettings','admin','_top','Basis-Einstellungen',1,'Hauptmenue','2015-08-25 23:06:41');
INSERT INTO `menu` VALUES (134,'Cam','','?run=camPics','admin','_top','Bewegungserkennung - Bilder',9999,'Kopfmenue','2015-09-04 01:32:15');
INSERT INTO `menu` VALUES (135,'Timeline','','?menuParent=Einstellungen&menuParent=Timeline&run=cronView',NULL,'_top','Hier werden die Events der n�chsten 24 Stunden angezeigt und k�nnen f�r die n�chste Ausf�hrung pausiert werden.',50,'Mobilmenue','2015-08-23 19:04:06');
INSERT INTO `menu` VALUES (136,'Automatisierung','Einstellungen','?menuParent=Einstellungen&run=automationConfig','admin','_top','In diesem Bereich werden Automatisierungen in Abh�ngigkeit der Sensorwerte konfiguriert.',85,'Hauptmenue','2015-09-28 23:35:34');
INSERT INTO `menu` VALUES (137,'Passwort vergessen','','?run=userRequestPw',NULL,'_top','',9999,'need','2015-10-15 22:34:29');
INSERT INTO `menu` VALUES (138,'','','?run=changeMyProfile',NULL,'_top','',9999,'need','0000-00-00 00:00:00');
INSERT INTO `menu` VALUES (139,'cc','','run=userpicUpload',NULL,'_top','',9999,'need','0000-00-00 00:00:00');
INSERT INTO `menu` VALUES (140,'Signale','Einstellungen','?menuParent=Einstellungen&run=alarmgeberConfig','admin','_top','Hier k�nnen die Alarmgeber (Sirenen, Lampen etc.) konfiguriert werden.',110,'Hauptmenue','2015-10-26 22:00:52');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `pageconfig` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `page_id` (`page_id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `programm_gruppen` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `text` varchar(250) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `programm_gruppen` VALUES (3,'Bilder','Alles was zum Bilderbuch gehört','0000-00-00 00:00:00');
INSERT INTO `programm_gruppen` VALUES (4,'Einstellungen','Einstellungsmasken und Administrative Links','0000-00-00 00:00:00');
INSERT INTO `programm_gruppen` VALUES (5,'Allgemeines','Hier kommt alles rein, was generell zur Verfügung steht','0000-00-00 00:00:00');
INSERT INTO `programm_gruppen` VALUES (6,'Mein Profil','Alles rund ums Userprofil','0000-00-00 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `public_vars` (
  `id` int(11) NOT NULL,
  `gruppe` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `titel` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `sortnr` int(5) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `public_vars` VALUES (1,'texte','impressum','Inhalt des Onlineangebotes','Der Autor übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche gegen den Autor, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen, sofern seitens des Autors kein nachweislich vorsätzliches oder grob fahrlässiges Verschulden vorliegt. Alle Angebote sind freibleibend und unverbindlich. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.',1);
INSERT INTO `public_vars` VALUES (2,'texte','impressum','Verweise und Links','Bei direkten oder indirekten Verweisen auf fremde Webseiten (\"Hyperlinks\"), die außerhalb des Verantwortungsbereiches des Autors liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern. Der Autor erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige Gestaltung, die Inhalte oder die Urheberschaft der gelinkten/verknüpften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller gelinkten /verknüpften Seiten, die nach der Linksetzung verändert wurden. Diese Feststellung gilt für alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie für Fremdeinträge in vom Autor eingerichteten Gästebüchern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe möglich sind. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.\r\n',2);
INSERT INTO `public_vars` VALUES (3,'texte','impressum','Urheber- und Kennzeichenrecht','Der Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen. Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschützten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind! Das Copyright für veröffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Autors nicht gestattet.',3);
INSERT INTO `public_vars` VALUES (4,'texte','impressum','Datenschutz','Sofern innerhalb des Internetangebotes die Möglichkeit zur Eingabe persönlicher oder geschäftlicher Daten (Kontodaten, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrücklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch möglich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet. Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben veröffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur Übersendung von nicht ausdrücklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei Verstössen gegen dieses Verbot sind ausdrücklich vorbehalten.',4);
INSERT INTO `public_vars` VALUES (5,'texte','impressum','Rechtswirksamkeit','Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollständig entsprechen sollten, bleiben die übrigen Teile des Dokumentes in ihrem Inhalt und ihrer Gültigkeit davon unberührt.',5);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `run_links` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(250) NOT NULL,
  `target` varchar(50) NOT NULL DEFAULT 'mainpage',
  `parent` varchar(50) NOT NULL,
  `prog_grp_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`name`,`parent`)
);

INSERT INTO `run_links` VALUES (12,'impressum','includes/Impressum.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (1,'start','includes/Startseite.php','mainpage','',0,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (19,'changeMyProfile','includes/user/user_change.php','mainpage','',6,'2008-09-11 21:49:04');
INSERT INTO `run_links` VALUES (20,'doUserpicUpload','includes/user/userpic_upload2.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (21,'userpicUpload','includes/user/userpic_upload.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (22,'userRequestPw','includes/user/user_request_pw.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (24,'showUserList','includes/user/user_liste.php','mainpage','',0,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (29,'showUserProfil','includes/user/show_userprofil.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (30,'userListe','includes/user/user_liste.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (36,'login','includes/Login.php','mainpage','',0,'2008-11-16 21:08:45');
INSERT INTO `run_links` VALUES (41,'redaktionsgruppe','includes/empty.php','mainpage','',1,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (52,'shortcutConfig','includes/ShortcutConfig.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (50,'imageUploaderPopup','includes/ImageUploaderPopup.php','mainpage','',0,'2009-06-27 09:01:28');
INSERT INTO `run_links` VALUES (51,'homeconfig','includes/ControlConfig.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (53,'shortcuts','includes/ShortcutSidebar.php','mainpage','',0,'2012-12-31 01:39:15');
INSERT INTO `run_links` VALUES (2,'mobile_start','mobile_includes/Startseite.php','mainpage','',0,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (54,'cronConfig','includes/CronConfig.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (55,'sensorConfig','includes/SensorConfig.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (56,'sensoren','includes/SensorenEdit.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (57,'sensorList','includes/Sensoren.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (58,'cronView','includes/CronView.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (59,'sensorlogView','includes/SensorLogViewer.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (60,'gebaeudeConfig','includes/GebaeudeConfig.php','mainpage','',0,'2015-08-23 18:58:17');
INSERT INTO `run_links` VALUES (61,'mainSettings','includes/MainSettings.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (62,'network','includes/NetworkConfig.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (63,'alarmConfig','includes/AlarmConfig.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (64,'camPics','includes/CamPics.php','mainpage','',0,'0000-00-00 00:00:00');
INSERT INTO `run_links` VALUES (66,'automationConfig','includes/AutomationConfig.php','mainpage','',0,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (67,'alarmgeberConfig','includes/AlarmgeberConfig.php','mainpage','',0,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (68,'mobile_shortcuts','mobile_includes/ShortcutSidebar.php','mainpage','',0,'2010-02-20 15:16:00');
INSERT INTO `run_links` VALUES (69,'logView','includes/LogView.php','mainpage','',0,'0000-00-00 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `site-enter` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `site-enter` VALUES (1,'Box in','0');
INSERT INTO `site-enter` VALUES (2,'Box Out','1');
INSERT INTO `site-enter` VALUES (3,'Circle in','2');
INSERT INTO `site-enter` VALUES (4,'Circle out','3');
INSERT INTO `site-enter` VALUES (5,'Wipe up','4');
INSERT INTO `site-enter` VALUES (6,'Wipe down','5');
INSERT INTO `site-enter` VALUES (7,'Wipe right','6');
INSERT INTO `site-enter` VALUES (8,'Wipe left','7');
INSERT INTO `site-enter` VALUES (9,'Vertical Blinds','8');
INSERT INTO `site-enter` VALUES (10,'Horizontal Blinds','9');
INSERT INTO `site-enter` VALUES (11,'Checkerboard across','10');
INSERT INTO `site-enter` VALUES (12,'Checkerboard down','11');
INSERT INTO `site-enter` VALUES (13,'Random Disolve','12');
INSERT INTO `site-enter` VALUES (14,'Split vertical in','13');
INSERT INTO `site-enter` VALUES (15,'Split vertical out','14');
INSERT INTO `site-enter` VALUES (16,'Split horizontal in','15');
INSERT INTO `site-enter` VALUES (17,'Split horizontal out','16');
INSERT INTO `site-enter` VALUES (18,'Strips left down','17');
INSERT INTO `site-enter` VALUES (19,'Strips left up','18');
INSERT INTO `site-enter` VALUES (20,'Strips right down','19');
INSERT INTO `site-enter` VALUES (21,'Strips right up','20');
INSERT INTO `site-enter` VALUES (22,'Random bars horizontal','21');
INSERT INTO `site-enter` VALUES (23,'Random bars vertical','22');
INSERT INTO `site-enter` VALUES (24,'Random','23');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `smileys` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Title` (`title`),
  UNIQUE KEY `Link` (`link`)
);

INSERT INTO `smileys` VALUES (1,':-)','pics/smileys/grins.gif');
INSERT INTO `smileys` VALUES (4,':-P','pics/smileys/baeh.gif');
INSERT INTO `smileys` VALUES (11,'cry','pics/smileys/crying.gif');
INSERT INTO `smileys` VALUES (13,'lol','pics/smileys/biglaugh.gif');
INSERT INTO `smileys` VALUES (16,':-@','pics/smileys/motz.gif');
INSERT INTO `smileys` VALUES (17,':-O','pics/smileys/confused.gif');
INSERT INTO `smileys` VALUES (20,':-D','pics/smileys/auslach.gif');
INSERT INTO `smileys` VALUES (26,'rofl','pics/smileys/rofl.gif');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag` varchar(15) NOT NULL DEFAULT '',
  `html` varchar(150) NOT NULL DEFAULT '',
  `btn` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `tag1` (`tag`)
);

INSERT INTO `tags` VALUES (1,'cybi','<a href=\'http://www.cyborgone.de\' target=\'cybi\'><img src=\'http://cyborgone.de/pics/banner13.gif\' width=\'200\' border=\'0\'></a>','n');
INSERT INTO `tags` VALUES (2,'fett','<b>','J');
INSERT INTO `tags` VALUES (3,'/fett','</b>','J');
INSERT INTO `tags` VALUES (4,'unter','<u>','J');
INSERT INTO `tags` VALUES (5,'/unter','</u>','J');
INSERT INTO `tags` VALUES (6,'normal','<font size=\'2\'>','J');
INSERT INTO `tags` VALUES (7,'/normal','</font>','J');
INSERT INTO `tags` VALUES (8,'klein','<font size=\'1\'>','J');
INSERT INTO `tags` VALUES (9,'/klein','</font>','J');
INSERT INTO `tags` VALUES (10,'mittel','<font size=\'3\'>','J');
INSERT INTO `tags` VALUES (11,'/mittel','</font>','J');
INSERT INTO `tags` VALUES (12,'blue','<font color=\'blue\'>','J');
INSERT INTO `tags` VALUES (13,'red','<font color=\'red\'>','J');
INSERT INTO `tags` VALUES (14,'green','<font color=\'green\'>','J');
INSERT INTO `tags` VALUES (15,'gray','<font color=\'gray\'>','J');
INSERT INTO `tags` VALUES (16,'/gray','</font>','J');
INSERT INTO `tags` VALUES (17,'/red','</font>','J');
INSERT INTO `tags` VALUES (18,'/blue','</font>','J');
INSERT INTO `tags` VALUES (19,'/green','</font>','J');
INSERT INTO `tags` VALUES (20,'quote','<table border=\'1\' cellpadding=\'0\' cellspacing=\'0\'><tr><td class=\'zitat\'><i>','N');
INSERT INTO `tags` VALUES (21,'hr','<hr>','J');
INSERT INTO `tags` VALUES (22,'/quote','</i></td></tr></table>','N');
INSERT INTO `tags` VALUES (23,'changed','<br><br><i><u><b>Geändert:','N');
INSERT INTO `tags` VALUES (24,'/changed','</b></u></i>','N');
INSERT INTO `tags` VALUES (25,'bild_500','<img src=\'','J');
INSERT INTO `tags` VALUES (26,'/bild_500','\' width=\'500\'>','J');
INSERT INTO `tags` VALUES (28,'bild_150','<img src=\'','J');
INSERT INTO `tags` VALUES (29,'/bild_150','\' width=\'150\'>','J');
INSERT INTO `tags` VALUES (30,'code','<textarea cols=\'70\' rows=\'10\' readonly>','J');
INSERT INTO `tags` VALUES (31,'/code','</textarea>','N');
INSERT INTO `tags` VALUES (32,'yellow','<font color=\'yellow\'>','N');
INSERT INTO `tags` VALUES (33,'/yellow','</font>','N');
INSERT INTO `tags` VALUES (34,'groß','<font size=\'4\'>','J');
INSERT INTO `tags` VALUES (35,'/groß','</font>','J');
INSERT INTO `tags` VALUES (36,'mitte','<center>',NULL);
INSERT INTO `tags` VALUES (37,'/mitte','</center>',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `update_log` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `descr` text NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `Vorname` varchar(50) NOT NULL DEFAULT '',
  `Nachname` varchar(50) NOT NULL DEFAULT '',
  `Name` varchar(100) NOT NULL DEFAULT '',
  `Geburtstag` date NOT NULL DEFAULT '0000-00-00',
  `Strasse` varchar(50) DEFAULT '-',
  `Plz` varchar(50) DEFAULT '-',
  `Ort` varchar(50) DEFAULT '-',
  `Email` varchar(50) DEFAULT NULL,
  `Telefon` varchar(50) DEFAULT '-',
  `Fax` varchar(20) NOT NULL DEFAULT '',
  `Handy` varchar(50) DEFAULT '-',
  `Icq` varchar(25) DEFAULT NULL,
  `Aim` varchar(25) DEFAULT NULL,
  `Homepage` varchar(50) DEFAULT NULL,
  `User` varchar(20) NOT NULL DEFAULT '',
  `Pw` varchar(255) NOT NULL,
  `Nation` char(1) DEFAULT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'waitForActivate',
  `user_group_id` int(11) NOT NULL DEFAULT '1',
  `Newsletter` enum('true','false') DEFAULT 'true',
  `Signatur` text,
  `Lastlogin` varchar(20) DEFAULT NULL,
  `Posts` int(10) DEFAULT '0',
  `Beschreibung` text,
  `pic` varchar(150) NOT NULL DEFAULT 'unknown.jpg',
  `pnnotify` char(1) NOT NULL DEFAULT 'Y',
  `autoforumnotify` char(1) NOT NULL DEFAULT 'Y',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `emailJN` enum('J','N') NOT NULL DEFAULT 'N',
  `icqJN` enum('J','N') NOT NULL DEFAULT 'J',
  `telefonJN` enum('J','N') NOT NULL DEFAULT 'N',
  `Level` double NOT NULL,
  `EP` double NOT NULL,
  `Gold` double NOT NULL,
  `Holz` double NOT NULL,
  `Erz` double NOT NULL,
  `Felsen` double NOT NULL,
  `Wasser` double NOT NULL,
  `Nahrung` double NOT NULL,
  `aktiv` set('J','N') NOT NULL DEFAULT 'N',
  `activationString` varchar(255) DEFAULT NULL,
  `angelegt` date NOT NULL COMMENT 'timestamp angelegt',
  `clan_id` int(11) DEFAULT NULL,
  `rasse_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `User` (`User`),
  KEY `Name` (`Name`(8))
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `userstatus` (
  `id` varchar(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
);

INSERT INTO `userstatus` VALUES ('gast','Gast');
INSERT INTO `userstatus` VALUES ('user','Hauptbenutzer');
INSERT INTO `userstatus` VALUES ('admin','Administrator');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `beschreibung` varchar(250) NOT NULL,
  `pic` varchar(150) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO user (id, Vorname, Nachname, Name, Geburtstag, Strasse, Plz, Ort, Email, Telefon, Fax, Handy, Icq, Aim, Homepage, User, Pw, Nation, Status, user_group_id, Newsletter, Signatur, Lastlogin, Posts, Beschreibung, pic, pnnotify, autoforumnotify, geaendert, emailJN, icqJN, telefonJN, Level, EP, Gold, Holz, Erz, Felsen, Wasser, Nahrung, aktiv, activationString, angelegt, clan_id, rasse_id)             VALUES (1, 'Admini', 'Istrator', 'Admini Istrator', '0000-00-00', '-', '-', '-', '', '-', '', '-', '', NULL, '', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin', 1, 'true', '', '2016-09-05 11:20:17', 0, NULL, 'unknown.jpg', 'Y', 'Y', '2016-09-05 09:20:17', 'N', 'N', 'N', 0, 0, 0, 0, 0, 0, 0, 0, 'J', NULL, '0000-00-00', NULL, 1);
ALTER TABLE user MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
INSERT INTO pageconfig VALUES
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
(20, 'NotifyTargetMail', '', 0, '2015-08-30 23:10:27', 'Benachrichtigungs-Email'),
(21, 'KontaktformularTargetMail', '', 0, '2013-01-08 02:33:52', NULL),
(23, 'timezoneadditional', '2', 0, '0000-00-00 00:00:00', NULL),
(24, 'loginForSwitchNeed', 'J', 0, '2016-09-06 16:56:48', 'Login zum schalten ben&ouml;tigt'),
(26, 'abwesendSimulation', 'N', 0, '2016-08-10 20:53:28', 'Anwesenheits-Simulation'),
(27, 'abwesendMotion', 'N', 0, '2015-08-26 22:46:27', 'Kamera-Bewegungserkennung'),
(28, 'anwesendMotion', 'N', 0, '2016-08-10 20:50:02', 'Kamera-Bewegungserkennung'),
(29, 'sessionDauer', '6000', 0, '2015-08-29 17:06:18', 'Zeit bis zum erneuten Login in Sekunden'),
(30, 'motionDauer', '9', 0, '2015-09-28 00:37:09', 'Tage die Bewegungs-Bilder behalten'),
(31, 'sensorlogDauer', '60', 0, '2015-08-28 07:03:00', 'Tage die Sensor-Log Daten behalten'),
(32, 'abwesendAlarm', 'N', 0, '2016-08-10 20:50:09', NULL),
(33, 'currentMode', '2', 0, '2015-10-11 17:18:53', NULL),
(34, 'timelineDuration', '3', 0, '2015-09-28 00:33:59', 'Gibt an, wie viele Tage in der Timeline angezeigt werden sollen'),
(35, 'loginForTimelinePauseNeed', 'J', 0, '2016-05-31 09:22:58', 'Gibt an, ob zum pausieren in der Timeline ein Login notwendig ist.'),
(36, 'btSwitchActive', 'J', 0, '2016-09-02 22:37:16', 'Gibt an, ob ein BT-Switch eingesetzt wird/werden soll'),
(37, 'loginExternOnly', 'J', 0, '0000-00-00 00:00:00', 'Wenn aktiviert, ist der Login zum schalten nur von Extern (abweichene IP Range) notwendig.');
ALTER TABLE pageconfig MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;

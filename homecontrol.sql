
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `action_log`;
CREATE TABLE `action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL,
  `zeit` int(30) NOT NULL,
  `request_dump` text,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `colors`;
CREATE TABLE `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `farbwert` varchar(20) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=12;

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'text','#454545',0,'2012-10-25 17:33:00'),(2,'link','#9999bb',0,'2015-01-02 00:15:22'),(3,'hover','#39a0f8',0,'2015-01-02 00:04:56'),(4,'titel','#1976D2',0,'2015-01-02 00:03:51'),(5,'menu','#9999bb',0,'2015-01-02 00:15:28'),(6,'background','#efefef',0,'2015-01-02 00:07:44'),(7,'panel_background','#efefef',0,'2014-10-26 02:10:14'),(8,'Tabelle_Hintergrund_1','#e1e1e1',0,'2014-10-26 02:11:23'),(9,'Tabelle_Hintergrund_2','#d5d5d5',0,'2014-10-26 02:09:42'),(10,'main_background','#ffffff',0,'2012-10-28 18:17:32'),(11,'button_background','#cccccc',0,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `chkActions`;
CREATE TABLE `chkActions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `chkval` text NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=75;

LOCK TABLES `chkActions` WRITE;
/*!40000 ALTER TABLE `chkActions` DISABLE KEYS */;
INSERT INTO `chkActions` VALUES (1,1,'','2017-01-02 23:47:46'),(2,1,'','2017-01-02 23:51:13'),(3,1,'','2017-01-02 23:52:26'),(4,1,'','2017-01-02 23:53:22'),(5,1,'','2017-01-02 23:54:44'),(6,1,'','2017-01-02 23:56:45'),(7,1,'','2017-01-02 23:58:29'),(8,1,'','2017-01-02 23:58:54'),(9,1,'','2017-01-03 00:03:10'),(10,1,'','2017-01-03 00:06:03'),(11,1,'','2017-01-03 00:06:05'),(12,1,'','2017-01-03 00:06:07'),(13,1,'','2017-01-03 00:06:19'),(14,1,'','2017-01-03 00:07:07'),(15,1,'','2017-01-03 00:10:24'),(16,1,'','2017-01-03 00:13:52'),(17,1,'','2017-01-03 00:14:51'),(18,1,'','2017-01-03 00:16:30'),(19,1,'','2017-01-03 00:26:14'),(20,1,'','2017-01-03 00:32:31'),(21,1,'','2017-01-03 00:34:10'),(22,1,'','2017-01-03 00:36:40'),(23,1,'','2017-01-03 00:38:53'),(24,1,'','2017-01-03 00:40:52'),(25,1,'','2017-01-03 00:42:21'),(26,1,'','2017-01-03 00:43:36'),(27,1,'','2017-01-03 00:47:45'),(28,1,'','2017-01-03 00:49:04'),(29,1,'','2017-01-03 00:49:14'),(30,1,'','2017-01-03 00:49:29'),(31,1,'','2017-01-03 00:50:12'),(32,1,'','2017-01-03 00:51:55'),(33,1,'','2017-01-03 00:51:58'),(34,1,'','2017-01-03 00:52:11'),(35,1,'','2017-01-03 00:54:18'),(36,1,'','2017-01-03 00:54:59'),(37,1,'','2017-01-03 01:03:43'),(38,1,'','2017-01-03 01:03:58'),(39,1,'','2017-01-03 01:06:27'),(40,1,'','2017-01-03 01:08:42'),(41,1,'','2017-01-03 01:08:56'),(42,1,'','2017-01-03 01:10:15'),(43,1,'','2017-01-03 01:11:04'),(44,1,'','2017-01-03 01:12:31'),(45,1,'','2017-01-03 01:13:14'),(46,1,'','2017-01-03 01:14:05'),(47,1,'','2017-01-03 01:14:08'),(48,1,'','2017-01-03 01:14:53'),(49,1,'','2017-01-03 01:18:03'),(50,1,'','2017-01-03 01:19:39'),(51,1,'','2017-01-03 01:20:12'),(52,1,'','2017-01-03 01:20:28'),(53,1,'','2017-01-03 01:21:43'),(54,1,'','2017-01-03 01:22:07'),(55,1,'','2017-01-03 01:23:53'),(56,1,'','2017-01-03 01:24:21'),(57,1,'','2017-01-03 01:24:28'),(58,1,'','2017-01-03 01:24:39'),(59,1,'','2017-01-03 01:25:24'),(60,1,'','2017-01-03 01:27:16'),(61,1,'','2017-01-03 01:27:22'),(62,1,'','2017-01-03 01:27:24'),(63,1,'','2017-01-03 01:29:55'),(64,1,'','2017-01-03 01:35:52'),(65,1,'','2017-01-03 01:36:26'),(66,1,'','2017-01-03 01:49:56'),(67,1,'','2017-01-04 01:59:57'),(68,1,'','2017-01-04 02:04:08'),(69,1,'','2017-01-04 02:05:14'),(70,1,'','2017-01-04 02:06:12'),(71,1,'','2017-01-04 02:08:52'),(72,1,'','2017-01-04 02:10:39'),(73,1,'','2017-01-04 02:11:52'),(74,1,'','2017-01-04 02:14:55');
/*!40000 ALTER TABLE `chkActions` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `dbcombos`;
CREATE TABLE `dbcombos` (
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
)   AUTO_INCREMENT=69;

LOCK TABLES `dbcombos` WRITE;
/*!40000 ALTER TABLE `dbcombos` DISABLE KEYS */;
INSERT INTO `dbcombos` VALUES (1,'geplant','status','geplant_status','tag','name','true','','name','J'),(3,'koordinatenzuordnung','str_id','strassenschluessel','id','name','true','','name','J'),(4,'stadt_angebot','ansprech','adressen','id','concat(name, \' \', strasse) as adr','true','ansprechpartner=\'J\'','','J'),(6,'stadt_institution','adresse','adressen','id','CONCAT(name, \' - \', plz, \' \', strasse, \' \', hausnummer) as adresse','true','ansprechpartner=\'N\'','','J'),(7,'links','topic','links','topic','topic','true','link is not null and descr is not null and link != \'-\' and descr != \'-\'','topic','J'),(9,'menu','parent','menu','text','text','true','','text','J'),(10,'stadt_kategorien','symbol','stadt_symbole','id','tooltip','true','','tooltip','J'),(11,'stadt_institution','kategorie','stadt_kategorien','id','name','true','','name','J'),(12,'user','status','userstatus','id','title','false','','title','J'),(13,'testbericht','institution_id','stadt_institution i, adressen a','i.id','CONCAT(i.name, \' - \', a.strasse, \' \', a.hausnummer) AS adresse','false','i.adresse = a.id order by i.name','','J'),(14,'stadt_angebot','institutionid','stadt_institution i, adressen a','i.id','CONCAT(i.name, \' - \', a.strasse, \' \', a.hausnummer) AS adresse','false','i.adresse = a.id ','','J'),(15,'menu','status','userstatus','id','title','false','','title','J'),(16,'stadt_angebot','kategorie','stadt_angebot_kategorie','id','name','false','','name','J'),(17,'run_links','parent','menu','text','text','false','','text','J'),(18,'run_links','prog_grp_id','programm_gruppen','id','name','false','','name','J'),(19,'berechtigung','user_id','user','id','concat(Vorname, \' \',Nachname) as nme','false','Vorname != \'Developer\' and \r\nVorname != \'Superuser\'','','J'),(20,'berechtigung','user_grp_id','user_groups','id','name','false','','name','J'),(21,'berechtigung','run_link_id','run_links','id','name','false','','name','J'),(22,'berechtigung','prog_grp_id','programm_gruppen','id','name','false','','name','J'),(23,'terminserie','monat','default_combo_values','code','value','false','combo_name = \'Monate\'','value','J'),(24,'terminserie','jaehrlichwochentag','default_combo_values','code','value','false','combo_name = \'tage\'','value','J'),(25,'user','user_group_id','user_groups','id','name','false','','name','J'),(26,'adressen','ortsteil','ortsteile','id','name','false','plz in (select plz from adressen where id=#id#)','name','J'),(27,'kopftexte','runlink','run_links','name','name','false','','name','J'),(28,'kopftexte','parent','run_links','parent','parent','false','','parent','J'),(29,'adressen','strasse','strassenschluessel','name','name','false','plz = #plz#','','J'),(30,'homecontrol_shortcut_items','shortcut_id','homecontrol_shortcut','id','name','false','','name','J'),(31,'homecontrol_shortcut_items','config_id','homecontrol_config','id','name','false','','name','J'),(32,'homecontrol_shortcut_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J'),(33,'homecontrol_shortcut_items','etagen_id',' homecontrol_etagen','id','name','false','','name','J'),(34,'homecontrol_zimmer','etagen_id','homecontrol_etagen','id','name','false','','name','J'),(35,'homecontrol_config','control_art','homecontrol_art','id','name','false','','name','J'),(36,'homecontrol_config','etage','homecontrol_etagen','id','name','false','','name','J'),(37,'homecontrol_config','zimmer','homecontrol_zimmer','id','name','false','','name','J'),(38,'homecontrol_shortcut_items','art_id','homecontrol_art','id','name','false','','name','J'),(39,'homecontrol_cron_items','shortcut_id','homecontrol_shortcut','id','name','false','','name','J'),(40,'homecontrol_cron_items','config_id','homecontrol_config','id','name','false','','name','J'),(41,'homecontrol_cron_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J'),(42,'homecontrol_cron_items','etagen_id','homecontrol_etagen','id','name','false','','name','J'),(43,'homecontrol_cron_items','art_id','homecontrol_art','id','name','false','','name','J'),(44,'homecontrol_sensor_items','sensor_id','homecontrol_sensor','id','name','false','','name','J'),(45,'homecontrol_sensor_items','config_id','homecontrol_config','id','name','false','','name','J'),(46,'homecontrol_sensor_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J'),(47,'homecontrol_sensor_items','etagen_id',' homecontrol_etagen','id','name','false','','name','J'),(48,'homecontrol_sensor_items','art_id','homecontrol_art','id','name','false','','name','J'),(49,'homecontrol_zimmer','etage_id','homecontrol_etagen','id','name','false','','name','J'),(50,'homecontrol_alarm_items','shortcut_id','homecontrol_alarm','id','name','false','','name','J'),(51,'homecontrol_alarm_items','config_id','homecontrol_config','id','name','false','','name','J'),(52,'homecontrol_alarm_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J'),(53,'homecontrol_alarm_items','etagen_id','homecontrol_etagen','id','name','false','','name','J'),(54,'homecontrol_alarm_items','art_id','homecontrol_art','id','name','false','','name','J'),(55,'homecontrol_regeln_items','shortcut_id','homecontrol_alarm','id','name','false','','name','J'),(56,'homecontrol_regeln_items','config_id','homecontrol_config','id','name','false','','name','J'),(57,'homecontrol_regeln_items','zimmer_id','homecontrol_zimmer','id','name','false','','name','J'),(58,'homecontrol_regeln_items','etagen_id','homecontrol_etagen','id','name','false','','name','J'),(59,'homecontrol_regeln_items','art_id','homecontrol_art','id','name','false','','name','J'),(60,'homecontrol_sender','etage','homecontrol_etagen','id','name','false','','name','J'),(61,'homecontrol_sender','zimmer','homecontrol_zimmer','id','concat(name, \" - \", (select name from homecontrol_etagen where homecontrol_zimmer.etage_id = homecontrol_etagen.id))','false','','etage_id, name','J'),(62,'homecontrol_sender_typen_parameter','senderTypId','homecontrol_sender_typen','id','name','false','','name','J'),(63,'homecontrol_sender_typen_parameter','parameterArtId','homecontrol_sender_typen_parameter_arten','id','name','false','','name','J'),(64,'homecontrol_sender','senderTypId','homecontrol_sender_typen','id','name','false','','name','J'),(65,'homecontrol_editor_parameter_possible','param_art_id','homecontrol_sender_typen_parameter_arten','id','name','false','','name','J'),(66,'homecontrol_editor_parameter_possible','editor_parameter_id','homecontrol_editor_parameter','id','name','false','editor_id = $selectedEditor$','name','J'),(67,'homecontrol_control_parameter_zu_editor','editor_param_id','homecontrol_editor_parameter','id','name','false','','name','J'),(68,'homecontrol_control_parameter_zu_editor','sender_param_id','homecontrol_sender_typen_parameter','id','name','false','senderTypId = (SELECT senderTypId FROM homecontrol_sender WHERE id = (SELECT sender_id FROM homecontrol_config WHERE id = ( SELECT config_id FROM homecontrol_control_editor_zuordnung WHERE id = #sendereditor_zuord_id#))) and fix=\'N\'','name','J');
/*!40000 ALTER TABLE `dbcombos` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `default_combo_values`;
CREATE TABLE `default_combo_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combo_name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=55;

LOCK TABLES `default_combo_values` WRITE;
/*!40000 ALTER TABLE `default_combo_values` DISABLE KEYS */;
INSERT INTO `default_combo_values` VALUES (1,'tage','1','Montag'),(2,'tage','2','Dienstag'),(3,'tage','3','Mittwoch'),(4,'tage','4','Donnerstag'),(5,'tage','5','Freitag'),(6,'tage','6','Samstag'),(7,'tage','7','Sonntag'),(8,'Monate','1','Januar'),(9,'Monate','2','Februar'),(10,'Monate','3','März'),(11,'Monate','4','April'),(12,'Monate','5','Mai'),(13,'Monate','6','Juni'),(14,'Monate','7','Juli'),(15,'Monate','8','August'),(16,'Monate','9','September'),(17,'Monate','10','Oktober'),(18,'Monate','11','November'),(19,'Monate','12','Dezember'),(20,'DatumTagzahl','1','1'),(21,'DatumTagzahl','2','2'),(22,'DatumTagzahl','3','3'),(23,'DatumTagzahl','4','4'),(24,'DatumTagzahl','5','5'),(25,'DatumTagzahl','6','6'),(26,'DatumTagzahl','7','7'),(27,'DatumTagzahl','8','8'),(28,'DatumTagzahl','9','9'),(29,'DatumTagzahl','10','10'),(30,'DatumTagzahl','11','11'),(31,'DatumTagzahl','12','12'),(32,'DatumTagzahl','13','13'),(33,'DatumTagzahl','14','14'),(34,'DatumTagzahl','15','15'),(35,'DatumTagzahl','16','16'),(36,'DatumTagzahl','17','17'),(37,'DatumTagzahl','18','18'),(38,'DatumTagzahl','19','19'),(39,'DatumTagzahl','20','20'),(40,'DatumTagzahl','21','21'),(41,'DatumTagzahl','22','22'),(42,'DatumTagzahl','23','23'),(43,'DatumTagzahl','24','24'),(44,'DatumTagzahl','25','25'),(45,'DatumTagzahl','26','26'),(46,'DatumTagzahl','27','27'),(47,'DatumTagzahl','28','28'),(48,'DatumTagzahl','29','29'),(49,'DatumTagzahl','30','30'),(50,'DatumTagzahl','31','31'),(51,'onOff','on','Aktivieren'),(52,'onOff','off','Deaktivieren'),(53,'relaisEinAus','Einschalten','Einschalten'),(54,'relaisEinAus','Ausschalten','Ausschalten');
/*!40000 ALTER TABLE `default_combo_values` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `fixtexte`;
CREATE TABLE `fixtexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)  ;

LOCK TABLES `fixtexte` WRITE;
/*!40000 ALTER TABLE `fixtexte` DISABLE KEYS */;
/*!40000 ALTER TABLE `fixtexte` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `form_insert_validation`;
CREATE TABLE `form_insert_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chkVal` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chkVal` (`chkVal`)
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_alarm`;
CREATE TABLE `homecontrol_alarm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `cam_trigger_jn` enum('J','N') NOT NULL DEFAULT 'N',
  `email` varchar(50) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  `foto_senden_jn` enum('J','N') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_alarmgeber_art`;
CREATE TABLE `homecontrol_alarmgeber_art` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=3;

LOCK TABLES `homecontrol_alarmgeber_art` WRITE;
/*!40000 ALTER TABLE `homecontrol_alarmgeber_art` DISABLE KEYS */;
INSERT INTO `homecontrol_alarmgeber_art` VALUES (1,'Sirene','pics/Sirene.png','2015-09-02 00:15:09'),(2,'Alarm-Licht','pics/Alarmlicht.png','2015-09-02 00:18:06');
/*!40000 ALTER TABLE `homecontrol_alarmgeber_art` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_alarm_geber`;
CREATE TABLE `homecontrol_alarm_geber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `x` int(10) DEFAULT '-1',
  `y` int(10) DEFAULT '-1',
  `etage_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `alarmgeber_art` int(11) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_alarm_items`;
CREATE TABLE `homecontrol_alarm_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_art`;
CREATE TABLE `homecontrol_art` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `zweite_funkid_jn` set('J','N') NOT NULL DEFAULT 'N',
  `pic` varchar(200) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=5;

LOCK TABLES `homecontrol_art` WRITE;
/*!40000 ALTER TABLE `homecontrol_art` DISABLE KEYS */;
INSERT INTO `homecontrol_art` VALUES (1,'Steckdose','N','pics/Steckdose.png','2014-11-28 12:44:33'),(2,'Jalousie','N','pics/Jalousien.png','2014-11-28 12:44:56'),(3,'Lampe','N','pics/Gluehbirne.png','2014-11-28 12:45:17'),(4,'Heizung','N','pics/Heizung.png','2014-11-28 12:45:27');
/*!40000 ALTER TABLE `homecontrol_art` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_condition`;
CREATE TABLE `homecontrol_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=7;

LOCK TABLES `homecontrol_condition` WRITE;
/*!40000 ALTER TABLE `homecontrol_condition` DISABLE KEYS */;
INSERT INTO `homecontrol_condition` VALUES (1,'<','<'),(3,'>','>'),(4,'<=','<='),(5,'>=','>='),(6,'=','=');
/*!40000 ALTER TABLE `homecontrol_condition` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_config`;
CREATE TABLE `homecontrol_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `beschreibung` text,
  `control_art` int(11) NOT NULL DEFAULT '1',
  `etage` int(3) NOT NULL DEFAULT '0',
  `zimmer` int(11) DEFAULT NULL,
  `x` int(4) NOT NULL DEFAULT '0',
  `y` int(4) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL,
  `dimmer` set('J','N') NOT NULL DEFAULT 'N',
  `sender_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=28;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_control_editor_zuordnung`;
CREATE TABLE `homecontrol_control_editor_zuordnung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=20;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_cron_parameter_values`;
CREATE TABLE `homecontrol_cron_parameter_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_id` int(11) NOT NULL,
  `config_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=60;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_control_parameter_zu_editor`;
CREATE TABLE `homecontrol_control_parameter_zu_editor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editor_param_id` int(11) NOT NULL,
  `sender_param_id` int(11) DEFAULT NULL,
  `sendereditor_zuord_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `editor_param_id` (`editor_param_id`,`sender_param_id`,`sendereditor_zuord_id`)
)   AUTO_INCREMENT=61;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_cron`;
CREATE TABLE `homecontrol_cron` (
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
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hc_cron_name_uk` (`name`)
)   AUTO_INCREMENT=10;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_cron_items`;
CREATE TABLE `homecontrol_cron_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cron_item_uk` (`cron_id`,`config_id`,`zimmer_id`,`etagen_id`)
)   AUTO_INCREMENT=13;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_cron_pause`;
CREATE TABLE `homecontrol_cron_pause` (
  `cron_id` int(11) NOT NULL,
  `pause_time` int(30) NOT NULL,
  PRIMARY KEY (`cron_id`)
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_editoren`;
CREATE TABLE `homecontrol_editoren` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `classname` varchar(50) NOT NULL,
  `descr` text NOT NULL,
  `pic` varchar(50) NOT NULL DEFAULT 'pics/transparentpixel.gif',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=5;

LOCK TABLES `homecontrol_editoren` WRITE;
/*!40000 ALTER TABLE `homecontrol_editoren` DISABLE KEYS */;
INSERT INTO `homecontrol_editoren` VALUES (1,'RGB Farbwahl','HomeControlRGBColorEditor','BenÃ¶tigt 3 Zahlen-Parameter (0 bis 255)','pics/transparentpixel.gif','2017-01-01 15:16:35'),(2,'Dimmer-Slider','HomeControlSliderVonBisEditor','BenÃ¶tigt einen Zahlen-Parameter (von/bis)','pics/transparentpixel.gif','2017-01-01 15:16:35'),(3,'Slider','HomeControlSliderVonBisEditor','','pics/transparentpixel.gif','2016-10-27 01:18:06'),(4,'Relais Funk-Switch','RelaisFunkSwitchEditor','','pics/transparentpixel.gif','2016-12-21 23:43:35');
/*!40000 ALTER TABLE `homecontrol_editoren` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_editor_parameter`;
CREATE TABLE `homecontrol_editor_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editor_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `editor_id` (`editor_id`,`name`)
)   AUTO_INCREMENT=10;

LOCK TABLES `homecontrol_editor_parameter` WRITE;
/*!40000 ALTER TABLE `homecontrol_editor_parameter` DISABLE KEYS */;
INSERT INTO `homecontrol_editor_parameter` VALUES (1,2,'value','2016-10-26 01:12:54'),(2,1,'red','2016-10-26 00:18:52'),(3,1,'green','2016-10-26 00:18:59'),(4,1,'blue','2016-10-26 00:19:10'),(5,3,'sliderValue','2016-10-27 01:18:26'),(8,4,'relaisId','2016-12-21 23:44:01'),(9,4,'relaisEinAus','2016-12-21 23:44:18');
/*!40000 ALTER TABLE `homecontrol_editor_parameter` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_editor_parameter_possible`;
CREATE TABLE `homecontrol_editor_parameter_possible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editor_parameter_id` int(11) NOT NULL,
  `param_art_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `editor_parameter_id` (`editor_parameter_id`,`param_art_id`)
)   AUTO_INCREMENT=17;

LOCK TABLES `homecontrol_editor_parameter_possible` WRITE;
/*!40000 ALTER TABLE `homecontrol_editor_parameter_possible` DISABLE KEYS */;
INSERT INTO `homecontrol_editor_parameter_possible` VALUES (1,2,1,'2016-10-26 01:08:21'),(3,3,1,'2016-10-26 01:08:46'),(4,4,1,'2016-10-26 01:08:57'),(5,5,1,'2016-10-26 01:14:16'),(6,1,9,'2016-10-26 01:22:35'),(7,5,3,'2016-10-27 01:18:37'),(8,5,9,'2016-10-27 01:19:01'),(15,8,10,'2016-12-21 23:44:08'),(16,9,12,'2016-12-21 23:44:26');
/*!40000 ALTER TABLE `homecontrol_editor_parameter_possible` ENABLE KEYS */;
UNLOCK TABLES;

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
)   AUTO_INCREMENT=5;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_modes`;
CREATE TABLE `homecontrol_modes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `selectable` set('J','N') NOT NULL,
  `beschreibung` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=5;

LOCK TABLES `homecontrol_modes` WRITE;
/*!40000 ALTER TABLE `homecontrol_modes` DISABLE KEYS */;
INSERT INTO `homecontrol_modes` VALUES (1,'default','N','Standard-Modus (Eintr?ge gelten f?r alle Modes)'),(2,'anwesend','J','Anwesenheits-Modus'),(3,'abwesend','J','Abwesenheits-Modus'),(4,'urlaub','J','Urlaubs-Modus');
/*!40000 ALTER TABLE `homecontrol_modes` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_noframe`;
CREATE TABLE `homecontrol_noframe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=2;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_regeln`;
CREATE TABLE `homecontrol_regeln` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `beschreibung` text,
  `reverse_switch` enum('J','N') NOT NULL DEFAULT 'J',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=2;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_regeln_items`;
CREATE TABLE `homecontrol_regeln_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regel_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `funkwahl` set('1','2') NOT NULL DEFAULT '1',
  `on_off` set('on','off') NOT NULL DEFAULT 'on',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `regel_id` (`regel_id`)
)   AUTO_INCREMENT=2;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sender`;
CREATE TABLE `homecontrol_sender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `etage` int(11) DEFAULT NULL,
  `zimmer` int(11) DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `default_jn` enum('J','N') NOT NULL DEFAULT 'N',
  `geaendert` timestamp NOT NULL,
  `senderTypId` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=6;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sender_parameter_values`;
CREATE TABLE `homecontrol_sender_parameter_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=96;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sender_typen`;
CREATE TABLE `homecontrol_sender_typen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=9;

LOCK TABLES `homecontrol_sender_typen` WRITE;
/*!40000 ALTER TABLE `homecontrol_sender_typen` DISABLE KEYS */;
INSERT INTO `homecontrol_sender_typen` VALUES (1,'Standard Funksender incl. BT-Switch','0000-00-00 00:00:00'),(2,'RGB Stripe Controller','2016-10-25 21:57:47'),(3,'Standard Funksender ohne BT-Switch','0000-00-00 00:00:00'),(6,'Relais-Switch Funksender','2016-12-21 23:19:51'),(7,'Leinwand','2017-12-17 15:05:21'),(8,'Eigene Funk-IDs','2017-12-17 17:08:18');
/*!40000 ALTER TABLE `homecontrol_sender_typen` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sender_typen_parameter`;
CREATE TABLE `homecontrol_sender_typen_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `senderTypId` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parameterArtId` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL,
  `fix` enum('J','N') NOT NULL DEFAULT 'J' COMMENT 'Gibt an, ob der Wert bezogen aufs Ger?t statisch ist oder in der Steuerung ?nderbar sein soll',
  `default_logic` enum('J','N') NOT NULL DEFAULT 'N' COMMENT 'Wenn J wird zum ausschalten der Wert mit -1 multipliziert. Es sollte sich also um einen Zahlenwert handeln',
  `optional` enum('J','N') NOT NULL DEFAULT 'N',
  `mandatory` enum('J','N') NOT NULL DEFAULT 'J',
  PRIMARY KEY (`id`),
  UNIQUE KEY `senderTypId` (`senderTypId`,`name`)
)   AUTO_INCREMENT=13;

LOCK TABLES `homecontrol_sender_typen_parameter` WRITE;
/*!40000 ALTER TABLE `homecontrol_sender_typen_parameter` DISABLE KEYS */;
INSERT INTO `homecontrol_sender_typen_parameter` VALUES (1,1,'schalte',7,'2017-03-03 21:12:56','J','J','N','J'),(2,1,'dimm',9,'2017-02-07 21:50:02','N','N','J','N'),(3,3,'schalte',8,'2017-02-07 21:48:30','J','J','N','J'),(4,2,'red',1,'2017-02-07 21:48:30','N','N','N','J'),(5,2,'green',1,'2017-02-07 21:48:30','N','N','N','J'),(6,2,'blue',1,'2017-02-07 21:48:30','N','N','N','J'),(8,6,'relaisId',10,'2017-02-07 21:48:30','J','N','N','J'),(10,6,'relaisStatus',12,'2017-02-07 21:50:10','N','N','J','N'),(11,7,'schalte',13,'2017-12-17 23:17:53','J','J','N','J'),(12,8,'schalte',2,'2017-12-17 17:16:30','J','N','N','J');
/*!40000 ALTER TABLE `homecontrol_sender_typen_parameter` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sender_typen_parameter_arten`;
CREATE TABLE `homecontrol_sender_typen_parameter_arten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `von` int(11) DEFAULT NULL,
  `bis` int(11) DEFAULT NULL,
  `minlen` int(11) DEFAULT NULL,
  `maxlen` int(11) DEFAULT NULL,
  `defaultValueTag` varchar(50) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=14;

LOCK TABLES `homecontrol_sender_typen_parameter_arten` WRITE;
/*!40000 ALTER TABLE `homecontrol_sender_typen_parameter_arten` DISABLE KEYS */;
INSERT INTO `homecontrol_sender_typen_parameter_arten` VALUES (1,'Zahl 0-255',0,255,NULL,NULL,NULL,'0000-00-00 00:00:00'),(2,'String (50)',NULL,NULL,0,50,NULL,'0000-00-00 00:00:00'),(3,'Zahl (0-100)',0,100,NULL,NULL,NULL,'0000-00-00 00:00:00'),(4,'OnOff',NULL,NULL,NULL,NULL,'onOff','0000-00-00 00:00:00'),(5,'Wochentage',NULL,NULL,NULL,NULL,'tage','0000-00-00 00:00:00'),(6,'Monate',NULL,NULL,NULL,NULL,'Monate','0000-00-00 00:00:00'),(7,'Funk ID incl. BT-Switch',1,386,NULL,NULL,NULL,'0000-00-00 00:00:00'),(8,'Funk ID ohne BT-Switch',1,290,NULL,NULL,NULL,'0000-00-00 00:00:00'),(9,'Dimmer',0,15,NULL,NULL,NULL,'2016-12-26 02:15:31'),(10,'Zahl 0 oder 1',0,1,NULL,NULL,NULL,'2016-12-21 23:39:58'),(11,'Zahl 0 bis 7',0,7,NULL,NULL,NULL,'2016-12-21 23:22:35'),(12,'Relais-Status',NULL,NULL,NULL,NULL,'relaisEinAus','2016-12-21 23:41:51'),(13,'Zahl 0-1023',0,1023,NULL,NULL,NULL,'2017-03-03 21:06:50');
/*!40000 ALTER TABLE `homecontrol_sender_typen_parameter_arten` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sender_typen_parameter_optional`;
CREATE TABLE `homecontrol_sender_typen_parameter_optional` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `active` set('J','N') NOT NULL DEFAULT 'J',
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=28;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sensor`;
CREATE TABLE `homecontrol_sensor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `beschreibung` text,
  `geaendert` timestamp NOT NULL,
  `lastSignal` int(30) DEFAULT NULL,
  `lastValue` float DEFAULT NULL,
  `sensor_art` int(11) NOT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `etage` int(11) DEFAULT NULL,
  `zimmer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=1000000000;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sensor_arten`;
CREATE TABLE `homecontrol_sensor_arten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status_sensor_jn` set('J','N') NOT NULL DEFAULT 'N',
  `pic` varchar(255) NOT NULL,
  `systemOnly` enum('J','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=9;

LOCK TABLES `homecontrol_sensor_arten` WRITE;
/*!40000 ALTER TABLE `homecontrol_sensor_arten` DISABLE KEYS */;
INSERT INTO `homecontrol_sensor_arten` VALUES (1,'Bewegungsmelder','J','pics/Bewegungsmelder.png','N'),(2,'Temperatur-Sensor','N','pics/TemperaturSensor.png','N'),(3,'Helligkeits-Sensor','N','pics/HelligkeitsSensor.png','N'),(4,'Regen-Sensor','J','pics/RegenSensor.png','N'),(5,'Rauchsensor','N','pics/RauchSensor.png','N'),(6,'Luftfeuchtigkeitssensor','N','pics/LuftfeuchtigkeitsSensor.png','N'),(7,'Ungelesene Emails','N','pics/mailSensor.png','J'),(8,'Luftdrucksensor','N','pics/LuftdruckSensor.png','N');
/*!40000 ALTER TABLE `homecontrol_sensor_arten` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_sensor_log`;
CREATE TABLE `homecontrol_sensor_log` (
  `sensor_id` int(11) NOT NULL,
  `value` int(9) NOT NULL,
  `update_time` int(30) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`sensor_id`,`update_time`)
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_shortcut`;
CREATE TABLE `homecontrol_shortcut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `beschreibung` text,
  `show_shortcut` enum('J','N') NOT NULL DEFAULT 'J',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=19;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_shortcut_items`;
CREATE TABLE `homecontrol_shortcut_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortcut_id` int(11) NOT NULL,
  `config_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `zimmer_id` int(11) DEFAULT NULL,
  `etagen_id` int(11) DEFAULT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shortcut_item_uk` (`shortcut_id`,`config_id`,`zimmer_id`,`etagen_id`),
  KEY `shortcut_id` (`shortcut_id`)
)   AUTO_INCREMENT=20;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_shortcut_parameter_values`;
CREATE TABLE `homecontrol_shortcut_parameter_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortcut_id` int(11) NOT NULL,
  `config_id` int(11) NOT NULL,
  `param_id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=52;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_term`;
CREATE TABLE `homecontrol_term` (
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
  `termcondition` varchar(50) DEFAULT NULL,
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
  `lastSensorintervall` int(8) DEFAULT NULL,
  `trigger_jn` set('J','N') NOT NULL DEFAULT 'J',
  PRIMARY KEY (`id`),
  KEY `trigger_id` (`trigger_id`)
)   AUTO_INCREMENT=2;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_term_trigger_type`;
CREATE TABLE `homecontrol_term_trigger_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=4;

LOCK TABLES `homecontrol_term_trigger_type` WRITE;
/*!40000 ALTER TABLE `homecontrol_term_trigger_type` DISABLE KEYS */;
INSERT INTO `homecontrol_term_trigger_type` VALUES (3,'Alarm'),(2,'Cron'),(1,'Regel');
/*!40000 ALTER TABLE `homecontrol_term_trigger_type` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_term_type`;
CREATE TABLE `homecontrol_term_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=5;

LOCK TABLES `homecontrol_term_type` WRITE;
/*!40000 ALTER TABLE `homecontrol_term_type` DISABLE KEYS */;
INSERT INTO `homecontrol_term_type` VALUES (1,'Sensorwert'),(2,'Sensor'),(3,'Zeit'),(4,'Wochentag');
/*!40000 ALTER TABLE `homecontrol_term_type` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `homecontrol_zimmer`;
CREATE TABLE `homecontrol_zimmer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `etage_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_etage_uk` (`name`,`etage_id`)
)   AUTO_INCREMENT=15;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `kopftexte`;
CREATE TABLE `kopftexte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runlink` varchar(250) NOT NULL,
  `text` text,
  `parent` varchar(50) DEFAULT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `runlink_name` (`runlink`)
)   AUTO_INCREMENT=10;

LOCK TABLES `kopftexte` WRITE;
/*!40000 ALTER TABLE `kopftexte` DISABLE KEYS */;
INSERT INTO `kopftexte` VALUES (1,'start','\r\n','Treffpunkt','2010-02-20 17:15:19'),(3,'forum','Hier im Forum habt ihr die Möglichkeit alles nach Themen-Gruppiert zu besprechen.\r\n\r\nWenn euch Themengruppen fehlen sollten, wendet euch einfach an einen der Administratoren.\r\n\r\n','Treffpunkt','2008-10-12 12:26:47'),(4,'todo','Hier seht ihr eine Übersicht aller noch ausstehenden Änderungen an der Seite.\r\n\r\nWenn euch auch noch etwas auffällt, was falsch läuft oder was an Informationen fehlt, tragt es doch einfach hier ein.\r\n\r\nDie Entwicklung wird sich schnellstmöglich damit befassen.\r\nWird der Vorschlag für sinnvoll angesehen, wird er auch so gut und so schnell es geht umgesetzt!\r\n\r\n',NULL,'2008-10-15 01:20:47'),(5,'test','testing',NULL,'0000-00-00 00:00:00'),(6,'kontakt','Wenn Sie uns eine Nachricht zukommen lassen möchten, haben Sie mit diesem Formular die möglichkeit uns eine Email schreiben.\r\nWir werden uns schnellstmöglich mit Ihnen in Verbindung setzen.\r\n',NULL,'0000-00-00 00:00:00'),(9,'bbUpload','In diesem Bereich könnt Ihr eure eigenen Bilder ins Bilderbuch einfügen.\r\n\r\n[fett]1. rechtsklick \"Add New Folder\"  um ein neues Verzeichniss anzulegen.[/fett]\r\nDer Name dieses Verzeichnisses wird später im Bilderbuch als Name der Bildergruppe angezeigt.\r\n\r\n[fett]2. das neue Verzeichniss auswählen und \"Dateien hinzufügen\"[/fett]\r\n\r\n[fett]3. In der Vorschau die Bilder überprüfen und ggf. in JPG oder PNG Konvertieren oder aus der Liste entfernen[/fett]\r\n\r\n[fett]4. Bilder \"Hochladen\"[/fett]\r\n\r\n[red][fett]Achtung![/fett] Ein späteres auswählen der angelegten Kategorie ist nach dem Hochladen nicht mehr möglich! Es können nachträglich somit keine Bilder mehr hinzugefügt werden.[/red]\r\n\r\n','Bilder','2009-03-17 00:19:25');
/*!40000 ALTER TABLE `kopftexte` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(100) DEFAULT NULL,
  `descr` longtext,
  `topic` varchar(50) DEFAULT NULL,
  `autor` varchar(50) NOT NULL DEFAULT '',
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)  ;

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` varchar(25) DEFAULT NULL,
  `User` varchar(30) NOT NULL DEFAULT '',
  `Ip` varchar(20) DEFAULT NULL,
  `Action` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  ;

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `lookupwerte`;
CREATE TABLE `lookupwerte` (
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
)   AUTO_INCREMENT=18;

LOCK TABLES `lookupwerte` WRITE;
/*!40000 ALTER TABLE `lookupwerte` DISABLE KEYS */;
INSERT INTO `lookupwerte` VALUES (1,'terminserie','serienmuster','1','Täglich','','de',0,'Y'),(2,'terminserie','serienmuster','2','Wöchentlich','','de',0,'N'),(3,'terminserie','serienmuster','3','Monatlich','','de',0,'N'),(4,'terminserie','serienmuster','4','Jährlich','','de',0,'N'),(6,'homecontrol_shortcut_items','on_off','on','Einschalten','','de',0,'N'),(7,'homecontrol_shortcut_items','on_off','off','Ausschalten','','de',0,'J'),(8,'homecontrol_cron_items','on_off','on','Einschalten','','de',0,'N'),(9,'homecontrol_cron_items','on_off','off','Ausschalten','','de',0,'J'),(10,'homecontrol_sensor_items','on_off','on','Einschalten','','de',0,'N'),(11,'homecontrol_sensor_items','on_off','off','Ausschalten','','de',0,'J'),(12,'homecontrol_shortcutview','on_off','on','Einschalten','','de',0,'N'),(13,'homecontrol_shortcutview','on_off','off','Ausschalten','','de',0,'J'),(14,'homecontrol_alarm_items','on_off','on','Einschalten','','de',0,'N'),(15,'homecontrol_alarm_items','on_off','off','Ausschalten','','de',0,'J'),(16,'homecontrol_regeln_items','on_off','off','Ausschalten','','de',0,'J'),(17,'homecontrol_regeln_items','on_off','on','Einschalten','','de',0,'N');
/*!40000 ALTER TABLE `lookupwerte` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
)   AUTO_INCREMENT=143 PACK_KEYS=0;

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (105,'Login','','?run=login',NULL,'_top','Hier k?nnen Sie sich an- oder abmelden',0,'Fussmenue','2014-07-20 20:26:34'),(115,'Geraete','Einstellungen','?menuParent=Einstellungen&run=homeconfig','admin','_top','Hier k?nnen die Ger?te konfiguriert werden.',20,'Hauptmenue','2016-10-25 21:14:20'),(116,'Shortcuts','Einstellungen','?menuParent=Einstellungen&run=shortcutConfig','admin','_top','Hier k?nnen die Schnellwahl Aktionen konfiguriert werden.',85,'Hauptmenue','2015-09-28 23:37:15'),(117,'Shortcuts','','?menuParent=Shortcuts&run=shortcuts','','_top','Konfigurierte Modi mit einem Klick',10,'Mobilmenue','2015-01-01 21:19:24'),(119,'Zeitplan','Einstellungen','?menuParent=Einstellungen&run=cronConfig','admin','_top','Hier k?nnen die automatischen Jobs konfiguriert werden.',50,'Hauptmenue','2015-09-28 23:35:34'),(120,'Sensoren','Einstellungenxx','?menuParent=Einstellungen&run=sensorConfig','xxxx','_top','Hier k?nnen die Aktionen f?r Sensoren konfiguriert werden.',30,'Hauptmenue','2016-08-17 01:52:27'),(121,'Einstellungen','','?menuParent=Einstellungen&run=mainSettings','admin','_top','Hier kann das gesamte System konfiguriert werden',200,'Kopfmenue','2015-08-27 23:37:34'),(122,'Steuerung','','?menuParent=Steuerung&run=start',NULL,'_top','',10,'Kopfmenue','2015-03-16 07:15:44'),(124,'Steuerung','','?menuParent=Steuerung&run=start','','_top','Steuerung',0,'Mobilmenue','2015-01-01 21:18:40'),(126,'Sensoren','','?menuParent=Sensoren&run=sensorList','','_top','Sensoren',5,'MobilmenueXX','2015-01-05 09:09:19'),(127,'Sensorwerte','','?menuParent=Sensorwerte&run=sensorList','','_top','Sensoren',150,'Kopfmenue','2016-09-25 18:50:42'),(128,'Timeline','','?menuParent=Einstellungen&menuParent=Timeline&run=cronView',NULL,'_top','Hier werden die Events der n?chsten 24 Stunden angezeigt und k?nnen f?r die n?chste Ausf?hrung pausiert werden.',50,'Kopfmenue','2015-08-23 19:04:06'),(129,'Sensor-Log','','?menuParent=Sensor-Log&run=sensorlogView',NULL,'_top','Hier werden die Logdaten der Sensoren angezeigt',70,'Kopfmenue','2014-11-11 22:09:42'),(130,'Gebaeude','Einstellungen','?menuParent=Einstellungen&run=gebaeudeConfig','admin','_top','Hier werden die Etagen und Raeume konfiguriert',10,'Hauptmenue','2016-10-25 21:14:24'),(131,'Basis','Einstellungen','?menuParent=Einstellungen&run=mainSettings','admin','_top','Basis-Einstellungen',1,'Hauptmenue','2015-08-25 23:06:41'),(133,'Alarmanlage','Einstellungen','?menuParent=Einstellungen&run=alarmConfig','admin','_top','Hier k?nnen die Einstellungen f?r das Verhalten der Alarmanlage konfiguriert werden.',90,'Hauptmenue','2015-08-23 19:05:47'),(134,'Cam','','?menuParent=Cam&run=camPics','admin','_top','Bewegungserkennung - Bilder',9999,'Kopfmenue','2016-09-25 13:20:21'),(135,'Timeline','','?menuParent=Einstellungen&menuParent=Timeline&run=cronView',NULL,'_top','Hier werden die Events der n?chsten 24 Stunden angezeigt und k?nnen f?r die n?chste Ausf?hrung pausiert werden.',50,'Mobilmenue','2015-08-23 19:04:06'),(136,'Automatisierung','Einstellungen','?menuParent=Einstellungen&run=automationConfig','admin','_top','In diesem Bereich werden Automatisierungen in Abh?ngigkeit der Sensorwerte konfiguriert.',85,'Hauptmenue','2015-09-28 23:35:34'),(137,'Passwort vergessen','','?run=userRequestPw',NULL,'_top','',9999,'need','2015-10-15 22:34:29'),(138,'','','?run=changeMyProfile',NULL,'_top','',9999,'need','0000-00-00 00:00:00'),(139,'cc','','run=userpicUpload',NULL,'_top','',9999,'need','0000-00-00 00:00:00'),(141,'Parameter','Einstellungen','?menuParent=Einstellungen&run=parameterConfig','admin','_top','',190,'Hauptmenue','2016-10-25 23:43:29'),(142,'Editoren','Einstellungen','?menuParent=Einstellungen&run=editorConfig','admin','_top','',200,'Hauptmenue','2016-10-25 21:14:35');
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `pageconfig`;
CREATE TABLE `pageconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text,
  `page_id` int(11) NOT NULL DEFAULT '0',
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `label` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `page_id` (`page_id`)
)   AUTO_INCREMENT=41;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `programm_gruppen`;
CREATE TABLE `programm_gruppen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `text` varchar(250) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=7;

LOCK TABLES `programm_gruppen` WRITE;
/*!40000 ALTER TABLE `programm_gruppen` DISABLE KEYS */;
INSERT INTO `programm_gruppen` VALUES (3,'Bilder','Alles was zum Bilderbuch gehört','0000-00-00 00:00:00'),(4,'Einstellungen','Einstellungsmasken und Administrative Links','0000-00-00 00:00:00'),(5,'Allgemeines','Hier kommt alles rein, was generell zur Verfügung steht','0000-00-00 00:00:00'),(6,'Mein Profil','Alles rund ums Userprofil','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `programm_gruppen` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `public_vars`;
CREATE TABLE `public_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gruppe` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `titel` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `sortnr` int(5) NOT NULL,
  PRIMARY KEY (`id`)
)   AUTO_INCREMENT=6;

LOCK TABLES `public_vars` WRITE;
/*!40000 ALTER TABLE `public_vars` DISABLE KEYS */;
INSERT INTO `public_vars` VALUES (1,'texte','impressum','Inhalt des Onlineangebotes','Der Autor übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche gegen den Autor, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen, sofern seitens des Autors kein nachweislich vorsätzliches oder grob fahrlässiges Verschulden vorliegt. Alle Angebote sind freibleibend und unverbindlich. Der Autor behält es sich ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.',1),(2,'texte','impressum','Verweise und Links','Bei direkten oder indirekten Verweisen auf fremde Webseiten (\"Hyperlinks\"), die außerhalb des Verantwortungsbereiches des Autors liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern. Der Autor erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige Gestaltung, die Inhalte oder die Urheberschaft der gelinkten/verknüpften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller gelinkten /verknüpften Seiten, die nach der Linksetzung verändert wurden. Diese Feststellung gilt für alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie für Fremdeinträge in vom Autor eingerichteten Gästebüchern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe möglich sind. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.\r\n',2),(3,'texte','impressum','Urheber- und Kennzeichenrecht','Der Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Grafiken, Tondokumente, Videosequenzen und Texte zu beachten, von ihm selbst erstellte Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen. Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschützten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind! Das Copyright für veröffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten. Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Autors nicht gestattet.',3),(4,'texte','impressum','Datenschutz','Sofern innerhalb des Internetangebotes die Möglichkeit zur Eingabe persönlicher oder geschäftlicher Daten (Kontodaten, Namen, Anschriften) besteht, so erfolgt die Preisgabe dieser Daten seitens des Nutzers auf ausdrücklich freiwilliger Basis. Die Inanspruchnahme und Bezahlung aller angebotenen Dienste ist - soweit technisch möglich und zumutbar - auch ohne Angabe solcher Daten bzw. unter Angabe anonymisierter Daten oder eines Pseudonyms gestattet. Die Nutzung der im Rahmen des Impressums oder vergleichbarer Angaben veröffentlichten Kontaktdaten wie Postanschriften, Telefon- und Faxnummern sowie Emailadressen durch Dritte zur Übersendung von nicht ausdrücklich angeforderten Informationen ist nicht gestattet. Rechtliche Schritte gegen die Versender von sogenannten Spam-Mails bei Verstössen gegen dieses Verbot sind ausdrücklich vorbehalten.',4),(5,'texte','impressum','Rechtswirksamkeit','Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollständig entsprechen sollten, bleiben die übrigen Teile des Dokumentes in ihrem Inhalt und ihrer Gültigkeit davon unberührt.',5);
/*!40000 ALTER TABLE `public_vars` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `run_links`;
CREATE TABLE `run_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `link` varchar(250) NOT NULL,
  `target` varchar(50) NOT NULL DEFAULT 'mainpage',
  `parent` varchar(50) NOT NULL,
  `prog_grp_id` int(11) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk` (`name`,`parent`)
)   AUTO_INCREMENT=73;

LOCK TABLES `run_links` WRITE;
/*!40000 ALTER TABLE `run_links` DISABLE KEYS */;
INSERT INTO `run_links` VALUES (1,'start','includes/Startseite.php','mainpage','',0,'2010-02-20 15:16:00'),(2,'mobile_start','mobile_includes/Startseite.php','mainpage','',0,'2010-02-20 15:16:00'),(12,'impressum','includes/Impressum.php','mainpage','',0,'0000-00-00 00:00:00'),(19,'changeMyProfile','includes/user/user_change.php','mainpage','',6,'2008-09-11 21:49:04'),(20,'doUserpicUpload','includes/user/userpic_upload2.php','mainpage','',0,'0000-00-00 00:00:00'),(21,'userpicUpload','includes/user/userpic_upload.php','mainpage','',0,'0000-00-00 00:00:00'),(22,'userRequestPw','includes/user/user_request_pw.php','mainpage','',0,'0000-00-00 00:00:00'),(24,'showUserList','includes/user/user_liste.php','mainpage','',0,'2010-02-20 15:16:00'),(29,'showUserProfil','includes/user/show_userprofil.php','mainpage','',0,'0000-00-00 00:00:00'),(30,'userListe','includes/user/user_liste.php','mainpage','',0,'0000-00-00 00:00:00'),(36,'login','includes/Login.php','mainpage','',0,'2008-11-16 21:08:45'),(41,'redaktionsgruppe','includes/empty.php','mainpage','',1,'2010-02-20 15:16:00'),(50,'imageUploaderPopup','includes/ImageUploaderPopup.php','mainpage','',0,'2009-06-27 09:01:28'),(51,'homeconfig','includes/ControlConfig.php','mainpage','',0,'0000-00-00 00:00:00'),(52,'shortcutConfig','includes/ShortcutConfig.php','mainpage','',0,'0000-00-00 00:00:00'),(53,'shortcuts','includes/ShortcutSidebar.php','mainpage','',0,'2012-12-31 01:39:15'),(54,'cronConfig','includes/CronConfig.php','mainpage','',0,'0000-00-00 00:00:00'),(55,'sensorConfig','includes/SensorConfig.php','mainpage','',0,'0000-00-00 00:00:00'),(56,'sensoren','includes/SensorenEdit.php','mainpage','',0,'0000-00-00 00:00:00'),(57,'sensorList','includes/Sensoren.php','mainpage','',0,'0000-00-00 00:00:00'),(58,'cronView','includes/CronView.php','mainpage','',0,'0000-00-00 00:00:00'),(59,'sensorlogView','includes/SensorLogViewer.php','mainpage','',0,'0000-00-00 00:00:00'),(60,'gebaeudeConfig','includes/GebaeudeConfig.php','mainpage','',0,'2015-08-23 18:58:17'),(61,'mainSettings','includes/MainSettings.php','mainpage','',0,'0000-00-00 00:00:00'),(62,'network','includes/NetworkConfig.php','mainpage','',0,'0000-00-00 00:00:00'),(63,'alarmConfig','includes/AlarmConfig.php','mainpage','',0,'0000-00-00 00:00:00'),(64,'camPics','includes/CamPics.php','mainpage','',0,'0000-00-00 00:00:00'),(66,'automationConfig','includes/AutomationConfig.php','mainpage','',0,'2010-02-20 15:16:00'),(67,'alarmgeberConfig','includes/AlarmgeberConfig.php','mainpage','',0,'2010-02-20 15:16:00'),(68,'mobile_shortcuts','mobile_includes/ShortcutSidebar.php','mainpage','',0,'2010-02-20 15:16:00'),(69,'logView','includes/LogView.php','mainpage','',0,'0000-00-00 00:00:00'),(70,'parameterConfig','includes/ParameterConfig.php','mainpage','',0,'2016-10-25 21:11:56'),(71,'editorConfig','includes/EditorenConfig.php','mainpage','',0,'2016-10-25 23:21:00'),(72,'mobile_cronView','mobile_includes/CronView.php','mainpage','',0,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `run_links` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `site-enter`;
CREATE TABLE `site-enter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)   AUTO_INCREMENT=25;

LOCK TABLES `site-enter` WRITE;
/*!40000 ALTER TABLE `site-enter` DISABLE KEYS */;
INSERT INTO `site-enter` VALUES (1,'Box in','0'),(2,'Box Out','1'),(3,'Circle in','2'),(4,'Circle out','3'),(5,'Wipe up','4'),(6,'Wipe down','5'),(7,'Wipe right','6'),(8,'Wipe left','7'),(9,'Vertical Blinds','8'),(10,'Horizontal Blinds','9'),(11,'Checkerboard across','10'),(12,'Checkerboard down','11'),(13,'Random Disolve','12'),(14,'Split vertical in','13'),(15,'Split vertical out','14'),(16,'Split horizontal in','15'),(17,'Split horizontal out','16'),(18,'Strips left down','17'),(19,'Strips left up','18'),(20,'Strips right down','19'),(21,'Strips right up','20'),(22,'Random bars horizontal','21'),(23,'Random bars vertical','22'),(24,'Random','23');
/*!40000 ALTER TABLE `site-enter` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `smileys`;
CREATE TABLE `smileys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Title` (`title`),
  UNIQUE KEY `Link` (`link`)
)   AUTO_INCREMENT=27;

LOCK TABLES `smileys` WRITE;
/*!40000 ALTER TABLE `smileys` DISABLE KEYS */;
INSERT INTO `smileys` VALUES (1,':-)','pics/smileys/grins.gif'),(4,':-P','pics/smileys/baeh.gif'),(11,'cry','pics/smileys/crying.gif'),(13,'lol','pics/smileys/biglaugh.gif'),(16,':-@','pics/smileys/motz.gif'),(17,':-O','pics/smileys/confused.gif'),(20,':-D','pics/smileys/auslach.gif'),(26,'rofl','pics/smileys/rofl.gif');
/*!40000 ALTER TABLE `smileys` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(15) NOT NULL DEFAULT '',
  `html` varchar(150) NOT NULL DEFAULT '',
  `btn` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`),
  KEY `tag1` (`tag`)
)   AUTO_INCREMENT=38;

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'cybi','<a href=\'http://www.cyborgone.de\' target=\'cybi\'><img src=\'http://cyborgone.de/pics/banner13.gif\' width=\'200\' border=\'0\'></a>','n'),(2,'fett','<b>','J'),(3,'/fett','</b>','J'),(4,'unter','<u>','J'),(5,'/unter','</u>','J'),(6,'normal','<font size=\'2\'>','J'),(7,'/normal','</font>','J'),(8,'klein','<font size=\'1\'>','J'),(9,'/klein','</font>','J'),(10,'mittel','<font size=\'3\'>','J'),(11,'/mittel','</font>','J'),(12,'blue','<font color=\'blue\'>','J'),(13,'red','<font color=\'red\'>','J'),(14,'green','<font color=\'green\'>','J'),(15,'gray','<font color=\'gray\'>','J'),(16,'/gray','</font>','J'),(17,'/red','</font>','J'),(18,'/blue','</font>','J'),(19,'/green','</font>','J'),(20,'quote','<table border=\'1\' cellpadding=\'0\' cellspacing=\'0\'><tr><td class=\'zitat\'><i>','N'),(21,'hr','<hr>','J'),(22,'/quote','</i></td></tr></table>','N'),(23,'changed','<br><br><i><u><b>Geändert:','N'),(24,'/changed','</b></u></i>','N'),(25,'bild_500','<img src=\'','J'),(26,'/bild_500','\' width=\'500\'>','J'),(28,'bild_150','<img src=\'','J'),(29,'/bild_150','\' width=\'150\'>','J'),(30,'code','<textarea cols=\'70\' rows=\'10\' readonly>','J'),(31,'/code','</textarea>','N'),(32,'yellow','<font color=\'yellow\'>','N'),(33,'/yellow','</font>','N'),(34,'groß','<font size=\'4\'>','J'),(35,'/groß','</font>','J'),(36,'mitte','<center>',NULL),(37,'/mitte','</center>',NULL);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `update_log`;
CREATE TABLE `update_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `descr` text NOT NULL,
  `geaendert` timestamp NOT NULL,
  PRIMARY KEY (`id`)
)  ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `Pw` varchar(255) NOT NULL DEFAULT '-',
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
  `aktiv` enum('J','N') NOT NULL DEFAULT 'N',
  `activationString` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `User` (`User`),
  KEY `Name` (`Name`(8))
)   AUTO_INCREMENT=2;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `userstatus`;
CREATE TABLE `userstatus` (
  `id` varchar(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
)  ;

LOCK TABLES `userstatus` WRITE;
/*!40000 ALTER TABLE `userstatus` DISABLE KEYS */;
INSERT INTO `userstatus` VALUES ('admin','Administrator'),('gast','Gast'),('user','Hauptbenutzer');
/*!40000 ALTER TABLE `userstatus` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO,MYSQL40' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `beschreibung` varchar(250) NOT NULL,
  `pic` varchar(150) NOT NULL,
  `geaendert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Name` (`name`)
)  ;

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

ALTER TABLE action_log MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE form_insert_validation MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_alarm MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_alarm_geber MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_alarm_items MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_config MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_cron MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_cron_items MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_etagen MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_noframe MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_regeln MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_regeln_items MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_sender MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_sensor MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_shortcut MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_shortcut_items MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_term MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE homecontrol_zimmer MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
ALTER TABLE update_log MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
INSERT INTO user (id, Vorname, Nachname, Name, Geburtstag, Strasse, Plz, Ort, Email, Telefon, Fax, Handy, Icq, Aim, Homepage, User, Pw, Nation, Status, user_group_id, Newsletter, Signatur, Lastlogin, Posts, Beschreibung, pic, pnnotify, autoforumnotify, geaendert, emailJN, icqJN, telefonJN, aktiv, activationString)             VALUES (1, 'Admini', 'Istrator', 'Admini Istrator', '0000-00-00', '-', '-', '-', '', '-', '', '-', '', NULL, '', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin', 1, 'true', '', '2016-09-05 11:20:17', 0, NULL, 'unknown.jpg', 'Y', 'Y', '2016-09-05 09:20:17', 'N', 'N', 'N', 'J', NULL);
ALTER TABLE user MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
INSERT INTO pageconfig VALUES
(1, 'pagetitel', 'Haussteuerung', 0, '2015-09-28 00:34:39', NULL),
(2, 'pageowner', 'SEITENINHABER', 0, '2013-01-08 02:32:07', NULL),
(3, 'background_pic', '', 0, '2008-09-18 15:19:00', NULL),
(4, 'banner_pic', 'pics/banner/13.jpg', 0, '2008-11-13 23:20:50', NULL),
(5, 'sessiontime', '0', 0, '0000-00-00 00:00:00', NULL),
(6, 'logging_aktiv', 'true', 0, '0000-00-00 00:00:00', NULL),
(7, 'debugoutput_aktiv', 'false', 0, '2008-10-25 11:37:21', NULL),
(8, 'classes_autoupdate', 'false', 0, '0000-00-00 00:00:00', NULL),
(9, 'pagedeveloper', 'Daniel Scheidler\r\n\r\n[fett]Email:[/fett]    support@smarthomeyourself.de\r\n', 0, '2013-01-08 02:39:44', NULL),
(10, 'pagedesigner', 'Daniel Scheidler', 0, '2013-01-08 02:40:04', NULL),
(11, 'background_repeat', 'repeat', 0, '0000-00-00 00:00:00', NULL),
(12, 'hauptmenu_button_image', 'pics/hauptmenu_button.jpg', 0, '0000-00-00 00:00:00', NULL),
(13, 'max_rowcount_for_dbtable', '25', 0, '2008-10-14 23:14:17', NULL),
(14, 'hauptmenu_button_image_hover', 'pics/hauptmenu_button_hover.jpg', 0, '2008-10-20 07:18:56', NULL),
(15, 'suchbegriffe', 'Haussteuerung, Arduino, Funk', 0, '2012-10-29 12:13:48', NULL),
(16, 'arduino_url', '192.168.1.12/rawCmd', 0, '2014-09-10 21:15:01', NULL),
(17, 'google_maps_API_key', '', 0, '2013-01-08 02:41:58', NULL),
(18, 'NotifyTargetMail', '', 0, '2015-08-30 23:10:27', 'Benachrichtigungs-Email'),
(19, 'KontaktformularTargetMail', '', 0, '2013-01-08 02:33:52', NULL),
(20, 'timezoneadditional', '2', 0, '0000-00-00 00:00:00', NULL),
(21, 'loginForSwitchNeed', 'J', 0, '2016-09-06 16:56:48', 'Login zum schalten ben&ouml;tigt'),
(22, 'abwesendSimulation', 'N', 0, '2016-08-10 20:53:28', 'Anwesenheits-Simulation'),
(23, 'abwesendMotion', 'N', 0, '2015-08-26 22:46:27', 'Kamera-Bewegungserkennung'),
(24, 'anwesendMotion', 'N', 0, '2016-08-10 20:50:02', 'Kamera-Bewegungserkennung'),
(25, 'sessionDauer', '6000', 0, '2015-08-29 17:06:18', 'Zeit bis zum erneuten Login in Sekunden'),
(26, 'motionDauer', '9', 0, '2015-09-28 00:37:09', 'Tage die Bewegungs-Bilder behalten'),
(27, 'sensorlogDauer', '60', 0, '2015-08-28 07:03:00', 'Tage die Sensor-Log Daten behalten'),
(28, 'abwesendAlarm', 'N', 0, '2016-08-10 20:50:09', NULL),
(29, 'currentMode', '2', 0, '2015-10-11 17:18:53', NULL),
(30, 'timelineDuration', '3', 0, '2015-09-28 00:33:59', 'Anzahl Tage in Timeline'),
(31, 'loginForTimelinePauseNeed', 'J', 0, '2016-05-31 09:22:58', 'Login zum pausieren in Timeline'),
(32, 'btSwitchActive', 'J', 0, '2016-09-02 22:37:16', 'BT-Switch im Einsatz?'),
(33, 'loginExternOnly', 'J', 0, '0000-00-00 00:00:00', 'Login nur von extern'),
(34, 'switchButtonsOnIconActive', 'J', 0, '0000-00-00 00:00:00', 'Buttons in Steuerung sichtbar?'),
(35, 'gmailAdress', '', 0, '0000-00-00 00:00:00', 'Email fur Gmail Abfragen'),
(36, 'gmailAppPassword', '', 0, '0000-00-00 00:00:00', 'App-Passwort fur Gmail Abfragen'),
(37, 'haBridgeActive', 'N', 0, '0000-00-00 00:00:00', 'Gibt an, ob die HA-Bridge installiert ist.'),
(38, 'haBridgePath', '/services/haBridge/', 0, '0000-00-00 00:00:00', 'Pfad zur HA-Bridge Installation'),
(39, 'showNamesInUi', 'N', 0, '0000-00-00 00:00:00', 'Gibt an ob Namen in der Steuerung angezeigt werden'),
(40, 'autoRefreshTime', '20', 0, '0000-00-00 00:00:00', 'Automatischer PageReload in Sek. (0=aus)');
ALTER TABLE pageconfig MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
INSERT INTO homecontrol_sensor (id, name, beschreibung, geaendert, lastSignal, lastValue, sensor_art, x, y, etage, zimmer) VALUES 
('999999999', 'UnreadMailsInInbox', 'Anzahl der Mails im G-Mail Posteingang',  '2016-09-22 00:00:00', 0, 0, 7, 0, 0, null, null);
create view homecontrol_cronview as 
SELECT 'Montag'  wochentag, 1 tagnr, hc.* FROM homecontrol_cron hc WHERE montag = 'J'
union SELECT 'Dienstag', 2, hc1.* FROM homecontrol_cron hc1 WHERE dienstag = 'J'
union SELECT 'Mittwoch', 3, hc2.* FROM homecontrol_cron hc2 WHERE mittwoch = 'J'
union SELECT 'Donnerstag', 4, hc3.* FROM homecontrol_cron hc3 WHERE donnerstag = 'J'
union SELECT 'Freitag', 5, hc4.* FROM homecontrol_cron hc4 WHERE freitag = 'J'
union SELECT 'Samstag', 6, hc5.* FROM homecontrol_cron hc5 WHERE samstag = 'J'
union SELECT 'Sonntag', 0, hc6.* FROM homecontrol_cron hc6 WHERE sonntag = 'J';
create view homecontrol_regel_item_view as
SELECT CONCAT( r.id,  '-', i.id ) id, r.id regel_id, r.name name, r.beschreibung beschreibung, i.config_id config_id, i.art_id art_id, i.zimmer_id zimmer_id, i.etagen_id etagen_id, i.on_off on_off
FROM homecontrol_regeln r, homecontrol_regeln_items i
WHERE r.id = i.regel_id;
create view homecontrol_shortcutview as 
select concat(s.id,'-', c.id) id,
       s.id shortcut_id, s.name shortcut_name, s.beschreibung beschreibung,
       i.id item_id, i.art_id art, c.zimmer, z.etage_id, 
       c.id config_id, c.name name, c.x, c.y, a.pic, c.geaendert geaendert
from homecontrol_shortcut s, 
     homecontrol_shortcut_items i, 
     homecontrol_config c  LEFT JOIN 
     homecontrol_zimmer z ON z.id = c.zimmer  LEFT JOIN 
     homecontrol_art a ON c.control_art = a.id 
WHERE i.shortcut_id = s.id
  AND (        (c.id = i.config_id OR i.config_id is null)
          AND  (c.control_art = i.art_id OR i.art_id is null)
          AND  (c.zimmer = i.zimmer_id OR i.zimmer_id is null)
          AND  (c.etage = i.etagen_id OR i.etagen_id is null)
     )
  AND (
    (
        (i.config_id IS NULL AND i.zimmer_id IS NOT NULL)
        AND NOT EXISTS( 
            SELECT 'X' FROM homecontrol_shortcut_items iZ WHERE iZ.shortcut_id = s.id AND iZ.config_id = c.id
        )
    ) OR (i.config_id IS NOT NULL OR i.zimmer_id IS NULL)
  )
  AND (
    (
        (i.config_id IS NULL AND i.etagen_id IS NOT NULL)
        AND NOT EXISTS( 
            SELECT 'X' FROM homecontrol_shortcut_items iZ WHERE iZ.shortcut_id = s.id AND (iZ.config_id = c.id OR iZ.zimmer_id = i.zimmer_id)
        )
    ) OR (i.config_id IS NOT NULL OR i.zimmer_id IS NOT NULL OR i.etagen_id IS NULL)
  );

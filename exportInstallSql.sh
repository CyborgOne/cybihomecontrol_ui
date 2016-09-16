echo "Geben Sie das DB-Passwort für root an"
read databasePasswd

echo "Geben Sie den DB-Host an"
read databaseHost

echo "Geben Sie den Datenbanknamen an"
read databaseName

now=$(date +"%Y_%m_%d")
filename="installation_sql_$now.sql"

echo  > $filename

####################################################
# Export aller Tabellen (einzeln)
#
# -d wird verwendet um Datenexport zu vermeiden
#    (nur Strukturen=DDL exportieren)
####################################################

mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName action_log                     >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName colors                         >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName dbcombos                       >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName default_combo_values           >> $filename
#mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName default_pageconfig  |\sed -e 's/`default_pageconfig`/`pageconfig`/'   >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName fixtexte                       >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName form_insert_validation         >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_alarm              >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_alarmgeber_art     >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_alarm_geber        >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_alarm_items        >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_art                >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_condition          >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_config             >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_cron               >> $filename
# mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_cronview         >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_cron_items         >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_cron_pause         >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_etagen             >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_modes              >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_noframe            >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_regeln             >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_regeln_items       >> $filename
#mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_regel_item_view   >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_sensor             >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_sensor_arten       >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_sensor_log         >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_shortcut           >> $filename
#mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_shortcutview      >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_shortcut_items     >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_term               >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_term_trigger_type  >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_term_type          >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName homecontrol_zimmer             >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName kopftexte                      >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName links                          >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName log                            >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName lookupwerte                    >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName menu                           >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName pageconfig                     >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName programm_gruppen               >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName public_vars                    >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName run_links                      >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName site-enter                     >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName smileys                        >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName tags                           >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName update_log                     >> $filename
mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName user                           >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName userstatus                     >> $filename
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName user_groups                    >> $filename


########################################################
#
# Manuelle Inserts 
# - Standard-Admin User
# - PageConfig
#
########################################################

# Admin User
echo "INSERT INTO user (id, Vorname, Nachname, Name, Geburtstag, Strasse, Plz, Ort, Email, Telefon, Fax, Handy, Icq, Aim, Homepage, User, Pw, Nation, Status, user_group_id, Newsletter, Signatur, Lastlogin, Posts, Beschreibung, pic, pnnotify, autoforumnotify, geaendert, emailJN, icqJN, telefonJN, Level, EP, Gold, Holz, Erz, Felsen, Wasser, Nahrung, aktiv, activationString, angelegt, clan_id, rasse_id)             VALUES (1, 'Admini', 'Istrator', 'Admini Istrator', '0000-00-00', '-', '-', '-', '', '-', '', '-', '', NULL, '', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin', 1, 'true', '', '2016-09-05 11:20:17', 0, NULL, 'unknown.jpg', 'Y', 'Y', '2016-09-05 09:20:17', 'N', 'N', 'N', 0, 0, 0, 0, 0, 0, 0, 0, 'J', NULL, '0000-00-00', NULL, 1);" 		>> $filename
echo "ALTER TABLE user MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;" >> $filename

# PageConfig
echo "INSERT INTO pageconfig VALUES" >> $filename
echo "(1, 'pagetitel', 'Haussteuerung', 0, '2015-09-28 00:34:39', NULL)," >> $filename
echo "(2, 'pageowner', 'SEITENINHABER', 0, '2013-01-08 02:32:07', NULL)," >> $filename
echo "(3, 'background_pic', '', 0, '2008-09-18 15:19:00', NULL)," >> $filename
echo "(4, 'banner_pic', 'pics/banner/13.jpg', 0, '2008-11-13 23:20:50', NULL)," >> $filename
echo "(5, 'sessiontime', '0', 0, '0000-00-00 00:00:00', NULL)," >> $filename
echo "(6, 'logging_aktiv', 'true', 0, '0000-00-00 00:00:00', NULL)," >> $filename
echo "(7, 'debugoutput_aktiv', 'false', 0, '2008-10-25 11:37:21', NULL)," >> $filename
echo "(11, 'classes_autoupdate', 'false', 0, '0000-00-00 00:00:00', NULL)," >> $filename
echo "(12, 'pagedeveloper', 'Daniel Scheidler\r\n\r\n[fett]Email:[/fett]    support@smarthomeyourself.de\r\n', 0, '2013-01-08 02:39:44', NULL)," >> $filename
echo "(13, 'pagedesigner', 'Daniel Scheidler', 0, '2013-01-08 02:40:04', NULL)," >> $filename
echo "(15, 'background_repeat', 'repeat', 0, '0000-00-00 00:00:00', NULL)," >> $filename
echo "(14, 'hauptmenu_button_image', 'pics/hauptmenu_button.jpg', 0, '0000-00-00 00:00:00', NULL)," >> $filename
echo "(16, 'max_rowcount_for_dbtable', '25', 0, '2008-10-14 23:14:17', NULL)," >> $filename
echo "(17, 'hauptmenu_button_image_hover', 'pics/hauptmenu_button_hover.jpg', 0, '2008-10-20 07:18:56', NULL)," >> $filename
echo "(18, 'suchbegriffe', 'Haussteuerung, Arduino, Funk', 0, '2012-10-29 12:13:48', NULL)," >> $filename
echo "(22, 'arduino_url', '192.168.1.12/rawCmd', 0, '2014-09-10 21:15:01', NULL)," >> $filename
echo "(19, 'google_maps_API_key', '', 0, '2013-01-08 02:41:58', NULL)," >> $filename
echo "(20, 'NotifyTargetMail', '', 0, '2015-08-30 23:10:27', 'Benachrichtigungs-Email')," >> $filename
echo "(21, 'KontaktformularTargetMail', '', 0, '2013-01-08 02:33:52', NULL)," >> $filename
echo "(23, 'timezoneadditional', '2', 0, '0000-00-00 00:00:00', NULL)," >> $filename
echo "(24, 'loginForSwitchNeed', 'J', 0, '2016-09-06 16:56:48', 'Login zum schalten ben&ouml;tigt')," >> $filename
echo "(26, 'abwesendSimulation', 'N', 0, '2016-08-10 20:53:28', 'Anwesenheits-Simulation')," >> $filename
echo "(27, 'abwesendMotion', 'N', 0, '2015-08-26 22:46:27', 'Kamera-Bewegungserkennung')," >> $filename
echo "(28, 'anwesendMotion', 'N', 0, '2016-08-10 20:50:02', 'Kamera-Bewegungserkennung')," >> $filename
echo "(29, 'sessionDauer', '6000', 0, '2015-08-29 17:06:18', 'Zeit bis zum erneuten Login in Sekunden')," >> $filename
echo "(30, 'motionDauer', '9', 0, '2015-09-28 00:37:09', 'Tage die Bewegungs-Bilder behalten')," >> $filename
echo "(31, 'sensorlogDauer', '60', 0, '2015-08-28 07:03:00', 'Tage die Sensor-Log Daten behalten')," >> $filename
echo "(32, 'abwesendAlarm', 'N', 0, '2016-08-10 20:50:09', NULL)," >> $filename
echo "(33, 'currentMode', '2', 0, '2015-10-11 17:18:53', NULL)," >> $filename
echo "(34, 'timelineDuration', '3', 0, '2015-09-28 00:33:59', 'Gibt an, wie viele Tage in der Timeline angezeigt werden sollen')," >> $filename
echo "(35, 'loginForTimelinePauseNeed', 'J', 0, '2016-05-31 09:22:58', 'Gibt an, ob zum pausieren in der Timeline ein Login notwendig ist.')," >> $filename
echo "(36, 'btSwitchActive', 'J', 0, '2016-09-02 22:37:16', 'Gibt an, ob ein BT-Switch eingesetzt wird/werden soll')," >> $filename
echo "(37, 'loginExternOnly', 'J', 0, '0000-00-00 00:00:00', 'Wenn aktiviert, ist der Login zum schalten nur von Extern (abweichene IP Range) notwendig.');" >> $filename

echo "ALTER TABLE pageconfig MODIFY id int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;" >> $filename




echo "Geben Sie das DB-Passwort für root an"
read databasePasswd

echo "Geben Sie den DB-Host an"
read databaseHost

echo "Geben Sie den Datenbanknamen an"
read databaseName

now=$(date +"%m_%d_%Y")
filename="installation_sql_$now.log"

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
mysqldump    --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName default_pageconfig  |\sed -e 's/`default_pageconfig`/`pageconfig`/'   >> $filename
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
#mysqldump -d --skip-opt --compatible=mysql40 --skip-comments --skip-set-charset --skip-tz-utc -u root -p$databasePasswd -h $databaseHost $databaseName pageconfig                     >> $filename
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
echo "INSERT INTO `user` (`id`, `Vorname`, `Nachname`, `Name`, `Geburtstag`, `Strasse`, `Plz`, `Ort`, `Email`, `Telefon`, `Fax`, `Handy`, `Icq`, `Aim`, `Homepage`, `User`, `Pw`, `Nation`, `Status`, `user_group_id`, `Newsletter`, `Signatur`, `Lastlogin`, `Posts`, `Beschreibung`, `pic`, `pnnotify`, `autoforumnotify`, `geaendert`, `emailJN`, `icqJN`, `telefonJN`, `Level`, `EP`, `Gold`, `Holz`, `Erz`, `Felsen`, `Wasser`, `Nahrung`, `aktiv`, `activationString`, `angelegt`, `clan_id`, `rasse_id`)             VALUES (1, 'Admini', 'Istrator', 'Admini Istrator', '0000-00-00', '-', '-', '-', '', '-', '', '-', '', NULL, '', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin', 1, 'true', '', '2016-09-05 11:20:17', 0, NULL, 'unknown.jpg', 'Y', 'Y', '2016-09-05 09:20:17', 'N', 'N', 'N', 0, 0, 0, 0, 0, 0, 0, 0, 'J', NULL, '0000-00-00', NULL, 1);" 		>> $filename

# PageConfig

			
drop view if exists homecontrol_cronview;

create view homecontrol_cronview as 
SELECT 'Montag'  wochentag, 1 tagnr, hc.* FROM `homecontrol_cron` hc WHERE montag = 'J'
union SELECT 'Dienstag', 2, hc1.* FROM `homecontrol_cron` hc1 WHERE dienstag = 'J'
union SELECT 'Mittwoch', 3, hc2.* FROM `homecontrol_cron` hc2 WHERE mittwoch = 'J'
union SELECT 'Donnerstag', 4, hc3.* FROM `homecontrol_cron` hc3 WHERE donnerstag = 'J'
union SELECT 'Freitag', 5, hc4.* FROM `homecontrol_cron` hc4 WHERE freitag = 'J'
union SELECT 'Samstag', 6, hc5.* FROM `homecontrol_cron` hc5 WHERE samstag = 'J'
union SELECT 'Sonntag', 0, hc6.* FROM `homecontrol_cron` hc6 WHERE sonntag = 'J'







# 0 (für Sonntag) bis 6 (für Samstag)
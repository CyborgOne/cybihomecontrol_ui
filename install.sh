#!/bin/bash
#  Installations-Skript für den HomeControl Server
#
#  
#
#  (c) by Daniel Scheidler             Aug 2015

sudo apt-get update


#
#  APACHE, PHP, MySQL
#
sudo apt-get install apache2 php5 
sudo apt-get install libapache2-mod-php5 libapache2-mod-perl2 php5 php5-cli php5-common php5-curl php5-dev php5-gd php5-imap php5-ldap php5-mhash php-pear php-apc
sudo a2enmod rewrite
cp installFiles/etc/apache2/sites-enabled/000-default  /etc/apache2/sites-enabled/000-default

# sudo visudo
# Am Ende der Datei fügen wir hierzu die folgende Zeile ein:
#  www-data ALL=(ALL) NOPASSWD: ALL

#
#  PHPMyAdmin
#
apt-get install libapache2-mod-auth-mysql phpmyadmin
ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf.d/phpmyadmin.conf

echo "extension=mysql.so" >> /etc/php5/apache2/php.ini

/etc/init.d/apache2 reload

#
#  SAMBA
#
sudo apt-get install samba samba-common-bin
echo "Geben sie ein Passwort fuer den Samba Benutzer 'pi' ein"
sudo smbpasswd -a pi

cp installFiles/etc/samba/smb.conf /etc/samba/smb.conf

#
#  WEBSEITE
#
apt-get install git
cd /var/www
rm * -R
git clone https://github.com/CyborgOne/cybihomecontrol_ui.git .

#
#  DATENBANK
#
echo "Geben Sie das Passwort für den root-Datenbankbenutzer ein"
sudo apt-get install mysql-server
sudo apt-get install php5-mysql php5-odbc mysql-client php5-mysql
mysql -u root -p homecontrol < homecontrol.sql

#nano /var/www/config/dbConnect.php

#
#  CRONS
#
mkdir -p /etc/cron.manual
cp installFiles/crons/* /etc/cron.manual

chmod +x /ect/cron.manual/homecontrol_cron
chmod +x /ect/cron.manual/homecontrol_motion_cleanup
chmod +x /ect/cron.manual/homecontrol_log_cleanup

#crons aktivieren
(crontab -l 2>/dev/null; echo "*/1 *  * * * /etc/cron.manual/homecontrol_cron >> /var/log/homecontrol_cron") | crontab -
(crontab -l 2>/dev/null; echo "0   0  * * * /etc/cron.manual/homecontrol_log_cleanup >> /var/log/homecontrol_log_cleanup") | crontab -
(crontab -l 2>/dev/null; echo "0   10 * * * /etc/cron.manual/homecontrol_motion_cleanup >> /var/log/homecontrol_motion_cleanup") | crontab -




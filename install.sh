#!/bin/bash
#  Installations-Skript für den HomeControl Server
#
#  Daniel Scheidler                      Juni 2016 

sudo apt-get update

#
# Basis-Packages
#
sudo apt-get nano screen 


#
#  APACHE, PHP, MySQL
#
sudo apt-get install apache2 php5 
sudo apt-get install libapache2-mod-php5 libapache2-mod-perl2 php5 php5-cli php5-common php5-curl php5-dev php5-gd php5-imap php5-ldap php5-mhash php-pear php-apc
sudo a2enmod rewrite
cp installFiles/etc/apache2/sites-enabled/000-default  /etc/apache2/sites-enabled/000-default


echo "Am Ende der Datei bitte die folgende Zeile einfügen und speichern:"
echo ""
echo "www-data ALL=(ALL) NOPASSWD: ALL"
echo ""
pause 
sudo visudo

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
sudo rm * -R
sudo rm .git -R
sudo rm .gitignore
git clone https://github.com/CyborgOne/cybihomecontrol_ui.git .

#
#  DATENBANK
#
echo "Geben Sie das neue Passwort für den root-Datenbankbenutzer ein"
echo ""
sudo apt-get install mysql-server
sudo apt-get install php5-mysql php5-odbc mysql-client php5-mysql
mysql -u root -p homecontrol < homecontrol.sql
echo "Geben Sie hier Ihr gerade neu vergebenes Datenbank-Kennwort ein."
echo ""
echo "Die Benutzerdaten kann man nach der Installation jeder Zeit anpassen."
echo "Ein neuer/anderer Datenbank-Benutzer benötigt Vollzugriff auf die Datenbank 'homecontrol'."
echo "Anschließend muss man nur die Login-Daten in der Datei /var/www/config/dbConnect.php an den gerade angelegten Benutzer anpassen."
echo ""
pause
nano /var/www/config/dbConnect.php


#
# Kamera / Motion-Detection
#
sudo apt-get install motion


#
#  CRONS
#
mkdir -p /etc/cron.manual
cp installFiles/crons/* /etc/cron.manual

#crons aktivieren
(crontab -l 2>/dev/null; echo "*/1 *  * * * /etc/cron.manual/homecontrol_cron >> /var/log/homecontrol_cron") | crontab -
(crontab -l 2>/dev/null; echo "0   0  * * * /etc/cron.manual/homecontrol_log_cleanup >> /var/log/homecontrol_log_cleanup") | crontab -
(crontab -l 2>/dev/null; echo "0   10 * * * /etc/cron.manual/homecontrol_motion_cleanup >> /var/log/homecontrol_motion_cleanup") | crontab -


#Logfiles erzeugen
echo > switch.log
echo > signalIn.log

#
# Rechte anpassen
#
sudo chown pi:www-data /var/www/* -R
sudo chmod 755 /var/www/* -R

sudo chown root:www-data /etc/network/interfaces -R
sudo chmod 775 /etc/network/interfaces -R

sudo chmod 775 /var/www/pics/raumplan -R
sudo chmod 775 /var/www/cam_pics

sudo chmod 755 /etc/cron.manual/* -R
sudo chmod +x  /etc/cron.manual/* -R

sudo chmod 775 /var/www/signalIn.log
sudo chmod 775 /var/www/switch.log


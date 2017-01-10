#!/bin/bash
#  Update-Skript fuer den HomeControl Server
#
#  Daniel Scheidler                      Juni 2016 

sudo apt-get update

cd /var/www
git update

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

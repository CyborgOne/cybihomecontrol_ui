#!/bin/bash
NOW=$(date +"%s")
raspistill -vf -o /var/www/cam_pics/image_$NOW.jpg -n


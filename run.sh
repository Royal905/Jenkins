#!/bin/bash

#uncomment below if centos docker image is being created
#exec /usr/sbin/httpd -DFOREGROUND

#uncomment below if images is to be created for cron jobs
#echo "Starting Cron"
#cron

#use below if docker images being created  is for ubuntu
echo "Starting Apache"
exec apache2ctl -D FOREGROUND
~
~
~
~
~


#!/bin/bash

#############################################
##
##	Script  para remover App ATENÇÃO!!!
##
############################################

#Passar nome do site/app
APP="$1"

rm -r "/var/www/html/$APP"
rm -r "/var/log/nginx/$APP"
rm "/etc/nginx/sites-available/$APP"
rm "/etc/nginx/sites-enabled/$APP"

docker stop "${APP}-app"
docker rm "${APP}-app"

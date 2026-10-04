#!/bin/bash



############################################################
##
##	Módulo de roolback para script de provisionamento
##
###########################################################


remove_dir(){

	rm -r /var/www/html/$APP_NAME
	echo "Realizando processo de Rollback: apagando pasta do projeto"
}

remove_nginx_files(){

	rm /etc/nginx/sites-available/$APP_NAME
	rm /etc/nginx/sites-enabled/$APP_NAME
	echo "Reomvendo arquivos nginx..."

}



#!/bin/bash

##########################################
##
##	Script para criação de database no container mariadb
##
###########################################



validate() {
	if [[ -z "$DATABASE" ]]; then
    		echo "Erro: database não informado."
    		exit 1
	fi

	if [[ -z "$DB_USER" ]]; then
    		echo "Erro: usuário não informado."
    		exit 1
	fi

	if [[ -z "$PASSWORD" ]]; then
    		echo "Erro: senha não informada."
    		exit 1
	fi
}



options(){
	while getopts ":d:u:p:" opt; do
    		case $opt in
        		d) DATABASE="$OPTARG" ;;
        		u) DB_USER="$OPTARG" ;;
        		p) PASSWORD="$OPTARG" ;;
        		*)
            			echo "Uso: $0 -d database -u user -p password"
            			exit 1
            		;;
    		esac
	done
}

docker_exec(){
	docker exec -it mariadb mariadb -u root -p -e "
	CREATE DATABASE \`$DATABASE\`
    	CHARACTER SET utf8mb4
    	COLLATE utf8mb4_unicode_ci;

	CREATE USER '$DB_USER'@'%'
    	IDENTIFIED BY '$PASSWORD';

	GRANT ALL PRIVILEGES
    		ON \`$DATABASE\`.*
    		TO '$DB_USER'@'%';

	FLUSH PRIVILEGES;
	"
}

options "$@"
validate
docker_exec

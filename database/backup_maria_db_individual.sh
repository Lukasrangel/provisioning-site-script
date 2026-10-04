#!/bin/bash


############################
#
#	Backup mariadb container individual, site único!
#
############################

validate(){
	if [[ -z "$DATABASE" ]]; then
		echo "Erro: Database não informado";
		exit 1;
	fi

	if [[ -z "$PASSWORD" ]]; then
		echo "Erro: Password não informado";
		exit 1;
	fi
}

options(){
        while getopts ":d:p:" opt; do
                case $opt in
                        d) DATABASE="$OPTARG" ;;
                        p) PASSWORD="$OPTARG" ;;
                        *)
                                echo "Uso: $0 -d database -p password"
                                exit 1
                        ;;
                esac
        done
}


mysql_backup(){
	mkdir -p /opt/backups/mariadb/individual/$DATABASE
	docker exec mariadb mysqldump -u $DATABASE -p"$PASSWORD" $DATABASE > /opt/backups/mariadb/individual/$DATABASE/$DATABASE-DB-$(date +%d-%m-%Y).sql
}

options "$@"
validate
mysql_backup

#!/bin/bash


############################
#
#	Backup mariadb container completo!
#
############################


mysql_backup(){
	mkdir -p /opt/backups/mariadb/full_backup
	docker exec mariadb mariadb-dump \
    	--defaults-extra-file=/run/secrets/mariadb.cnf  \
    	--all-databases \
    	--routines \
    	--triggers \
    	--events \
    	| gzip > /opt/backups/mariadb/full_backup/mariadb-full-$(date +%F).sql.gz
}


mysql_backup

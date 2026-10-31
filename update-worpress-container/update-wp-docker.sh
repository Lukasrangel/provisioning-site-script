#!/bin/bash

#########################################
##
##	Scrip de atualização do Wordpress Dockerizado
##
########################################


## Monitoramento de erros
set -Eeuo pipefail

handle_error() {
    local exit_code=$?
    local line="$1"
    local command="$2"

    echo "[ERRO] Falha na linha: $line"
    echo "[ERRO] Comando: $command"
    echo "[ERRO] Código de saída: $exit_code"

    # Aqui você pode chamar o rollback
    rollback_database
    rollback_files
}

trap 'handle_error "$LINENO" "$BASH_COMMAND"' ERR


## Váriaveis

container=$1
sql_backup_dir="/opt/backups/mariadb/individual/${container}"
wp_backup="/opt/backups/wordpress/${container}"
db_password="123456"
#log_file=

check_container_exists(){

	if ! docker ps | grep -q "${container}-app"; then
		echo "Não existe o continer ${container}-app"
		exit 1
	fi
}


##########################################
##### Bloco de backup
#########################################
work_mode_on(){

	docker exec -it -u www-data "${container}-app" wp maintenance-mode activate
	echo "[X] Manutenção mode on!"
}


db_backup(){

	mkdir -p $sql_backup_dir
	docker exec mariadb mysqldump -u $container -p"${db_password}" $container > $sql_backup_dir/${container}-$(date +%d-%m-%Y).sql
	echo "[X] Database backup ok in ${sql_backup_dir}"
}

wp_backup(){
	mkdir -p $wp_backup
	tar -czf "${wp_backup}/${container}-$(date +%d-%m-%Y).tar.gz" -C /var/www/html/$container public
	echo "[X] Wordpress backup ok in ${wp_backup}"
}

## Fim do bloco de backup

############################################
###### Bloco check updates
############################################

check_core_update(){

	if docker exec -u www-data "${container}-app" wp core check-update | grep -qE '^[1-9]'; then
		return 0
	else
		return 1
	fi

}


check_plugins_update(){

	if docker exec -it -u www-data "${container}-app" wp plugin list --update=available | grep -qE '^[1-9]'; then
		return 0
	else 
		# sem atualizações
		return 1
	fi

}


check_themes_update(){

	if docker exec -it -u www-data "${container}-app" wp theme list --update=available | grep -qE '^[1-9]'; then
		return 0
	else
		return 1
	fi	

}

#### Fim do bloco de checks

##################################################
##### Bloco de updates
#################################################

update_core(){

	if check_core_update; then
		echo "[X] Update core disponível, iniciando update...."
		docker exec -it -u www-data "${container}-app" wp core update
		echo "[X] Update core Wordpress concluído"
	else
		echo "[X] Sem updates do Core necessários..."
	fi
}

update_plugins(){

	if check_plugins_update; then
		echo "[X] Update plugins disponíveis, iniciando update..."
		docker exec -it -u www-data "${container}-app" wp plugin update --all
		echo "[X] Pugins update ok"
	else
		echo "[X] Sem update de plugins necessários... "
	fi

}


update_themes(){

	if check_themes_update; then
		echo "[X] Update themes disponíveis, iniciando update..."
		docker exec -it -u www-data "${container}-app" wp theme update --all
		echo "[X] Themes update ok"
	else
		echo "[X] Sem update de themes necessário"
	fi

}



update_db(){

	docker exec -it -u www-data "${container}-app" wp core update-db
	echo "[X] Update database ok!"

}


#### Fim do bloco de update



# checksum do wordpress
checksum_wp_core(){
	
	if docker exec -it -u www-data "${container}-app" wp core verify-checksums; then
		echo "[X] Wordpress successfully updated!"
		return 0
	else
		return 1
	fi

}


####################################
#### Check container 
####################################

check_container_up(){

	if  docker ps --format '{{.Names}}' | grep -Fxq "${container}-app"; then
		echo "[X] Container ativo e saudável! Finalizando..."
	else
		echo "[X] Container caiu, verificar manualmente!!!"
		exit 0 	
	fi

}


work_mode_of(){

	docker exec -it -u www-data "${container}-app" wp maintenance-mode deactivate
	echo "[X] Maintenance mode off"

}

#######################################
##### rollback
#######################################
rollback_database(){

	docker exec -i mariadb mariadb -u $container -p"${db_password}" ${container} < "${sql_backup_dir}/${container}-$(date +%d-%m-%Y).sql"
	echo "[X] Rollback database restored!"

}

rollback_files(){

	tar -xvf "$wp_backup/${container}-$(date +%d-%m-%Y).tar.gz" -C /var/www/html/$container
	echo "[X] Rollback wp files ok!"

}





check_container_exists
work_mode_on
db_backup
wp_backup

update_core
update_plugins
update_themes
update_db

checksum_wp_core
work_mode_of
echo "[X] Update concluído com sucesso!"


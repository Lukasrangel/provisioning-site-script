#!/bin/bash

####################################################
##
##	Script para provisionamento de site no LAB VPS DOCKER
##
##
##	Este script provisiona um novo site na infraestrutura, container docker php-fpm, registro nginx e site online
##
##
###################################################


########
# MODULES
########

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/../modules/rollback.sh"
source "$SCRIPT_DIR/../modules/docker-provide.sh"
source "$SCRIPT_DIR/../modules/nginx-provide.sh"

######
# INICIO BLOCO DE VALIDAÇÃO
#####

options(){
        while getopts ":n:d:p:" opt; do
                case $opt in
                        n) APP_NAME="$OPTARG" ;;
			d) DOMAIN="$OPTARG" ;;
			p) APP_PORT="$OPTARG" ;;
                        *)
                                echo "Uso: $0 -n <app-name> -d <domain> -p <app-port>"
                                exit 1
                        ;;
                esac
        done
}



validate(){
	
	# is root?
	if [ ! $(id -u) == "0" ]; then
    		echo "Necessário rodar o comando como root!";
		exit 1
	fi

	#docker instalado?
	if [ ! -x $(which docker) ]; then
		echo "Docker não está instalado!"
		exit 1;
	fi

	#docker compose disponível?
	if ! docker compose version &>/dev/null; then
		echo "Docker compose não está disponível!"
		exit 1;
	fi

	#nginx instalado?
	if ! nginx -v &>/dev/null; then
		echo "Nginx não está instalado!"
		exit 1;
	fi	

}

validate_app_name(){
	
	# verifica se foi passado argumento
	if [[ -z "$APP_NAME" ]]; then
		echo "Não foi setado nome para aplicação!";
		echo "Use $0 -n <app-name>";
		exit 1;
	fi

	# verifica se nome é válido (sem caracteres especiais)
	if ! [[ "$APP_NAME" =~ ^[a-z0-9][a-z0-9_-]*$ ]]; then
        	echo "Erro: nome da aplicação inválido."
        	echo "Use apenas letras minúsculas, números, '_' e '-'."
        	exit 1
    	fi

	# verifica se app com o mesmo nome já existe
	if  docker ps -a --format '{{.Names}}' | grep -x $APP_NAME-app; then
		echo "Já existe app com este nome!"
		exit 1;
	fi

}

validate_domain(){

	if [[ -z "$DOMAIN" ]]; then
		echo "Não foi setado um domínio para a aplicação!"
		echo "Use $0 -n <app-name> -d <domain>"
		exit 1
	fi

	if [[ ! "$DOMAIN" =~ ^[a-zA-Z0-9]([a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$ ]]; then
        	echo "Erro: domínio inválido: $DOMAIN"
        	exit 1
    	fi
}

validate_app_dir(){

	# verifica se já existe dierotorio do app
	if test -d /var/www/html/$APP_NAME; then
		echo "Já existe diretório com este nome em /var/html/www !!"
		exit 1;
	fi


}

validate_container_exists(){

	# verifica se container já existe
	if docker ps -a | grep $APP_NAME-app; then
		echo "Já existe um container docker com este nome!!"
		exit 1;
	fi


}

#########
# FIM DO BLOCO DE VALIDAÇÃO
########


options "$@"
validate
validate_app_name
validate_domain
validate_app_dir
validate_container_exists

make_dir
dockerfile_php
docker_compose_file
docker_copy_themes
docker_compose_up
remove_tmp_themes_dir

nginx_file_provide
nginx_check_and_restart

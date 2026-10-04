#!/bin/bash

##############################
##
##	Nginx conf module
##
##############################

nginx_file_provide(){

	if ! cp "../templates/nginx.skeleton" "/etc/nginx/sites-available/$APP_NAME"; then
		echo 'Erro ao provisionar arquivo nginx'
		remove_dir
		remove_nginx_files
		exit 1;
	fi

	sed -i \
        -e "s/{{APP_NAME}}/$APP_NAME/g" \
        -e "s/{{APP_PORT}}/$APP_PORT/g" \
	-e "s/{{DOMAIN}}/$DOMAIN/g" \
	"/etc/nginx/sites-available/$APP_NAME";

	# Ativa o site configurando link simbolico
	if ! ln -s "/etc/nginx/sites-available/$APP_NAME" "/etc/nginx/sites-enabled/"; then
		echo "Erro ao ativar link simbolico no nginx!!";
		remove_nginx_files
		exit 1;	
	fi

	# Criando diretorio para os logs da aplicacao
	mkdir "/var/log/nginx/$APP_NAME"
}

nginx_check_and_restart(){

	if ! nginx -t; then
		echo "Erro: configuracoes nginx!";
		remove_nginx_files
		exit 1;
	fi	

	echo "[X] Nginx configurado com sucesso"	

	systemctl restart nginx

}


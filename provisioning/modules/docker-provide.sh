#!/bin/bash


###########
# Criando estrutura
###########

make_dir(){

	if ! mkdir -p /var/www/html/$APP_NAME/docker; then
		echo "Erro ao criar o diretorio em /var/www/html/$APP_NAME";
		remove_dir
		exit 1;
	fi

	mkdir /var/www/html/$APP_NAME/public;
	
	echo "[x] Diretorio criado em /var/www/html/$APP_NAME"; 

}

dockerfile_php(){
		
	if ! cp ../templates/Dockerfile-wordpress.skeleton "/var/www/html/$APP_NAME/docker/Dockerfile"; then
		echo "Erro ao provisionar Dockerfile da aplicação!"
		remove_dir
		exit 1;
	fi

	echo "[X] Dockerfile provisionado!";

}

docker_compose_file() {

	if ! cp ../templates/docker-compose.skeleton "/var/www/html/$APP_NAME/docker/docker-compose.yml"; then
		echo "Erro ao provisionar docker-compose da aplicação!"
		remove_dir
		exit 1;
	fi	

	sed -i \
    	-e "s/{{APP_NAME}}/$APP_NAME/g" \
    	-e "s/{{APP_PORT}}/$APP_PORT/g" \
    	"/var/www/html/$APP_NAME/docker/docker-compose.yml"

	if ! docker compose -f "/var/www/html/$APP_NAME/docker/docker-compose.yml" config --quiet; then
		echo "Erro no arquivo docker-compose";
		remove_dir
		exit 1;
	fi
	
	echo "[X] Docker compose da aplicacao provisionado com sucesso";
}


docker_copy_themes(){

	mkdir -p "/var/www/html/$APP_NAME/docker/themes"
	cp -r /opt/infra/themes/. "/var/www/html/$APP_NAME/docker/themes/"
}


docker_compose_up(){
	
    docker compose \
    -f "/var/www/html/$APP_NAME/docker/docker-compose.yml" \
    up -d

    if [ $? -ne 0 ]; then
	echo "Erro ao subir o container!! Verifique as portas disponíveis!"
	remove_dir
	exit 1
    fi
}

remove_tmp_themes_dir(){

	rm -r "/var/www/html/$PP_NAME/docker/themes"

}

docker_wordpress_app_config(){

	docker exec "$APP_NAME-app" \
    		wp config set DISALLOW_FILE_MODS true --raw --allow-root

}

#!/bin/bash

################################
#
#	Importar sql para database
#
###############################


options(){
        while getopts ":d:u:p:f:" opt; do
                case $opt in
                        d) DATABASE="$OPTARG" ;;
                        u) DB_USER="$OPTARG" ;;
                        p) PASSWORD="$OPTARG" ;;
			f) FILE="$OPTARG" ;;
                        *)
                                echo "Uso: $0 -d database -u user -p password -f file"
                                exit 1
                        ;;
                esac
        done
}


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

	fi [[ -z "$FILE" ]]; then
		echo "Erro: arquivo sql não informado."
		exit 1
}



mysql_import(){
	docker exec -i mariadb \
  	mariadb -u $DB_USER -p'$PASSWORD' $DATABASE \
  	< $ARQUIVO_SQL
	
}

options "$@"
validate
mysql_import

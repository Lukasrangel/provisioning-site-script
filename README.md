# WordPress Hosting Infrastructure

Conjunto de scripts Bash e configurações Docker desenvolvidos para provisionamento, administração e manutenção de ambientes de hospedagem WordPress.

O projeto automatiza a criação de novas aplicações WordPress em containers Docker, incluindo a configuração de Virtual Hosts no Nginx, gerenciamento de bancos de dados e rotinas de manutenção.

A infraestrutura foi pensada para hospedar múltiplos sites WordPress em um único servidor Linux, mantendo o isolamento entre aplicações e centralizando serviços compartilhados.

## Funcionalidades

### Provisionamento de aplicações

Automatiza a criação de novas instâncias WordPress, contemplando:

- Criação da estrutura de diretórios da aplicação.
- Provisionamento do container WordPress.
- Configuração do ambiente Docker.
- Criação e configuração do Virtual Host no Nginx.
- Integração da aplicação à infraestrutura de rede Docker.
- Configuração inicial do banco de dados e das credenciais de acesso.

### Gerenciamento de banco de dados

Conjunto de utilitários para administração dos bancos MariaDB utilizados pelas aplicações.

Inclui:

- Criação de bancos de dados e usuários individuais.
- Exportação de backups completos do servidor MariaDB.
- Exportação de backups individuais por aplicação.
- Importação de arquivos SQL para restauração ou migração de dados.

### Atualização do WordPress

Script de manutenção responsável por executar atualizações do WordPress dentro dos containers, utilizando WP-CLI.

Contempla:

- Atualização do WordPress Core.
- Atualização de plugins e temas.
- Verificação de integridade dos arquivos do Core.
- Backup prévio dos arquivos e banco de dados.
- Mecanismo de rollback em caso de falhas durante a atualização.

### Remoção de aplicações

Script para remoção de uma aplicação WordPress da infraestrutura.

Responsável pela limpeza dos recursos associados ao site, incluindo estrutura de diretórios, containers, configurações de Virtual Host e recursos de banco de dados, conforme o procedimento de remoção implementado.

## Arquitetura da infraestrutura

A infraestrutura utiliza Docker para executar as aplicações WordPress e centralizar serviços compartilhados, como o servidor MariaDB.

Cada aplicação possui seu próprio ambiente de execução e banco de dados, enquanto os serviços compartilhados são utilizados pelas diferentes instâncias.

### Componentes da arquitetura

**1. Aplicações WordPress (Docker)**

Cada site é executado em seu próprio container, com diretório de aplicação e configurações independentes.

**2. Banco de dados compartilhado (MariaDB)**

Um container MariaDB centraliza os bancos de dados das aplicações.

Cada site possui um banco de dados e um usuário próprios, evitando o compartilhamento de credenciais entre aplicações.

**3. Nginx (Host Linux)**

O Nginx é executado diretamente no host e atua como Reverse Proxy, recebendo as requisições HTTP/HTTPS e direcionando o tráfego para a aplicação correspondente.

Cada site possui seu próprio Virtual Host (Server Block), permitindo hospedar múltiplos domínios no mesmo servidor.

**4. Storage local**

Os dados persistentes das aplicações são armazenados em diretórios do host Linux por meio de volumes Docker.

Essa abordagem permite manter os dados das aplicações independentes do ciclo de vida dos containers.

**5. Camada de segurança**

A infraestrutura contempla medidas de proteção e controle de acesso, incluindo:

- Restrição da instalação de plugins e temas diretamente pelo painel WordPress.
- Controle de acesso aos serviços e bancos de dados.
- Rate Limiting no Nginx.
- Fail2ban para proteção contra tentativas de acesso indevidas.

## Organização do repositório

```text
/
├── README.md
│
├── remove_app.sh
│
├── database/
│   ├── backup_maria_db_full.sh
│   ├── backup_maria_db_individual.sh
│   ├── create-database-mariadb.sh
│   └── import_sql.sh
│
├── docker/
│   ├── mariadb/
│   └── phpmyadmin/
│
├── provisioning/
│   ├── bin/
│   ├── modules/
│   └── templates/
│
└── update-wordpress-container/
    └── update-wp-container-script.sh
```

### Diretórios e scripts

| Componente | Responsabilidade |
|---|---|
| `remove_app.sh` | Remoção de aplicações WordPress. |
| `database/` | Utilitários de criação, backup e restauração de bancos de dados. |
| `docker/` | Configurações dos serviços Docker compartilhados. |
| `provisioning/` | Scripts, módulos e templates utilizados no provisionamento de aplicações. |
| `update-wordpress-container/` | Rotinas de atualização e manutenção do WordPress. |

## Objetivo do projeto

Desenvolver uma infraestrutura de hospedagem WordPress automatizada, administrável por linha de comando e baseada em ferramentas open source.

O projeto também funciona como laboratório prático de administração Linux, Docker, redes, servidores web, bancos de dados, segurança e automação com Bash.

# WordPress Container Manager — Script de Atualização

## Objetivo

Script Bash desenvolvido para automatizar o processo de atualização do WordPress em ambientes de hospedagem não gerenciada (*Unmanaged WordPress Hosting*).

Nesse modelo de hospedagem, operações administrativas como atualizações do Core, instalação e atualização de plugins e temas podem ficar sob responsabilidade do SysAdmin.

O script automatiza essas operações dentro do container WordPress, adicionando mecanismos de backup, validação e rollback para reduzir os riscos durante o processo de manutenção.

## Estrutura de execução

O script é dividido em blocos funcionais, organizados para executar as etapas de manutenção de forma sequencial.

### 1. Tratamento de erros

Responsável por definir as diretivas de segurança e o mecanismo de captura de falhas do script.

Utiliza:

```bash
set -Eeuo pipefail
trap 'handle_error "$LINENO" "$BASH_COMMAND"' ERR
```

As diretivas configuradas permitem:

- Interromper a execução diante de falhas não tratadas.
- Identificar o uso de variáveis não inicializadas.
- Detectar falhas em comandos executados por pipelines.
- Propagar o tratamento de erros para funções e contextos aplicáveis.

O `trap ERR` aciona a função `handle_error` quando ocorre uma falha elegível para captura.

Essa função inicia o procedimento de rollback, responsável por restaurar o banco de dados e os arquivos do WordPress a partir dos backups realizados antes da atualização.

### 2. Variáveis e configurações

Centraliza as variáveis utilizadas durante a execução do script.

Inclui informações como:

- Nome do container WordPress.
- Nome do container MariaDB.
- Diretório de instalação do WordPress.
- Diretório de armazenamento dos backups.

### 3. Backup

Responsável por criar os pontos de restauração antes de iniciar as atualizações.

São realizados dois backups:

- **Banco de dados:** exportação do banco MariaDB em formato SQL.
- **Arquivos:** compactação dos arquivos do WordPress em formato TAR.GZ.

Os backups permitem restaurar o ambiente ao estado anterior à atualização em caso de falha.

### 4. Verificação de atualizações (*Check Updates*)

Consulta o WP-CLI para identificar atualizações disponíveis.

Verifica individualmente:

- WordPress Core.
- Plugins.
- Temas (*Themes*).

Essa etapa permite identificar quais componentes precisam ser atualizados antes de iniciar as operações.

### 5. Atualizações (*Updates*)

Executa as atualizações identificadas na etapa anterior, utilizando o WP-CLI dentro do container WordPress.

As operações contemplam:

- Atualização do Core.
- Atualização dos plugins.
- Atualização dos temas.
- Atualização da estrutura do banco de dados, quando necessária.

### 6. Validação pós-atualização

Executa verificações para avaliar a integridade do WordPress após as atualizações.

Inclui:

- Verificação dos checksums do Core.
- Verificação da disponibilidade do container.

O objetivo é identificar possíveis inconsistências antes de considerar o processo concluído.

### 7. Rollback

Responsável por executar a restauração do ambiente em caso de falha durante o processo de atualização.

O procedimento contempla:

1. Interromper o fluxo de atualização.
2. Restaurar o banco de dados a partir do backup SQL.
3. Restaurar os arquivos do WordPress a partir do backup TAR.GZ.
4. Registrar o resultado da operação.

O objetivo é retornar o ambiente ao estado anterior ao início da manutenção.

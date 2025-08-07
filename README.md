# Sistema de Agendamento de Coleta de Materiais Recicláveis

Este sistema permite o agendamento de coletas de materiais recicláveis por cidadãos, com gerenciamento pelo administrador (alteração de status, histórico, etc). O projeto é composto por um backend em PHP/Laravel e um frontend em JavaScript/Vue.js, orquestrados com Docker.

---
##  Especificações em Gherkin
O sistema possui especificações de comportamento escritas em **Gherkin**

### Localização dos arquivos

- **Arquivo de especificação:** `features/agendamento/coleta_de_materiais_reciclaveis.feature`

## Configuração do Ambiente

Siga os passos abaixo para configurar e executar o projeto.

# Pré-requisitos

1. Docker desktop ou WSL2 (Linux)
2. Docker e Docker Compose instalados
  
# Este comando inicia o container com:

```
Backend (PHP + Laravel)
Frontend (Vue.js + Vite)
Banco de dados (PostgreSQL)
Teste (Cypress + PHPUnit)
```

### 1. Clonar o repositório

[https://github.com/SENAI-SD/qa-junior-01825-2025-091.714.176-80.git]

```sh
cd qa-junior-01825-2025-091.714.176-80.git
```
```sh
docker-compose up -d
```

### 2. Instalar dependências do backend

```sh
docker exec -it backend composer install
```
   
### 3. Instalar dependências do frontend

```sh
docker exec -it frontend npm install
```

### 4. Executar migrações e seeds
Acessar o container do backend

```sh
docker exec -it backend bash
```

# Dentro do container:

```bash
php artisan migrate
php artisan db:seed
```
- (usuario e senha para teste podem serem vistos na seed)

### 5. Acessar Aplicação

[http://localhost:5180]

### Testes Implementados
1. Teste de API

Descrição: Valida o endpoint POST /api/appointments com dados válidos e inválidos.
Localização: tests/Feature/AppointmentApiTest.php

2. Testes Unitários (3 testes)

- Testa a criação, valida se data atende ao requisitos do sistema.
Localização: tests/Unit/AppointmentBusinessRuleTest.php

- Verifica se os logs de status são criados corretamente após atualização.
Localização: tests/Unit/StatusLogTest.php

- Garante que admin irá comentar status concluido ou cancelado.
Localização: tests/Unit/MaterialTest.php

- Executar teste.
(lembrando que o teste limpa a base de dados)

```sh
docker exec -it backend php artisan test 
```

# 3. Testes E2E com Cypress
O projeto inclui testes E2E automatizados com Cypress.

- Teste para agendamento de coleta.

    Executar o Cypress

```sh
npx cypress open
```

Todos os testes estão versionados no Git e organizados da seguinte forma:

- Testes E2E (Cypress):
    cypress/e2e/

- Testes de Feature/API:
    tests/Feature/

- Testes Unitários:
    tests/Unit/

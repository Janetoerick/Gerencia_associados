# 🚀 Sistema de Gestão de Associados e Cobranças (PHP Puro)

Este projeto é um sistema de gerenciamento de membros (associados) desenvolvido em PHP Puro com arquitetura MVC. O objetivo principal é automatizar o controle de anuidades, registro de pagamentos e visualização de pendências financeiras por associado.

---

## 🛠️ Pré-requisitos

Para rodar a aplicação, você precisa ter o **Docker** e o **Docker Compose** instalados em sua máquina:

* **Docker Engine**
* **Docker Compose**
* **Git** (para clonar o repositório)

---

## 🏁 Instalação e Execução Rápida

O ambiente completo (PHP-FPM, Nginx e MySQL) é orquestrado pelo Docker Compose. O banco de dados é inicializado e as tabelas são criadas automaticamente no primeiro *boot*.

Siga os passos abaixo para configurar e iniciar o sistema em menos de 1 minuto:

### 1. Clonar o Repositório

```bash
git clone https://github.com/Janetoerick/Gerencia_associados.git
```

```bash
cd Gerencia_associados
```

### 2. Levantar o Ambiente e Configurar

Execute o comando a seguir. Ele fará a construção das imagens, a instalação das dependências do Composer e a inicialização completa do banco de dados:

```bash
docker-compose up -d --build
```

### 3. Acesso à Aplicação

Após alguns segundos (tempo necessário para o MySQL inicializar), a aplicação estará disponível em:

➡️ http://localhost:8000

---

## 📋 Funcionalidades Principais

O sistema oferece as seguintes funcionalidades de gestão:

* **CRUD de Associados:** Cadastro completo de novos membros, com validação de unicidade de CPF e E-mail.
* **Gestão de Anuidades:** Definição do valor da anuidade por ano.
* **Controle de Cobranças:**
  *  **Geração em Lote:** Botão de ação rápida na tela de Anuidades para gerar a cobrança de anuidade do ano atual para todos os associados ativos.
  *  **Registro de Pagamento:** Opção de registrar o pagamento de cobranças pendentes individualmente.
  *  **Dashboard de Pendências:** Visualização do número total de cobranças em aberto (pendências) diretamente na listagem de associados.

---

## 🏗️ Arquitetura do Projeto

O projeto segue um padrão MVC (Model-View-Controller) com as seguintes características:
* **Front Controller:** Todas as requisições são direcionadas ao `index.php`.
* **Roteamento:** Implementação manual do roteamento usando a biblioteca `nikic/fast-route`.
* **Camada de Dados:** Uso de **PDO Puro** nos Models para acesso ao **MySQL**.

---

## 👤 Autor e Contato

Este projeto foi desenvolvido por Janeto Erick / Janetoerick
* https://github.com/Janetoerick

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

**Nota Importante sobre Conexão:** O servidor de banco de dados (MySQL) pode levar mais tempo para iniciar completamente do que o servidor web (PHP/Nginx). Se você tentar acessar **localhost:8000** imediatamente e receber um erro de "Connection refused" ou "SQLSTATE[HY000] [2002]", aguarde mais alguns segundos/minutos e atualize a página.

### 3. Acesso à Aplicação

Após alguns segundos/minutos (tempo necessário para o MySQL inicializar), a aplicação estará disponível em:

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

## 🗄️ Estrutura do Banco de Dados (Schema)

A base de dados `associacao` é composta por três tabelas principais que seguem as relações de Chave Primária (PK), Chave Estrangeira (FK) e Restrições de Unicidade.

| Tabela | Chave Principal (PK) | Chaves de Relacionamento (FK) | Restrições Únicas (UNIQUE) |
| :---| :--- | :--- | :--- |
| **`associado`** | `id` | N/A | `cpf`,`email` |
| **`anuidade`** | `ano` | N/A | `ano` |
| **`cobranca`** | `id` | `Associado_id (FK para `associado`), `Anuidade_ano` (FK para `anuidade`) | `(Associado_id, Anuidade_ano)` |

---

## ⚠️ Limitações

Embora o sistema esteja funcional para as operações de fluxo de caixa primárias, há algumas limitações conhecidas no design da interface (UI) e regras de negócio:

* **Exclusão de Anuidades:**
  *  O endpoint de exclusão `(DELETE /anuidades/{ano}/delete)` funciona no backend.
  *  No entanto, a opção de exclusão não está visível na interface (UI). A exclusão só é possível através do endpoint e se nenhuma cobrança estiver relacionada à anuidade.
*  **Status do Associado:**
   *  O sistema assume que todos os associados cadastrados estão **ativos** e são incluídos na geração de cobranças em lote.

---

## 👤 Autor e Contato

Este projeto foi desenvolvido por Janeto Erick
* https://github.com/Janetoerick

# SysCheck — Sistema de Chamados

O **SysCheck** é um sistema interno desenvolvido para gerenciar **chamados técnicos**, **equipamentos**, **usuários** e **operações de suporte** dentro da empresa.  
Ele foi criado com o objetivo de **Parar de fazer o checklist no papel e virar eletronico**, **automatizar o controle de solicitações**, **otimizar o fluxo de manutenção** e **centralizar informações** de forma prática e segura.

---

## 🚀 Funcionalidades Principais

### 🧾 Gestão de Chamados
- Abertura de chamados com seleção de tipo, prioridade e descrição do problema.  
- Associação automática de **equipamentos** ao tipo de chamado escolhido.  
- Atualização de status e histórico de cada solicitação.  
- Registro de **usuário responsável** e **data/hora de abertura**.

### 💻 Controle de Equipamentos
- Cadastro, edição e listagem de todos os equipamentos por categoria.  
- Filtro automático por **tipo de checklist**.  
- Status individual de cada equipamento (ativo, em manutenção, inativo).

### 👨‍💻 Usuários e Sessões
- Sistema de **login seguro** com controle de sessão.  
- Cada ação é vinculada ao **usuário logado**.  
- Permissões configuradas por cargo e departamento.

### 🧠 Arquitetura Limpa
Organizado no padrão **MVC + RN + DAO**, garantindo um código limpo, modular e fácil de manter:
src/
├── controllers/ → Controladores (lógica entre View e RN)
├── rn/ → Regras de Negócio (validações e lógica principal)
├── dao/ → Acesso ao Banco de Dados (MySQL)
├── models/ → Modelos de dados (classes de domínio)
├── database/ → Classe de conexão com o banco
├── util/ → Classes auxiliares (Sessão, Util, etc.)
└── views/ → Páginas HTML/PHP da interface

---

## 🏗️ Tecnologias Utilizadas

| Tecnologia | Função |
|-------------|--------|
| **PHP 8+** | Backend principal |
| **MySQL** | Banco de dados relacional |
| **HTML5 / CSS3 / JS (ES6)** | Interface web |
| **Bootstrap / Tailwind ** | Estilização responsiva |
| **jQuery / AJAX** | Requisições dinâmicas sem recarregar a página |
| **Composer (autoload)** | Gerenciamento de dependências |

---

## ⚙️ Instalação e Configuração

### 🔧 Requisitos
- PHP 8.0 ou superior  
- Servidor Apache ou Nginx  
- MySQL 5.7+  
- Composer instalado globalmente  

### 📦 Passos para Instalar

1. **Clonar o repositório:**
   ```bash
   git clone https://github.com/Brunossaantos/syscheck.git
Instalar dependências via Composer:

composer install

---

Configurar o banco de dados:

Crie um banco chamado syscheck.

Importe o arquivo database/syscheck.sql (se disponível).

Configure suas credenciais no arquivo:
src/database/Conexao.php

---

Iniciar o servidor local:

php -S localhost:8080
Acessar o sistema:

http://localhost/syscheck/

---

🔒 Segurança
Todas as rotas críticas são protegidas por verificação de sessão.

As senhas são armazenadas de forma segura.

Acesso a funções restritas depende do status e cargo do usuário.


👥 Estrutura de Usuários
Tipo de Usuário	Permissões
Administrador	Acesso total ao sistema, cadastro de usuários e configurações
Técnico	Abertura e fechamento de chamados, controle de equipamentos
Usuário Comum	Abertura de chamados e acompanhamento de status

🧩 Fluxo de Chamado
Usuário logado acessa Abertura de Chamado.

Seleciona o tipo de chamado.

O sistema carrega automaticamente apenas os equipamentos relacionados àquele tipo.

Usuário descreve o problema e confirma.

O chamado é registrado e vinculado ao usuário da sessão.

📁 Organização do Código
Pasta	Descrição
/controllers	Controla o fluxo entre interface e regras de negócio
/rn	Regras de negócio — camada que centraliza validações e lógicas
/dao	Comunicação com o banco via consultas SQL
/models	Estrutura das entidades do sistema (Usuário, Chamado, Objeto, etc.)
/util	Funções auxiliares como Sessao, Util, Mensagens, etc.
/views	Telas e formulários do sistema

🧠 Desenvolvido por
Danilo Franco
Bruno Santos



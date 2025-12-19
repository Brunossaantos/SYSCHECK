# SysCheck

---

## 📌 Sobre o Projeto

O **SysCheck** é um sistema corporativo desenvolvido para **gestão operacional de empilhadeiras e veículos**, com foco em **segurança, rastreabilidade e padronização de processos internos**.

A aplicação centraliza o controle de uso dos equipamentos, execução de checklists operacionais, registro de evidências, abertura de chamados técnicos e geração de relatórios, garantindo conformidade operacional e apoio à tomada de decisão.

---

## 🎯 Objetivos

- Padronizar a execução de checklists operacionais
- Garantir rastreabilidade completa do uso de equipamentos
- Reduzir falhas operacionais e riscos de segurança
- Centralizar registros, evidências e históricos
- Automatizar notificações e comunicação de não conformidades

---

## ⚙️ Funcionalidades Principais

- Checklists por tipo de equipamento (empilhadeiras, veículos, TI)
- Controle de início, andamento e encerramento de uso
- Validação automática de horímetro em aberto
- Registro de nível de bateria (comum e lítio)
- Upload de fotos por etapa do checklist
- Abertura de chamados vinculados ao checklist
- Envio automático de e-mails em caso de reprovação
- Consulta e filtros avançados de checklists
- Relatórios gerenciais e operacionais
- Controle de acesso por perfil de usuário
- Logs para rastreamento

---

## 🏗️ Arquitetura

O SysCheck utiliza uma arquitetura em camadas inspirada no padrão **MVC**, promovendo organização, manutenibilidade e separação de responsabilidades.

- **Controllers** – Controle de rotas e fluxo da aplicação
- **RN (Regras de Negócio)** – Validações e regras operacionais
- **DAO** – Persistência e acesso a dados
- **Models** – Entidades do domínio
- **Views** – Interface do usuário
- **Util** – Classes utilitárias e helpers
- **Logs** – rastreamento

---

## 📁 Estrutura de Diretórios

```text
/config        # Configurações globais e ambiente
/functions     # Funções auxiliares
/logs          # Logs de sistema
/src
 ├── controllers
 ├── DAO
 ├── database
 ├── models
 ├── rn
 ├── Util
 └── views
/vendor        # Dependências (Composer)
/work          # Componentes compartilhados
```

---

## 🧰 Tecnologias Utilizadas

- PHP 8.x
- HTML5 / CSS3
- Tailwind CSS
- JavaScript
- MySQL / MariaDB
- PHPMailer
- Composer
- Dotenv

---

## 🔄 Fluxo Operacional de Checklist

1. Usuário acessa o módulo de checklists
2. O sistema valida permissões e perfil
3. Verifica automaticamente horímetro em aberto
4. Checklist é iniciado conforme o tipo de equipamento
5. Etapas são executadas com ações, observações e evidências
6. Horímetro e bateria são registrados quando aplicável
7. Checklist é finalizado
8. Em caso de reprovação, o responsável é notificado por e-mail
9. Checklist fica disponível para consulta e relatórios

---

## ✉️ Notificações por E-mail

O envio de notificações é realizado automaticamente via **PHPMailer**, utilizando SMTP configurado por variáveis de ambiente.

Os e-mails são disparados quando checklists são finalizados com **itens reprovados ou apontamentos críticos**, garantindo rápida atuação dos responsáveis.

---

## 🔐 Configuração de Ambiente

### Variáveis (`.env`)

```env
# Banco principal
DB_HOST=
DB_USER=
DB_PASS=
DB_NAME=

# Banco de treinamento
DB2_HOST=
DB2_USER=
DB2_PASS=
DB2_NAME=

# SMTP
SMTP_HOST=
SMTP_PORT=
SMTP_USER=
SMTP_PASS=
SMTP_SECURE=
SMTP_CHARSET=

# Aplicação
APP_ENV=
APP_DEBUG=
```

---

## ✅ Requisitos

- PHP 8.x ou superior
- MySQL ou MariaDB
- Composer
- Servidor Web (Apache)

---

## 🚀 Instalação

1. Clone o repositório
2. Execute `composer install`
3. Configure o arquivo `.env`
4. Configure o banco de dados
5. Aponte o servidor web para o diretório raiz do projeto

---

## 🔒 Segurança

- Credenciais sensíveis não são versionadas
- Configuração centralizada via `.env`
- Controle de sessão e permissões por perfil
- Registro de erros e auditoria

---

## 📊 Status do Projeto

- Sistema corporativo de uso interno
- Em produção
- Versionado e mantido via GitHub

---

## 👤 Autor

**Bruno Carvalho**  
**Danilo Franco**

Desenvolvimento e manutenção do sistema **SysCheck**, com foco em qualidade técnica, estabilidade operacional e aderência às boas práticas de engenharia de software.

---

> Este repositório possui finalidade técnica e documental. Não contém dados sensíveis ou informações confidenciais.


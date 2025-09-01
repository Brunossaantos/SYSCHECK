# Módulo de checklist do sistema de combate a incendio

### **Funcionalidades do módulo**

- Cadastrar locais
- Cadastrar equipamentos
- Alterar cadastros de locais e equipamentos
- Alterar cadastros de equipamentos
- Associar equipamentos a locais
- Listar equipamentos por locais
- Iniciar checklists por locais
- Abertura de chamados por local
- Listar checklists por local


### **Regras de negócio**

#### **Cadastro de locais**
Cadastro de local deve ser único. 
Todo local precisa ter as seguinte informações
- Descrição de local
- Data de cadastro

#### **Cadastro de equipamentos**
Cadastro de tipos de equipamento:
- Descrição do tipo do equipamento
- Status do tipo do equipamento

>Listar tipos de equipamento com possibilidade de alteração do cadastro de tipos.

**Cadastro de equipamento deve ter:**
- Descrição do equipamento
- Tipo do equipamento

> Litar equipamentos cadatrados com possibilidade de alterar o cadastro dos equipamentos.

#### **Associar um equipamento a um local**
- Os equipamentos de um determinado local devem ser associados através desse tela, possbilitando incluir e remover equimentos do local. 
- Um mesmo local pode ter vários equipamentos do mesmo tipo.
- Para poder colocar um equipamento em um local, esse equipamento deve ser cadastrado antes, na tela de cadastro de equipamentos.

#### **Checklist**

#### **Etapas de checklist / Itens que devem ser verificados**
- Cadastrar etapas associando elas ao tipo do checklist
- Inclusão da possibilidade de solicitar fotos obrigatórias
- Inclusão da possibilidade da inclusão de campos adicionais
- Alteração das etapas (Número, título, conteúdo, status)

#### **Condução do checklsits**
Para iniciar o checklist o usuário primeiro deve selecionar o local, o sistema deverá trazer uma lista de equipamentos cadastrados naquele local, seperados por tipo.
A lista de equipamentos deve seguir o seguinte padrão:

Tipo do equipamento
Descrição do equipamento | Ações

Clicando no botão para iniciar o checklist o usuário vai iniciar o checklsit do equipamento.
Ao final do checklist, o usuário deve ser encaminhado para tela de seleção do equipamento para o local onde ele fez o utimo checklist.

##### **Tela da etapa de checklist**
A tela deve ter os botões:
Aprovado | Reprovado | Observação | Foto

- Botão aprovado -> segue para a próxima etapa quando houver, se não vai para tela de finalização do checklist
- Botão reprovado -> exige que seja enviada uma foto e uma observação para reprovação do item verificado e segue para a próxima etapa.
- Botão observação deve abrir um modal para inclusão de alguma observação que o usuário queira fazer referente a etapada o item checado.
- Botão foto deve abrir um campo para inlcusão de fotos referente a etapa do item checado.


#### **Relatórios**

##### **Relatório por local**
Selecionando o local, deve ser gerada uma lista de todos os equiamentos do local, mostrando o ultimo checklists de cada equipamento (com possibilidade mostrar mais de um checklists por equipamento).

Exemplo do relatório

###### **Local**

**Tipo do Equipamento**
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
|--------------------------|---------|---------|---------|---------|-------------|-------------|------|
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |

**Tipo do Equipamento**
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
|--------------------------|---------|---------|---------|---------|-------------|-------------|------|
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |


##### **Relatório por tipo de equipamento**
Selecionando o tipo do equipamento, deve ser gerada uma lista de todos os equipamentos cadastrados seperados por locais, mostrando a ultima verificação de cada equipamento

Exemplo do relatório

**Tipo do equipamento**
| Local | Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
|-------|--------------------------|---------|---------|---------|---------|-------------|-------------|------|
| Local | Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Local | Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |
| Local | Descrição do equipamento | Etapa 1 | Etapa 2 | Etapa 3 | Etapa 4 | Observações | Vistoriador | Data |


##### Etapas da implementação

- [x] Implementação das tabelas no banco de dados
- [x] Implementação e testes unitários da camada model
- [x] Implementação e testes unitários da camada DAO
- [ ] Implementação e testes unitários das regras de negócio
- [ ] Implementação e testes unitários das views
- [ ] Deploy em ambiente de homologação
- [ ] Testes do módulo
- [ ] Coleta de log de erros e melhorias
- [ ] Deploy em produção
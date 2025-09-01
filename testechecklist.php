<?php

// Ajuste o require conforme a estrutura do seu projeto
require_once __DIR__ . '/src/database/Conexao.php';
require_once __DIR__ . '/src/models/Checklist.php';
require_once __DIR__ . '/src/DAO/DaoChecklist.php';
require_once __DIR__ . '/src/Util/Util.php';


use DAO\DaoChecklist;
use models\Checklist;
use database\Conexao;

// Cria a conexão
$conexao = new mysqli('localhost', 'root', '', 'syscheck');
if ($conexao->connect_errno) {
    die("Falha na conexão: " . $conexao->connect_error);
}
$idUsuarioSessao = 1; // usuário de teste

$daoChecklist = new DaoChecklist($conexao, $idUsuarioSessao);

// -------------------
// TESTE DE INSERÇÃO
// -------------------
$checklistNovo = new Checklist(
    null,          // ID (novo)
    $idUsuarioSessao, 
    1,             // tipo checklist (1 = veicular, por exemplo)
    2,             // objeto/veículo
    date('Y-m-d H:i:s'), 
    null, 
    1              // status 1 = aberto
);

$idChecklistInserido = $daoChecklist->iniciarCheckList($checklistNovo);
echo "Checklist inserido ID: $idChecklistInserido\n";

// -------------------
// TESTE DE SELEÇÃO
// -------------------
$checklistSelecionado = $daoChecklist->selecionarChecklist($idChecklistInserido);
if ($checklistSelecionado) {
    echo "Checklist selecionado: " . $checklistSelecionado->getIdChecklist() . "\n";
}

// -------------------
// TESTE DE ATUALIZAÇÃO
// -------------------
$checklistSelecionado->setDataFim(date('Y-m-d H:i:s'));
$checklistSelecionado->setStatusChecklist(3); // fechado
$resultAtualizacao = $daoChecklist->atualizarChecklist($checklistSelecionado);
echo "Checklist atualizado: $resultAtualizacao linhas afetadas\n";

// -------------------
// TESTE DE LISTAGEM
// -------------------
$listaChecklists = $daoChecklist->listaChecklists();
echo "Total de checklists listados: " . count($listaChecklists) . "\n";

// -------------------
// TESTE DE FILTRO
// -------------------
$filtros = [
    'numero' => $idChecklistInserido,
    'data_inicio' => date('d/m/Y'), // teste com data de hoje
    'tipo' => 1,
    'objeto' => 2,
    'usuario' => $idUsuarioSessao,
    'status' => 3
];

$checklistsFiltrados = $daoChecklist->filtrarChecklists($filtros);
echo "Checklists filtrados: " . count($checklistsFiltrados) . "\n";

foreach ($checklistsFiltrados as $chk) {
    echo "ID: " . $chk->getIdChecklist() . " | Usuário: " . $chk->getFkUsuario() . " | Tipo: " . $chk->getFkTipo() . "\n";
}

?>

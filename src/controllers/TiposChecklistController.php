<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use models\TipoChecklist;
use rn\RnChecklist;
use rn\RnResponsavel;
use rn\RnTipoChecklist;
use Util\Sessao;

class TiposChecklistController{
    private $rnTiposChecklist;

    function __construct(RnTipoChecklist $rnTiposChecklist){
        $this->rnTiposChecklist = $rnTiposChecklist;
    }

    function carregarformulario(){
        $listaResposaveis = (new RnResponsavel(Sessao::idusuario()))->gerarListaResponsaveis();
        require_once __DIR__ .'/../views/features/checklists/tipos/cadastrotipo.php';
    }

    function cadastrarnovotipo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tipoChecklist = new TipoChecklist(1, strtoupper($_POST['descricao']), $_POST['responsavel'], $_POST['statustipochecklist']);

            /*echo "<pre>";
            var_dump($tipoChecklist);*/

            $idTipoChecklist = $this->rnTiposChecklist->cadastrarNovoTipoChecklist($tipoChecklist);

            var_dump($idTipoChecklist);

            if($idTipoChecklist > 0){
                header("Location: /syscheck/tiposchecklist/gerenciarTipos");
            } else {
                header("Location: /syscheck/checklist");
            }

        } else {

            $this->carregarformulario();
        }
    }

    function gerenciarTipos(){
        $listaTipos = $this->rnTiposChecklist->retornarListaTiposChecklist();
        require_once __DIR__ . '/../views/features/checklists/tipos/gerenciartiposchecklist.php';
    }

    function alterartipo($idTipo){
        $tipoChecklist = $this->rnTiposChecklist->selecionarTipoChecklist($idTipo);
        require_once __DIR__ . '/../views/features/checklists/tipos/alterartipo.php';
    }

    function salvaralteracao(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tipoChecklist = new TipoChecklist($_POST['idtipochecklist'], strtoupper($_POST['descricao']), $_POST['statustipochecklist']);

            $qtdLinhas = $this->rnTiposChecklist->alterarTipoChecklist($tipoChecklist);

            if($qtdLinhas > 0){
                header("Location: /syscheck/checklist");
            } else {
                header("Location: /syscheck/checklist");
            }
        }
    }
}

?>
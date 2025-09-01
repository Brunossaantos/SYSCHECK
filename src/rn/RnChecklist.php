<?php

namespace rn;

require __DIR__ .'/../../vendor/autoload.php';

use models\Checklist;
use models\Erro;
use DAO\DaoErro;
use DAO\DaoChecklist;
use database\Conexao;

class RnChecklist{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function iniciarChecklist(Checklist $checklist){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->iniciarCheckList($checklist);
    }

    function selecionarChecklist($idChecklist){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarChecklist($idChecklist);
    }

    function atualizarChecklist(Checklist $checklist){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->atualizarChecklist($checklist);
    }

    function listarChecklists(){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->listaChecklists();
    }

    function listarComFiltros($filtros){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->filtrarChecklists($filtros);
    }

    function listarChecklistsVeiculares(){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->listarChecklistVeicular();
    }  
    
    function verificarChecklistPendente($fkUsuario){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarChecklistPorUsuario($fkUsuario);
    }

    function verificarPendencia($fkUsuario){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarChecklistPendente($fkUsuario); 
    }
    
    function recuperarHorimetrosPorChecklist($fkUsuario){
        return (new DaoChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->recuperarHorimetrosPorChecklist($fkUsuario);
    }

}

?>
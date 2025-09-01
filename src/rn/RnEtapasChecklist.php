<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoEtapaRealizada;
use models\EtapasChecklist;
use DAO\DaoEtapasChecklist;
use database\Conexao;

class RnEtapasChecklist{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function cadastraNovaEtapa(EtapasChecklist $etapa){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirEtapaChecklist($etapa);
    }

    function seleionarEtapaChecklist($fkTipo, $numeroEtapa){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarEtapaChecklist($fkTipo, $numeroEtapa);
    }

    function selecionarEtapaPeloId(int $idEtapa){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarEtapaPeloId($idEtapa);
    }

    function alterarEtapaChecklist(EtapasChecklist $etapa){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->alterarEtapaChecklist($etapa);
    }

    function listarEtapasChecklist($fkTipo){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->retornarListaEtapas($fkTipo);
    }  
    
    function quantidadeEtapas($fkTipo){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->quantidadeEtapas($fkTipo);
    }

    function organizarNumeroEtapas($fktTipoChecklist){
        return (new DaoEtapasChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->organizarNumeroEtapas($fktTipoChecklist);
    }

    

        
}

?>
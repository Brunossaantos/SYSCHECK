<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\EtapaRealizada;
use DAO\DaoEtapaRealizada;
use database\Conexao;

class RnEtapaRealizada
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirEtapaRealizada(EtapaRealizada $etapaRealizada)
    {
        return (new DaoEtapaRealizada((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirEtapaRealizada($etapaRealizada);
    }

    function selecionarEtapaRalizada($idEtapaRealizada)
    {
        return (new DaoEtapaRealizada((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarEtapaRealizada($idEtapaRealizada);
    }

    function montarChecklist(int $idChecklist)
    {
        return (new DaoEtapaRealizada((new Conexao())->conectar(), $this->idUsuarioSessao))->listaEtapasPorChecklist($idChecklist);
    }

    function verificarUltimaEtapa($idChecklist)
    {
        return (new DaoEtapaRealizada((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarUltimaEtapa($idChecklist);
    }
}

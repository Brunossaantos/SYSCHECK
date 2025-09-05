<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\TipoChecklist;
use DAO\DaoTiposChecklist;
use database\Conexao;

class RnTipoChecklist
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function cadastrarNovoTipoChecklist(TipoChecklist $tipoChecklist)
    {
        return (new DaoTiposChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirTipoChecklist($tipoChecklist);
    }

    function selecionarTipoChecklist($idTipoChecklist)
    {
        return (new DaoTiposChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarTipoChecklist($idTipoChecklist);
    }

    function alterarTipoChecklist(TipoChecklist $tipoChecklist)
    {
        return (new DaoTiposChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->alteraTipoChecklist($tipoChecklist);
    }

    function retornarListaTiposChecklist()
    {
        return (new DaoTiposChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->retornarListaTiposChecklist();
    }

    function retornarResponsavel(int $idTipoChecklist)
    {
        return (new DaoTiposChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarResponsavel($idTipoChecklist);
    }

    function verificarTipoEmpilhadeira(int $fkTipoChecklist)
    {
        return (new DaoTiposChecklist((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarTipoEmpilhadeira($fkTipoChecklist);
    }
}

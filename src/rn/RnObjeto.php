<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Objeto;
use DAO\DaoObjeto;
use database\Conexao;

class RnObjeto
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function cadastraNovoObjeto(Objeto $objeto)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirObjeto($objeto);
    }

    function selecionarObjeto($idObjeto)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarObjeto($idObjeto);
    }

    function alterarObjeto(Objeto $objeto)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->alterarObjeto($objeto);
    }

    function listarObjetosPeloTipo($fkTipo)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->listarObjetosPeloTipo($fkTipo);
    }

    function listarObejetos()
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->listarObjetos();
    }
}

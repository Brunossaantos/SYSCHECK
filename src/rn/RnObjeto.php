<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';


use models\Objeto;
use DAO\DaoObjeto;
use database\Conexao;
use Util\Sessao;

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
        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();

        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->listarObjetosPeloTipo($fkTipo, $fkEmpresa);
    }

    function listarObjetos()
    {
        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();

        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->listarObjetos($fkEmpresa);
    }

    public function listarObjetosAtivos()
    {
        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();

        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->listarObjetosAtivos($fkEmpresa);
    }
}

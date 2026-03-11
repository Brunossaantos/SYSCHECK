<?php

namespace rn;

include_once __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoInterface;
use database\Conexao2;
use database\Conexao;

class RnInterface
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function verificarConexao()
    {
        $conexao = new Conexao2();
        var_dump($conexao->conectar());
    }

    function listarDepartamentos()
    {
        $daoInterface = new DaoInterface((new Conexao2())->conectar(), $this->idUsuarioSessao);
        $listaDepartamentos = $daoInterface->listarDepartamentos();

        $departamentoAtivos = array_filter($listaDepartamentos, function ($departamento) {
            return $departamento['status'] == 1;
        });

        return $departamentoAtivos;
    }

    function listaColaboradores()
    {
        $daoInterface = new DaoInterface((new Conexao2())->conectar(), $this->idUsuarioSessao);
        $listaColaboradores = $daoInterface->listaColaboradores();

        $colaboradoresAtivos = array_filter($listaColaboradores, function ($colaborador) {
            return $colaborador['status'] == 1;
        });

        return $colaboradoresAtivos;
    }

    function listarCargosEtreinamento()
    {
        $daoInterface = new DaoInterface((new Conexao2())->conectar(), $this->idUsuarioSessao);
        $listaCargos = $daoInterface->listaCargosEtreinamento();
        return $listaCargos;
    }

    function sincronizarCargos() {}
}

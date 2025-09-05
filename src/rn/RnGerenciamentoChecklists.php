<?php

namespace rn;

use DAO\DaoHorimetro;
use database\Conexao;

require __DIR__ . '/../../vendor/autoload.php';

class RnGerenciamentoChecklists
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function listaChecklists()
    {
        return (new DaoHorimetro((new Conexao())->conectar(), $this->idUsuarioSessao))->listaChecklistsEmpilhadeiras(600);
    }
}

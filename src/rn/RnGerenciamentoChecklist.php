<?php

namespace rn;

require_once __DIR__ . '/../../vendor/autoload.php';

use database\Conexao;
use DAO\DaoGerenciamentoChecklists;

class RnGerenciamentoChecklist
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function salvarLogFinalizarHorimetro($logFimHorimetro)
    {
        return (new DaoGerenciamentoChecklists((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarLogFinalizacaoHorimetro($logFimHorimetro);
    }
}

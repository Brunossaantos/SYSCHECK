<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Responsavel;
use DAO\DaoResponsavel;
use database\Conexao;

class RnResponsavel
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function selecionarResponsavel(int $fkResponsavel)
    {
        return (new DaoResponsavel((new Conexao)->conectar(), $this->idUsuarioSessao))->selecionarResponsavel($fkResponsavel);
    }

    function gerarListaResponsaveis()
    {
        return (new DaoResponsavel((new Conexao)->conectar(), $this->idUsuarioSessao))->gerarListaResponsaveis();
    }
}

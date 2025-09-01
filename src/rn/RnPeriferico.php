<?php

namespace rn;

use DAO\DaoPerifericoBateria;

use database\Conexao;

require __DIR__ . '/../../vendor/autoload.php';

class RnPeriferico{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function listarPerifericos(){
        return (new DaoPerifericoBateria((new Conexao())->conectar(), $this->idUsuarioSessao))->listaPerifericos();
    }
}


?>
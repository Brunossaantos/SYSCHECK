<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Foto;
use DAO\DaoFoto;
use database\Conexao;

class RnFoto{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirFoto(Foto $foto){
        return (new DaoFoto((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirFoto($foto);
    }

    function selecionarFoto(int $fkChecklist, int $numeroEtapa){
        return (new DaoFoto((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarFoto($fkChecklist, $numeroEtapa);
    }

    function selecionarFotoChecklist(int $idChecklist){
        return (new DaoFoto((new Conexao())->conectar(), $this->idUsuarioSessao))->listaFotosChecklist($idChecklist);
    }

    function selecionarFotoEtapa(int $fkChecklist, int $numeroEtapa){
        return (new DaoFoto((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarFotoEtapa($fkChecklist, $numeroEtapa);
    }

    function listarFotosPorEtapa(int $fkChecklist, int $numeroEtapa){
        return (new DaoFoto((new Conexao())->conectar(), $this->idUsuarioSessao))->listarFotosPorEtapa($fkChecklist, $numeroEtapa);
    }

}

?>
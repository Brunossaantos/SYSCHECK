<?php

namespace rn;

require __DIR__ .'/../../vendor/autoload.php';

use database\Conexao;
use Util\Sessao;
//instancie aqui as classes que devem ser testadas;
use rn\RnHorimetro;


class RnTesteUnitario{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function testeHorimetro(){
        $listaHorimetros = (new RnHorimetro(Sessao::idusuario()))->recuperarListaHorimetros(600);

        echo"<pre>";
        var_dump($listaHorimetros);
    }

    
}



?>
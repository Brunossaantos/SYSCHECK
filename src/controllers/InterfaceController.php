<?php

namespace controllers;

use rn\RnInterface;
use Util\Sessao;

include_once __DIR__ . '/../../vendor/autoload.php';


class InterfaceController{
    private $rnInterface;

    function __construct($rnInterface){
        $this->rnInterface = $rnInterface;
    }

    function index(){
        echo "Lógica de construtores funcionando";
    }

    function verificarConexao(){
        return (new RnInterface(Sessao::idusuario()))->verificarConexao();
    }

    function listarDeparmentos(){
        $rnInterface = new RnInterface(Sessao::idusuario());
        $listaDepartamentos = $rnInterface->listarDepartamentos();
        
        return json_encode($listaDepartamentos);
    }

    function listarColaboradores(){
        $rnInterface = new RnInterface(Sessao::idusuario());
        $listaColaboradores = $rnInterface->listaColaboradores();

        return json_encode($listaColaboradores);
    }

    function listarCargos(){
        $listaCargos = (new RnInterface(Sessao::idusuario()))->listarCargosEtreinamento();

        for($cont =0; $cont < count($listaCargos); $cont++){
            echo $listaCargos[$cont]."<br>";
        }
    }
}

?>
<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use rn\RnChecklist;
use rn\RnTesteUnitario;
use rn\RnHorimetro;
use Util\Sessao;


class TesteUnitarioController{

    private $rnTesteUnitario;

    function __construct($rnTesteUnitario){
        $this->rnTesteUnitario = $rnTesteUnitario;
    }

    function index(){
        require_once __DIR__ . '/../views/features/documentacao/testeunitario.html';
    }

    function testehorimetros($fkChecklist){
        $rnHorimetro = (new RnHorimetro(Sessao::idusuario()));
        $listaHorimetros = $rnHorimetro->recuperarListaHorimetros($fkChecklist);

        echo "<pre>";
        var_dump($listaHorimetros);

        echo "<br>";

        $qtdHorimetrosRegistrados = sizeof($listaHorimetros);

        if($qtdHorimetrosRegistrados <= 1){
            echo "Equipamento em uso";
        } else {            
            echo "Horimetro inicial: ".$listaHorimetros[0]['horimetro'];
            echo "<br>";
            echo "Horimetro final: ".$listaHorimetros[1]['horimetro'];
        }
    }

    function listahorimetros(){
        $rnHorimetro = (new RnHorimetro(Sessao::idusuario()));
        $listaHorimetros = $rnHorimetro->recuperarHorimetroPorEquipamento(94);

        echo "<h1>Quantidade de horimetros registrados: ".sizeof($listaHorimetros)."</h1>";

        echo "<pre>";
        echo "Ultimos dois horimetros";

        var_dump($listaHorimetros[sizeof($listaHorimetros)-2]);
        var_dump($listaHorimetros[sizeof($listaHorimetros)-1]);

        echo "<h1>Status do equipamento</h1>";

        echo "ID do primeiro checklist da comparação: ".$listaHorimetros[sizeof($listaHorimetros)-2]['fkChecklist']."<br>";
        echo "ID do segundo checklist da comparação: ".$listaHorimetros[sizeof($listaHorimetros)-1]['fkChecklist']."<br>";

        if($listaHorimetros[sizeof($listaHorimetros)-2]['fkChecklist'] != $listaHorimetros[sizeof($listaHorimetros)-1]['fkChecklist']){
            echo "<h2>Equipamento com horimetro aberto</h2>";
        } else {
            echo "<h2>Equipamento livre para uso</h2>";            
        }        

        echo "<h1>Lista de completa de horimetros</h1>";
        
        var_dump($listaHorimetros);
    }

    function testeListaHorimetros(){
        $rnChecklist = new RnChecklist(Sessao::idusuario());
        $listaHorimetros = $rnChecklist->recuperarHorimetrosPorChecklist(25);

        echo "<pre>";
        var_dump($listaHorimetros);

        echo "<br>Tamanho do array retornado: ".sizeof($listaHorimetros);

        $horimetrosVazios = 0;
        
        for($cont = 0; $cont < sizeof($listaHorimetros); $cont++){            
            if($listaHorimetros[$cont]['horimetroFinal'] == null){
                $horimetrosVazios++;
            }
        }
        
        echo "<br>Quantidade horimetros vazios: ".$horimetrosVazios;
    }


}

?>
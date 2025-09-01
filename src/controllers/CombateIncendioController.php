<?php

namespace controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use rn\RnCombateIncendio;
use models\Local;
use models\TipoChecklist;
use models\Objeto;
use database\Conexao;
use DAO\DaoLocal;
use Util\Sessao;

class CombateIncendioController {
    private $rnCombateIncendio;
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao) {
        $this->idUsuarioSessao = $idUsuarioSessao;
        $this->rnCombateIncendio = new RnCombateIncendio($idUsuarioSessao);
    }

    public function index() {
        //echo "Módulo de Combate a Incêndio \n";
        
        $target = 10;
        $array = [1, 3, 5, 7, 9];
        $resultado = [];

        for($cont = 0; $cont <= count($array) -1; $cont++){
            echo "Indice: " . $cont . " - Valor: " . $array[$cont] . "<br>";
        }

        for($cont = 0; $cont <= count($array) -1; $cont++){
            
            for($ind = 0; $ind <= count($array) -1; $ind++){
                if($array[$cont] + $array[$ind] == $target && $array[$cont] != $array[$ind]){
                    echo "O número que está no indice " . $cont . " e o número que está no indice " . $ind . " somam " . $target . "<br>";
                    //exit;

                    $resultado[] = [$array[$cont], $array[$ind]];
                }
            }

        }

        echo "<pre>";
        print_r($resultado);

    }

    public function cadastrarLocal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $descricao = $_POST['descricao'];
            $status = $_POST['status'];
            
            if(isset($descricao) && isset($status)){

                $local = new Local(null, $descricao, $status);                
                $idLocal = $this->rnCombateIncendio->inserirLocal($local);

                if($idLocal > 0){
                    Sessao::salvarMensagemNaSessao("Local cadastrado com sucesso!");
                    header("Location:/syscheck/combateincendio/listarLocaisCadastrados/");
                    exit;
                }else{
                    Sessao::salvarMensagemNaSessao("Não foi possível cadastrar o local.");
                    header("Location:/syscheck/combateincendio/");
                    exit;
                }
            }

        } else {
            require_once __DIR__ . '/../views/features/sistemacombateincendio/cadastrolocal.php';
        }
               
    }

    function listarLocaisCadastrados(){
        $listaLocais = $this->rnCombateIncendio->listarLocaisCadatrados();

        require_once __DIR__ . '/../views/features/sistemacombateincendio/listarlocais.php';
    }

    function listarEquipamentosLocal($idLocal){
        $listaEquipamentos = $this->rnCombateIncendio->listarEquipamentosLocal($idLocal);

        //var_dump($listaEquipamentos);

        $local = $this->rnCombateIncendio->selecionarLocal($idLocal);

        require_once __DIR__ . '/../views/features/sistemacombateincendio/listarequipamentos.php';
    }



    


}

?>
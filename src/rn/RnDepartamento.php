<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use controllers\InterfaceController;
use models\Departamento;
use database\Conexao;
use DAO\DaoDepartamento;
use Util\Sessao;

class RnDepartamento{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function cadastrarDepartamento(Departamento $departamento){
        return (new DaoDepartamento((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirDepartamento($departamento);
    }

    function selecionarDepartamento($idDepartamento){
        return (new DaoDepartamento((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarDepartamento($idDepartamento);
    }

    function listarDepartamentos(){

        $rnInterface = new RnInterface(Sessao::idusuario());
        $interface = new InterfaceController($rnInterface);
        $listaDepartamentos = json_decode($interface->listarDeparmentos(), true);     

        $listaConvertida = [];

        foreach($listaDepartamentos as $departamento){            
            $departamentoConvertido = new Departamento($departamento['idDepartamento'], $departamento['departamento'], $departamento['status']);           

            $listaConvertida[] = $departamentoConvertido;
        }

        return $listaConvertida;
    }
}

?>
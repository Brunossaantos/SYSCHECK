<?php

namespace DAO;

include_once __DIR__ .'/../../vendor/autoload.php';
include_once __DIR__ . '/../constantes/constTabelasdb.php';

use Exception;
use Util\Util;



class DaoInterface{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_departamentos = tbl_deparmantos;
    private $tbl_colaboradores = tbl_colaboradores;
    

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function listarDepartamentos(){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_DEPARTAMENTO, DEPARTAMENTO, STATUS_DEPARTAMENTO FROM {$this->tbl_departamentos}");
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $listaDepartamentos [] = [
                        'idDepartamento' => $row['ID_DEPARTAMENTO'],
                        'departamento' => $row['DEPARTAMENTO'],
                        'status' => $row['STATUS_DEPARTAMENTO']
                    ];
                }

                return $listaDepartamentos;
            }

            return [];

        }catch(Exception $e){
            Util::inserirErro($e, "listarDerpatamentos", $this->idUsuarioSessao);
            return[];
        }
    }

    function listaColaboradores(){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_COLABORADOR, NOME, EMPRESA, CARGO, HEXADECIMAL, MATRICULA, DEPARTAMENTO, STATUS_COLABORADOR FROM {$this->tbl_colaboradores}");
            $stmt->execute();

            $result = $stmt->get_result();

            if($result->num_rows >0){
                while($row = $result->fetch_assoc()){
                    $listaColaboradores [] = [
                        'idColaborador' => $row['ID_COLABORADOR'],
                        'nome' => $row['NOME'],
                        'empresa' => $row['EMPRESA'],
                        'cargo' => $row['CARGO'],
                        'hexadecimal' => $row['HEXADECIMAL'],
                        'matricula' => $row['MATRICULA'],
                        'departamento' => $row['DEPARTAMENTO'],
                        'status' => $row['STATUS_COLABORADOR'] 
                    ];
                }

                return $listaColaboradores;
            }

            return [];
        }catch (Exception $e){
            Util::inserirErro($e, "listarColaboradores", $this->idUsuarioSessao);
            return[];
        }
    }

    function listaCargosEtreinamento(){
        try{
            $stmt = $this->conexao->prepare("SELECT CARGO FROM {$this->tbl_colaboradores} GROUP By CARGO");
            $stmt->execute();
            $result = $stmt->get_result();            

            if($result->num_rows >0){ 
                $listaCargos = [];               
                while($row = $result->fetch_assoc()){
                    $listaCargos[] = $row['CARGO'];
                }
                return $listaCargos;
            }            

            return [];

        }catch(Exception $e){
            Util::inserirErro($e, "listarCargos", $this->idUsuarioSessao);
            return[];
        }
    }   


}

?>
<?php

namespace DAO;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../constantes/constTabelasdb.php';

use Exception;
use models\Local;
use Util\Util;


class DaoLocal{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_locais = TBL_LOCAIS;
    private $tbl_equipamentos_local = TBL_EQUIPAMENTOS_LOCAL;
    
    function __construct($conexao, $idUsuarioSessao) {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;        
    }

    public function inserirLocal(Local $local){
        
        $descricao = $local->getDescricaoLocal();
        $status = $local->getStatusLocal();
        
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_locais} (DESCRICAO_LOCAL, STATUS_LOCAL) VALUES (?, ?)");
            $stmt->bind_param("si", $descricao, $status);
            
            if($stmt->execute()){
                return $stmt->insert_id;
            }else{
                return -1; // Falha na inserção
            }

        }catch(Exception $e){
            Util::inserirErro($e, "inserirLocal", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarLocal($idLocal){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_LOCAL, DESCRICAO_LOCAL, STATUS_LOCAL FROM {$this->tbl_locais} WHERE ID_LOCAL = ?");
            $stmt->bind_param("i", $idLocal);
            $stmt->execute();
            
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return new Local($row['ID_LOCAL'], $row['DESCRICAO_LOCAL'], $row['STATUS_LOCAL']);
            }

            return null; // Local não encontrado            
 
        }catch(Exception $e){
            Util::inserirErro($e, "selecionarLocal", $this->idUsuarioSessao);
            return null;
        }
    }

    function listarLocais(){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_LOCAL, DESCRICAO_LOCAL, STATUS_LOCAL FROM {$this->tbl_locais} ORDER BY ID_LOCAL ASC");
            $stmt->execute();

            $result = $stmt->get_result();
            $locais = [];   

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $locais[] = new Local($row['ID_LOCAL'], $row['DESCRICAO_LOCAL'], $row['STATUS_LOCAL']);
                }
            }

            return $locais; // Retorna um array de objetos Local

        }catch(Exception $e){
            Util::inserirErro($e, "listarLocais", $this->idUsuarioSessao);
            return [];
        }
    }

    function atualizarLocal(Local $local){
        $idLocal = $local->getIdLocal();
        $descricao = $local->getDescricaoLocal();
        $status = $local->getStatusLocal();

        try{
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_locais} SET DESCRICAO_LOCAL = ?, STATUS_LOCAL = ? WHERE ID_LOCAL = ?");
            $stmt->bind_param("sii", $descricao, $status, $idLocal);
            
            if($stmt->execute()){
                return $stmt->affected_rows; // Atualização bem-sucedida
            }

            return -1; // Falha na atualização

        }catch(Exception $e){
            Util::inserirErro($e, "atualizarLocal", $this->idUsuarioSessao);
            return -2; // Erro na execução da consulta
        }
    }

    function associarEquipamentoLocal($fkLocal, $fkEquipamento){
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_equipamentos_local} (FK_LOCAL, FK_EQUIPAMENTO) VALUES (?, ?)");
            $stmt->bind_param("ii", $fkLocal, $fkEquipamento);
            
            if($stmt->execute()){
                return $stmt->insert_id; // Retorna o ID do registro inserido
            }

            return -1; // Falha na inserção
        }catch(Exception $e){
            Util::inserirErro($e, "associarEquipamentoLocal", $this->idUsuarioSessao);
            return -1; // Erro na execução da consulta
        }
    }

    function listarEquipamentosPorLocal($fkLocal){
        try{
            $stmt = $this->conexao->prepare("SELECT FK_EQUIPAMENTO FROM {$this->tbl_equipamentos_local} WHERE FK_LOCAL = ?");
            $stmt->bind_param("i", $fkLocal);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $equipamentos = [];

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $equipamentos[] = $row['FK_EQUIPAMENTO'];
                }
            }

            return $equipamentos; // Retorna um array com os IDs dos equipamentos associados ao local

        }catch(Exception $e){
            Util::inserirErro($e, "listarEquipamentosPorLocal", $this->idUsuarioSessao);
            return []; // Retorna um array vazio em caso de erro
        }
    }

}
?>
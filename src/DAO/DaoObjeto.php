<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Objeto;
use database\Conexao;
use Util\Util;

class DaoObjeto{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_objetos = TBL_OBJETOS;

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirObjeto(Objeto $objeto){
        $descricao = $objeto->getDescricaoObjeto();
        $fkTipo = $objeto->getFkTipoChecklist();
        $status = $objeto->getStatusObjeto();

        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_objetos} (DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO) VALUES (?,?,?)");
            $stmt->bind_param("sii", $descricao, $fkTipo, $status);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e){
            Util::inserirErro($e, "inserirObjeto", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarObjeto($idObjeto){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO FROM {$this->tbl_objetos} WHERE ID_OBJETO = ?");
            $stmt->bind_param("i", $idObjeto);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return new Objeto($row['ID_OBJETO'], $row['DESCRICAO_OBJETO'], $row['FK_TIPO_CHECKLIST'], $row['STATUS_OBJETO']);                
            }

            return null;
        } catch (Exception $e){
            Util::inserirErro($e, "selecionarObjeto", $this->idUsuarioSessao);
            return null;
        }
    }

    function alterarObjeto(Objeto $objeto){
        $idObjeto = $objeto->getIdObjeto();
        $descricao = $objeto->getDescricaoObjeto();
        $fkTipo = $objeto->getFkTipoChecklist();
        $status = $objeto->getStatusObjeto();

        try{
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_objetos} SET DESCRICAO_OBJETO = ?, FK_TIPO_CHECKLIST = ?, STATUS_OBJETO = ? WHERE ID_OBJETO = ?");
            $stmt->bind_param("siii", $descricao, $fkTipo, $status, $idObjeto);

            if($stmt->execute()){
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e){
            Util::inserirErro($e, "alterarObjeto", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listarObjetosPeloTipo($fkTipo){
        $listaObjetos = [];
        try{
            $stmt = $this->conexao->prepare("SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO FROM {$this->tbl_objetos} WHERE FK_TIPO_CHECKLIST = ?");
            $stmt->bind_param("i", $fkTipo);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows >0){
                while($row = $result->fetch_assoc()){
                    $objeto = new Objeto($row['ID_OBJETO'], $row['DESCRICAO_OBJETO'], $row['FK_TIPO_CHECKLIST'], $row['STATUS_OBJETO']);
                    $listaObjetos[] = $objeto;
                }
            }

            return $listaObjetos;
        } catch (Exception $e){
            Util::inserirErro($e, "listarObjetosPeloTIpo", $this->idUsuarioSessao);
            return null;
        }
    }

    function listarObjetos(){
        $listaObjetos = [];
        try {
            $stmt = $this->conexao->prepare("SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO FROM {$this->tbl_objetos}");
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $objeto = new Objeto($row['ID_OBJETO'], $row['DESCRICAO_OBJETO'], $row['FK_TIPO_CHECKLIST'], $row['STATUS_OBJETO']);
                    $listaObjetos[] = $objeto;
                }
            }

            return $listaObjetos;
        } catch (Exception $e){
            Util::inserirErro($e, "listarObjetos", $this->idUsuarioSessao);
            return null;
        }
    }
}

?>
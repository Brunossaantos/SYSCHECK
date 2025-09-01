<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\TipoChecklist;
use Exception;
use database\Conexao;
use Util\Util;

class DaoTiposChecklist{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_tipos_checklist = TBL_TIPOS_CHECKLIST;
    private $tbl_tpc_temp = TBL_TPC_TEMP;

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirTipoChecklist(TipoChecklist $tipoChecklist){
        $descricao = $tipoChecklist->getDescricaoTipoChecklist();
        $responsavel = $tipoChecklist->getFkResponsavel();
        $status = $tipoChecklist->getStatusTipoChecklist();

        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_tipos_checklist} (DESCRICAO_TIPO_CHECKLIST, FK_RESPONSAVEL, STATUS_TIPO_CHECKLIST) VALUES (?,?,?)");
            $stmt->bind_param("sii", $descricao, $responsavel, $status);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e){
            //echo "<pre>";
            //var_dump($e);
            Util::inserirErro($e, "inserirTipoChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarTipoChecklist($idTipoChecklist){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_TIPO_CHECKLIST, DESCRICAO_TIPO_CHECKLIST, FK_RESPONSAVEL, STATUS_TIPO_CHECKLIST FROM {$this->tbl_tipos_checklist} WHERE ID_TIPO_CHECKLIST = ?");
            $stmt->bind_param("i", $idTipoChecklist);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return new TipoChecklist($row['ID_TIPO_CHECKLIST'], $row['DESCRICAO_TIPO_CHECKLIST'], $row['FK_RESPONSAVEL'], $row['STATUS_TIPO_CHECKLIST']);
            }

            return null;
        } catch (Exception $e){
            Util::inserirErro($e, "selecionarTipoChecklist", $this->idUsuarioSessao);
            return null;
        }
    }

    function retornarListaTiposChecklist(){
        $listaTiposChecklist = [];
        try{
            $stmt = $this->conexao->prepare("SELECT ID_TIPO_CHECKLIST, DESCRICAO_TIPO_CHECKLIST, FK_RESPONSAVEL, STATUS_TIPO_CHECKLIST 
            FROM {$this->tbl_tipos_checklist}
            WHERE STATUS_TIPO_CHECKLIST = 1"); // <--- filtra apenas ativos

            $stmt->execute();
            $result = $stmt->get_result();     
            
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $tipoChecklist = new TipoChecklist($row['ID_TIPO_CHECKLIST'], $row['DESCRICAO_TIPO_CHECKLIST'], $row['FK_RESPONSAVEL'], $row['STATUS_TIPO_CHECKLIST']);
                    $listaTiposChecklist[] = $tipoChecklist;
                }
            }

            return $listaTiposChecklist;
        } catch (Exception $e){
            Util::inserirErro($e, "retornarListaTiposChecklist", $this->idUsuarioSessao);
            return null;
        }
    }

    function alteraTipoChecklist(TipoChecklist $tipoChecklist){
        $idTipoChecklist = $tipoChecklist->getIdTipoChecklist();
        $descricao = $tipoChecklist->getDescricaoTipoChecklist();
        $status = $tipoChecklist->getStatusTipoChecklist();

        try{
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_tipos_checklist} SET DESCRICAO_TIPO_CHECKLIST = ?, STATUS_TIPO_CHECKLIST = ? WHERE ID_TIPO_CHECKLIST = ?");
            $stmt->bind_param("sii", $descricao, $status, $idTipoChecklist);

            if($stmt->execute()){
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e){
            Util::inserirErro($e, "alterarTipoChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    function verificarResponsavel(int $fkTipoChecklist){
        try{
            $stmt = $this->conexao->prepare("SELECT FK_RESPONSAVEL FROM {$this->tbl_tipos_checklist} WHERE ID_TIPO_CHECKLIST = ?");
            $stmt->bind_param("i", $fkTipoChecklist);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return $row['FK_RESPONSAVEL'];
            }

            return 0;
        } catch (Exception $e){
            Util::inserirErro($e, "verificarResponsavel", $this->idUsuarioSessao);
            return -1;
        }
    }


    function verificarTipoEmpilhadeira(int $fkTipoChecklist){        
        try{
            $stmt = $this->conexao->prepare("SELECT ID_TCTE, FK_TCKL, FK_TEMP FROM {$this->tbl_tpc_temp} WHERE FK_TCKL = ?");
            $stmt->bind_param("i", $fkTipoChecklist);
            
            $stmt->execute();
            $result = $stmt->get_result();

            $tpChecklistTpEmp = [];

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $tpChecklistTpEmp[] = [
                    'ID_RELACAO' => $row['ID_TCTE'],
                    'FK_TIPO_CHECKLIST' => $row['FK_TCKL'],
                    'FK_TIPO_EMPILHADEIRA' => $row['FK_TEMP']
                ]; 
            }            

            return $tpChecklistTpEmp;

        }catch(Exception $e){

            Util::inserirErro($e, "verificarTipoEmpilhadeira", $this->idUsuarioSessao);
            return null;
        }
    }

         

    
}


?>
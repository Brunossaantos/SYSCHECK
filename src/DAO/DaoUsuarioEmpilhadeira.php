<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use database\Conexao;
use Util\Util;

class DaoUsuarioEmpilhadeira{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_usuario_empilhadeira = TBL_USUARIO_EMPILHADEIRA;

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function iniciarExpediente($fkChecklist, $fkUsuario, $fkEmpilhadeira, $dataHoraInicio){
        $dataHoraEncerramento = "0";
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_usuario_empilhadeira} (FK_CHECKLIST, FK_USUARIO, FK_EMPILHADEIRA, DATA_HORA_INICIO, DATA_HORA_ENCERRAMENTO) VALUES (?,?,?,?,?)");
            $stmt->bind_param("iiiss", $fkChecklist, $fkUsuario, $fkEmpilhadeira, $dataHoraInicio, $dataHoraEncerramento);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;
        }catch(Exception $e){
            Util::inserirErro($e, "iniciarExpediente", $this->idUsuarioSessao);
            return -2;
        }
    }

    function encerrarExpediente($fkChecklist, $dataHoraEncerramento){
        try{
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_usuario_empilhadeira} SET DATA_HORA_ENCERRAMENTO = ? WHERE FK_CHECKLIST = ?");
            $stmt->bind_param("si", $dataHoraEncerramento, $fkChecklist);

            if($stmt->execute()){
                return $stmt->affected_rows;
            }

            return -1;
        }catch(Exception $e){
            Util::inserirErro($e, "encerrarExpediente", $this->idUsuarioSessao);
            return -2;
        }
    }

    // adicionado
function verificarChecklistAbertoPorEmpilhadeira(int $fkEmpilhadeira){
    try{
        $stmt = $this->conexao->prepare(
            "SELECT ID_USUARIO_EMPILHADEIRA, FK_CHECKLIST, FK_USUARIO, FK_EMPILHADEIRA, DATA_HORA_INICIO, DATA_HORA_ENCERRAMENTO
             FROM {$this->tbl_usuario_empilhadeira}
             WHERE FK_EMPILHADEIRA = ?
             ORDER BY ID_USUARIO_EMPILHADEIRA DESC
             LIMIT 1"
        );
        $stmt->bind_param("i", $fkEmpilhadeira);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0){
            return $result->fetch_assoc();
        }

        return []; // sem registros
    } catch(Exception $e){
        Util::inserirErro($e, "verificarChecklistAbertoPorEmpilhadeira", $this->idUsuarioSessao);
        return null;
    }
}


    function verificarChecklistAberto($idUsuario){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_USUARIO_EMPILHADEIRA, FK_CHECKLIST, FK_USUARIO, FK_EMPILHADEIRA, DATA_HORA_INICIO, DATA_HORA_ENCERRAMENTO FROM {$this->tbl_usuario_empilhadeira} WHERE FK_USUARIO = ? ORDER BY ID_USUARIO_EMPILHADEIRA DESC LIMIT 1");
            $stmt->bind_param("i", $idUsuario);

            $stmt->execute();
            $result = $stmt->get_result();

            $checklistAberto = [];

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                    return [
                    'ID_USUARIO_EMPILHADEIRA' => $row['ID_USUARIO_EMPILHADEIRA'],
                    'FK_CHECKLIST' => $row['FK_CHECKLIST'],
                    'FK_USUARIO' => $row['FK_USUARIO'],
                    'FK_EMPILHADEIRA' => $row['FK_EMPILHADEIRA'],
                    'DATA_INICIO' => $row['DATA_HORA_INICIO'],
                    'DATA_FIM' => $row['DATA_HORA_ENCERRAMENTO']
                ];
            }

            return $checklistAberto;

        }catch(Exception $e){
            Util::inserirErro($e, "verificarChecklistAberto", $this->idUsuarioSessao);
            return null;
        }
    }
}

?>
<?php

namespace DAO;

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use database\Conexao;
use database\Conexao2;
use DateTime;
use models\Usuario;
use Util\Util;

class DaoLista{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_colaboradores = "colaboradores";
    private $tbl_usuarios = TBL_USUARIOS;
    private $tbl_lista_uso = TBL_LISTA_USO_VEICULO;

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }
    

    function selecionarUsuario($hexadecimal){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_COLABORADOR, NOME, CARGO, HEXADECIMAL FROM {$this->tbl_colaboradores} WHERE HEXADECIMAL = ?");
            $stmt->bind_param("s", $hexadecimal);

            $stmt->execute();
            $result = $stmt->get_result();            

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return [
                    'id_colaborador' => $row['ID_COLABORADOR'],
                    'nome' => $row['NOME'],
                    'cargo' => $row['CARGO'],
                    'hexadecimal' => $row['HEXADECIMAL']
                ];
            }

            return [];
        } catch (Exception $e){
            Util::inserirErro($e, "selecionarUsuario", $this->idUsuarioSessao);
            return [];
        }
    }

    function buscarFkUsuario($nomeUsuario){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, STATUS_USUARIO FROM {$this->tbl_usuarios} WHERE NOME = ?");
            $stmt->bind_param("s", $nomeUsuario);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return new Usuario($row['ID_USUARIO'], $row['NOME'], $row['DEPARTAMENTO'], $row['CARGO'], $row['NOME_USUARIO'], null, $row['STATUS_USUARIO'], 0);
            }

            return null;
        } catch(Exception $e){
            Util::inserirErro($e, "buscarFkUsuario", $this->idUsuarioSessao);
            return null;
        }
    }

    function verificarStatusVeiculo($fkVeiculo){
        try{
            $stmt = $this->conexao->prepare("SELECT STATUS_USO FROM {$this->tbl_lista_uso} where FK_VEICULO = ? ORDER By ID_USO_VEICULO DESC LIMIT 1");
            $stmt->bind_param("i", $fkVeiculo);

            $stmt->execute();
            $result = $stmt->get_result();            

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();

                return $row['STATUS_USO'];
            }

            return 1;
        } catch (Exception $e){
            Util::inserirErro($e, "verificarStatusVeiculo", $this->idUsuarioSessao);
            return 0;
        }
    }

    function selecionarUltimaMovimentacao($movimentacao){
        try{            
            $stmt = $this->conexao->prepare("SELECT ID_USO_VEICULO, FK_USUARIO, FK_VEICULO, DATA_HORA, DATA_HORA_DEVOLUCAO, STATUS_USO FROM {$this->tbl_lista_uso} WHERE FK_VEICULO = ? ORDER By ID_USO_VEICULO LIMIT 1");
            $stmt->bind_param("i", $movimentacao['veiculo']);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return [
                    'idUso' => $row['ID_USO_VEICULO'],
                    'usuario' => $row['FK_USUARIO'],
                    'veiculo' => $row['FK_VEICULO'],
                    'dataInicio' => $row['DATA_HORA'],
                    'dataFinalizacao' => $row['DATA_HORA_DEVOLUCAO'],
                    'status' => $row['STATUS_USO']
                ];
            }

            return [];

        } catch (Exception $e){
            Util::inserirErro($e, "selecinoarUltimaMovimentacao", $this->idUsuarioSessao);
            return [];
        }
    }

    function salvarDevolucao($fkVeiculo){
        try{
            
            $stmt = $this->conexao->prepare("SELECT ID_USO_VEICULO FROM {$this->tbl_lista_uso} WHERE FK_VEICULO = ? ORDER By ID_USO_VEICULO DESC LIMIT 1");
            $stmt->bind_param("i", $fkVeiculo);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                $idUsoVeiculo = $row['ID_USO_VEICULO'];
            }

            $dataHora = (new DateTime())->format('d/m/Y H:i:s');

            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_lista_uso} SET DATA_HORA_DEVOLUCAO = ?, STATUS_USO = 1 WHERE ID_USO_VEICULO = ?");
            $stmt->bind_param("si", $dataHora, $idUsoVeiculo);
            
            $stmt->execute();
            
            return $stmt->affected_rows;

        } catch (Exception $e){
            Util::inserirErro($e, "salvarDevolucao", $this->idUsuarioSessao);
            return -1;
        }
    }

    function salvarMovimentacao($movimentacao){        
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_lista_uso} (FK_USUARIO, FK_VEICULO, DATA_HORA, STATUS_USO) VALUES (?,?,?,2)");
            $stmt->bind_param("iis", $movimentacao['usuario'], $movimentacao['veiculo'], $movimentacao['data']);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return 0;
        } catch(Exception $e){
            Util::inserirErro($e, "salvarMovimentacao", $this->idUsuarioSessao);
            return -1;
        }
    }   
 


}

?>
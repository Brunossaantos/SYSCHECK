<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use database\Conexao;
use database\Conexao2;
use DateTime;
use models\Usuario;
use Util\Util;

class DaoLista
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_colaboradores = "colaboradores";
    private $tbl_usuarios = TBL_USUARIOS;
    private $tbl_lista_uso = TBL_LISTA_USO_VEICULO;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }


    function selecionarUsuario($hexadecimal)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_COLABORADOR, NOME, CARGO, HEXADECIMAL FROM {$this->tbl_colaboradores} WHERE HEXADECIMAL = ?");
            $stmt->bind_param("s", $hexadecimal);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return [
                    'id_colaborador' => $row['ID_COLABORADOR'],
                    'nome'           => $row['NOME'],
                    'cargo'          => $row['CARGO'],
                    'hexadecimal'    => $row['HEXADECIMAL']
                ];
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarUsuario", $this->idUsuarioSessao);
            return [];
        }
    }


    function atualizarStatusVeiculo($fkVeiculo, $novoStatus)
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE {$this->tbl_lista_uso} 
                SET STATUS_USO = ? 
                WHERE FK_VEICULO = ? 
                ORDER BY ID_USO_VEICULO DESC 
                LIMIT 1
            ");
            $stmt->bind_param("ii", $novoStatus, $fkVeiculo);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            Util::inserirErro($e, "atualizarStatusVeiculo", $this->idUsuarioSessao);
            return false;
        }
    }


    function buscarFkUsuario($nomeUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, STATUS_USUARIO 
                FROM {$this->tbl_usuarios} 
                WHERE NOME = ?
            ");
            $stmt->bind_param("s", $nomeUsuario);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Usuario(
                    $row['ID_USUARIO'],
                    $row['NOME'],
                    $row['DEPARTAMENTO'],
                    $row['CARGO'],
                    $row['NOME_USUARIO'],
                    null,
                    $row['STATUS_USUARIO'],
                    0
                );
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "buscarFkUsuario", $this->idUsuarioSessao);
            return null;
        }
    }


    function verificarStatusVeiculo($fkVeiculo)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT STATUS_USO 
                FROM {$this->tbl_lista_uso} 
                WHERE FK_VEICULO = ? 
                ORDER BY ID_USO_VEICULO DESC 
                LIMIT 1
            ");
            $stmt->bind_param("i", $fkVeiculo);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return (int) $row['STATUS_USO'];
            }

            return 1;
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarStatusVeiculo", $this->idUsuarioSessao);
            return 0;
        }
    }


    function selecionarUltimaMovimentacao($movimentacao)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_USO_VEICULO, FK_USUARIO, FK_VEICULO, DATA_HORA, DATA_HORA_DEVOLUCAO, STATUS_USO 
                FROM {$this->tbl_lista_uso} 
                WHERE FK_VEICULO = ? 
                ORDER BY ID_USO_VEICULO DESC 
                LIMIT 1
            ");
            $stmt->bind_param("i", $movimentacao['veiculo']);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return [
                    'idUso'           => $row['ID_USO_VEICULO'],
                    'usuario'         => $row['FK_USUARIO'],
                    'veiculo'         => $row['FK_VEICULO'],
                    'dataInicio'      => $row['DATA_HORA'],
                    'dataFinalizacao' => $row['DATA_HORA_DEVOLUCAO'],
                    'status'          => $row['STATUS_USO']
                ];
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarUltimaMovimentacao", $this->idUsuarioSessao);
            return [];
        }
    }


    /**
     * Verifica se existe uma movimentação em aberto para o veículo
     * (retirada sem devolução registrada).
     * Cobre tanto NULL quanto string vazia ''.
     */
    function buscarMovimentacaoAberta($fkVeiculo)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_USO_VEICULO 
                FROM {$this->tbl_lista_uso} 
                WHERE FK_VEICULO = ? 
                AND (DATA_HORA_DEVOLUCAO IS NULL OR DATA_HORA_DEVOLUCAO = '')
                ORDER BY ID_USO_VEICULO DESC 
                LIMIT 1
            ");
            $stmt->bind_param("i", $fkVeiculo);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows > 0;
        } catch (Exception $e) {
            Util::inserirErro($e, "buscarMovimentacaoAberta", $this->idUsuarioSessao);
            return false;
        }
    }


    /**
     * Registra a devolução do veículo:
     * busca o registro em aberto e preenche DATA_HORA_DEVOLUCAO + STATUS_USO = 1.
     * Cobre tanto NULL quanto string vazia '' no campo DATA_HORA_DEVOLUCAO.
     */
  function salvarDevolucao($fkVeiculo)
{
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $chamadas = array_map(fn($t) => ($t['class'] ?? '') . '::' . ($t['function'] ?? ''), $trace);
    file_put_contents('C:/xampp/htdocs/SYSCHECK/debug_devolucao.txt', date('H:i:s') . " | FK_VEICULO=$fkVeiculo | " . implode(' -> ', $chamadas) . "\n", FILE_APPEND);

    try {
    // ... resto do código
            $stmt = $this->conexao->prepare("
                SELECT ID_USO_VEICULO 
                FROM {$this->tbl_lista_uso} 
                WHERE FK_VEICULO = ? 
                AND (DATA_HORA_DEVOLUCAO IS NULL OR DATA_HORA_DEVOLUCAO = '')
                ORDER BY ID_USO_VEICULO DESC 
                LIMIT 1
            ");
            $stmt->bind_param("i", $fkVeiculo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return 0; // Nenhuma retirada em aberto para devolver
            }

            $row = $result->fetch_assoc();
            $idUsoVeiculo = $row['ID_USO_VEICULO'];

            // ✅ Formato correto para o banco (VARCHAR)
            $dataHora = (new DateTime())->format('d/m/Y H:i:s');

            $stmt = $this->conexao->prepare("
                UPDATE {$this->tbl_lista_uso} 
                SET DATA_HORA_DEVOLUCAO = ?, STATUS_USO = 1 
                WHERE ID_USO_VEICULO = ?
            ");
            $stmt->bind_param("si", $dataHora, $idUsoVeiculo);
            $stmt->execute();

            return $stmt->affected_rows;
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarDevolucao", $this->idUsuarioSessao);
            return -1;
        }
    }


    /**
     * Registra a retirada do veículo:
     * insere novo registro com DATA_HORA e STATUS_USO = 2 (Ocupado).
     */
    function salvarMovimentacao($movimentacao)
    {
        try {
            // ✅ Formato correto para o banco (VARCHAR)
            $dataHora = (new DateTime())->format('d/m/Y H:i:s');

            $stmt = $this->conexao->prepare("
                INSERT INTO {$this->tbl_lista_uso} 
                (FK_USUARIO, FK_VEICULO, DATA_HORA, STATUS_USO) 
                VALUES (?, ?, ?, 2)
            ");
            $stmt->bind_param("iis", $movimentacao['usuario'], $movimentacao['veiculo'], $dataHora);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return 0;
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarMovimentacao", $this->idUsuarioSessao);
            return -1;
        }
    }


    function buscarUsuarioUltimaMovimentacao($fkVeiculo)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT u.NOME, l.FK_USUARIO
                FROM {$this->tbl_lista_uso} l
                INNER JOIN " . TBL_USUARIOS . " u ON u.ID_USUARIO = l.FK_USUARIO
                WHERE l.FK_VEICULO = ?
                ORDER BY l.ID_USO_VEICULO DESC
                LIMIT 1
            ");
            $stmt->bind_param("i", $fkVeiculo);
            $stmt->execute();

            $result = $stmt->get_result();
            return $result->fetch_assoc() ?: null;
        } catch (Exception $e) {
            Util::inserirErro($e, "buscarUsuarioUltimaMovimentacao", $this->idUsuarioSessao);
            return null;
        }
    }
}
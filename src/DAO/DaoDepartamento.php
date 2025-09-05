<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Departamento;
use Util\Util;

class DaoDepartamento
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_departamento = TBL_DEPARTAMENTOS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirDepartamento(Departamento $departamento)
    {
        $descricaoDepartamento = $departamento->getDescricaoDepartamento();
        $statusDepartamento = $departamento->getStatusDepartamento();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_departamento} (DESCRICAO_DEPARTAMENTO, STATUS_DEPARTAMENTO) VALUES (?,?)");
            $stmt->bind_param("si", strtoupper($descricaoDepartamento), $statusDepartamento);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirDepartamento", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarDepartamento($idDepartamento)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_DEPARTAMENTO, DESCRICAO_DEPARTAMENTO, STATUS_DEPARTAMENTO FROM {$this->tbl_departamento} WHERE ID_DEPARTAMENTO = ?");
            $stmt->bind_param("i", $idDepartamento);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Departamento($row['ID_DEPARTAMENTO'], $row['DESCRICAO_DEPARTAMENTO'], $row['STATUS_DEPARTAMENTO']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarDepartamento", $this->idUsuarioSessao);
            return null;
        }
    }

    function alterarDepartamento(Departamento $departamento)
    {
        $idDepartamento = $departamento->getIdDepartamento();
        $descricao = $departamento->getDescricaoDepartamento();
        $status = $departamento->getStatusDepartamento();

        try {
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_departamento} SET DESCRICAO_DEPARTAMENTO = ?, STATUS_DEPARTAMENTO = ? WHERE ID_DEPARTAMENTO = ?");
            $stmt->bind_param("sii", $descricao, $status, $idDepartamento);

            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarDepartamento", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listarDepartamentos()
    {
        $listaDepartamentos = [];
        try {
            $stmt = $this->conexao->prepare("SELECT ID_DEPARTAMENTO, DESCRICAO_DEPARTAMENTO, STATUS_DEPARTAMENTO FROM {$this->tbl_departamento}");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $departamento = new Departamento($row['ID_DEPARTAMENTO'], $row['DESCRICAO_DEPARTAMENTO'], $row['STATUS_DEPARTAMENTO']);
                    $listaDepartamentos[] = $departamento;
                }
            }

            return $listaDepartamentos;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarDepartamentos", $this->idUsuarioSessao);
            return null;
        }
    }
}

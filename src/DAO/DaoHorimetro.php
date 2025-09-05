<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Exception;
use Util\Util;

class DaoHorimetro
{
    private \mysqli $conexao;
    private int $idUsuarioSessao;
    private string $tbl_horimetro = TBL_HORIMETRO;
    private string $v_checklist_horimetro = V_CHECKLISTS_HORIMETRO;

    public function __construct(\mysqli $conexao, int $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    public function salvarHorimetro(int $fkChecklist, int $fkEmpilhadeira, float $horimetro): int
    {
        try {
            $stmt = $this->conexao->prepare(
                "INSERT INTO {$this->tbl_horimetro} (FK_CHECKLIST, FK_EQUIPAMENTO, HORIMETRO) VALUES (?,?,?)"
            );
            $stmt->bind_param("iid", $fkChecklist, $fkEmpilhadeira, $horimetro);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }
            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarHorimetro", $this->idUsuarioSessao);
            return -2;
        }
    }

    public function selecionarHorimetro(int $fkChecklist): array
    {
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_HORIMETRO, FK_CHECKLIST, FK_EQUIPAMENTO, HORIMETRO 
                 FROM {$this->tbl_horimetro} 
                 WHERE FK_CHECKLIST = ?"
            );
            $stmt->bind_param("i", $fkChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                return [
                    'ID_HORIMETRO' => $row['ID_HORIMETRO'],
                    'FK_CHECKLIST' => $row['FK_CHECKLIST'],
                    'FK_EMPILHADEIRA' => $row['FK_EQUIPAMENTO'],
                    'HORIMETRO' => $row['HORIMETRO']
                ];
            }
            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarHorimetro", $this->idUsuarioSessao);
            return [];
        }
    }

    public function recuperarHorimetros(int $fkChecklist): array
    {
        $listaHorimetros = [];
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_HORIMETRO, FK_CHECKLIST, FK_EQUIPAMENTO, HORIMETRO 
                 FROM {$this->tbl_horimetro} 
                 WHERE FK_CHECKLIST = ? 
                 ORDER BY ID_HORIMETRO ASC"
            );
            $stmt->bind_param("i", $fkChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $listaHorimetros[] = [
                    'idHorimetro' => $row['ID_HORIMETRO'],
                    'fkChecklist' => $row['FK_CHECKLIST'],
                    'fkEmpilhadeira' => $row['FK_EQUIPAMENTO'],
                    'horimetro' => $row['HORIMETRO']
                ];
            }
        } catch (Exception $e) {
            Util::inserirErro($e, "recuperarHorimetros", $this->idUsuarioSessao);
        }
        return $listaHorimetros;
    }

    public function recuperarHorimetroPorEquipamento(int $fkEmpilhadeira): array
    {
        $listaHorimetros = [];
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_HORIMETRO, FK_CHECKLIST, FK_EQUIPAMENTO, HORIMETRO 
                 FROM {$this->tbl_horimetro} 
                 WHERE FK_CHECKLIST > 600 AND FK_EQUIPAMENTO = ? 
                 ORDER BY ID_HORIMETRO ASC"
            );
            $stmt->bind_param("i", $fkEmpilhadeira);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $listaHorimetros[] = [
                    'idHorimetro' => $row['ID_HORIMETRO'],
                    'fkChecklist' => $row['FK_CHECKLIST'],
                    'fkEmpilhadeira' => $row['FK_EQUIPAMENTO'],
                    'horimetro' => $row['HORIMETRO']
                ];
            }
        } catch (Exception $e) {
            Util::inserirErro($e, "recuperarHorimetroPorEquipamento", $this->idUsuarioSessao);
        }
        return $listaHorimetros;
    }

    public function salvarHorimetroFinal(int $fkChecklist, int $fkEmpilhadeira, float $horimetroFinal): bool
    {
        try {
            $stmt = $this->conexao->prepare(
                "UPDATE {$this->tbl_horimetro} 
                 SET HORIMETRO_FINAL = ?, STATUS_USO = 0 
                 WHERE FK_EQUIPAMENTO = ? AND STATUS_USO = 1"
            );
            $stmt->bind_param("di", $horimetroFinal, $fkEmpilhadeira);
            return $stmt->execute();
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarHorimetroFinal", $this->idUsuarioSessao);
            return false;
        }
    }

    public function listaChecklistsEmpilhadeiras(int $fkChecklist): array
    {
        $listaHorimetros = [];
        try {
            $stmt = $this->conexao->prepare(
                "SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, DATA_FIM, 
                        STATUS_CHECKLIST, HORIMETRO_INICIAL, HORIMETRO_FINAL 
                 FROM {$this->v_checklist_horimetro} 
                 WHERE ID_CHECKLIST >= ?"
            );
            $stmt->bind_param("i", $fkChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $listaHorimetros[] = [
                    'idChecklist' => $row['ID_CHECKLIST'],
                    'usuario' => $row['FK_USUARIO'],
                    'tipo' => $row['FK_TIPO'],
                    'empilhadeira' => $row['FK_OBJETO'],
                    'dataInicio' => $row['DATA_INICIO'],
                    'dataFim' => $row['DATA_FIM'],
                    'status' => $row['STATUS_CHECKLIST'],
                    'horimetroInicial' => $row['HORIMETRO_INICIAL'],
                    'horimetroFinal' => $row['HORIMETRO_FINAL']
                ];
            }
        } catch (Exception $e) {
            Util::inserirErro($e, "listaChecklistsEmpilhadeiras", $this->idUsuarioSessao);
        }
        return $listaHorimetros;
    }
}

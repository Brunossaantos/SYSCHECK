<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Checklist;
use Exception;
use Util\Util;
use service\PermissaoService;

class DaoChecklist
{
    private \mysqli $conexao;
    private int $idUsuarioSessao;
    private int $idEmpresaSessao;
    private string $tbl_checklists = TBL_CHECKLISTS;
    private string $view_checklists = V_CHECKLIS_VISAO_GERAL;
    private string $view_checklists_horimetros = V_CHECKLISTS_HORIMETRO;

    public function __construct($conexao, int $idUsuarioSessao, int $idEmpresaSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
        $this->idEmpresaSessao = $idEmpresaSessao;
    }

    // =========================
    // CRUD BÁSICO
    // =========================

    public function iniciarCheckList(Checklist $checklist): int
    {
        try {
            $stmt = $this->conexao->prepare("
                INSERT INTO {$this->tbl_checklists} 
                (FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, STATUS_CHECKLIST)
                VALUES (?, ?, ?, ?, ?)
            ");

            $fkUsuario       = $checklist->getFkUsuario();
            $fkTipo          = $checklist->getFkTipo();
            $fkObjeto        = $checklist->getFkObjeto();
            $dataInicio      = $checklist->getDataInicio();
            $statusChecklist = $checklist->getStatusChecklist();

            $stmt->bind_param(
                "iiisi",
                $fkUsuario,
                $fkTipo,
                $fkObjeto,
                $dataInicio,
                $statusChecklist
            );

            return $stmt->execute() ? $stmt->insert_id : -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "iniciarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    public function selecionarChecklist(int $idChecklist): ?Checklist
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO,
                       DATA_INICIO, DATA_FIM, STATUS_CHECKLIST
                FROM {$this->tbl_checklists}
                WHERE ID_CHECKLIST = ?
            ");

            $stmt->bind_param("i", $idChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) return null;

            $row = $result->fetch_assoc();

            return new Checklist(
                $row['ID_CHECKLIST'],
                $row['FK_USUARIO'],
                $row['FK_TIPO'],
                $row['FK_OBJETO'],
                $row['DATA_INICIO'],
                $row['DATA_FIM'],
                $row['STATUS_CHECKLIST']
            );
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarChecklist", $this->idUsuarioSessao);
            return null;
        }
    }

    public function atualizarChecklist(Checklist $checklist): int
    {
        try {
            $stmt = $this->conexao->prepare("
                UPDATE {$this->tbl_checklists}
                SET DATA_FIM = ?, STATUS_CHECKLIST = ?
                WHERE ID_CHECKLIST = ?
            ");

            $dataFim    = $checklist->getDataFim();
            $status     = $checklist->getStatusChecklist();
            $idChecklist = $checklist->getIdChecklist();

            $stmt->bind_param(
                "sii",
                $dataFim,
                $status,
                $idChecklist
            );

            return $stmt->execute() ? $stmt->affected_rows : -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "atualizarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    // =========================
    // HORÍMETRO
    // =========================

    public function recuperarHorimetrosPorChecklist(int $fkUsuario): array
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO, 
                       DATA_INICIO, DATA_FIM, STATUS_CHECKLIST,
                       HORIMETRO_INICIAL, HORIMETRO_FINAL
                FROM {$this->view_checklists_horimetros}
                WHERE FK_USUARIO = ?
                ORDER BY ID_CHECKLIST DESC
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            $lista = [];
            while ($row = $result->fetch_assoc()) {
                $lista[] = [
                    'idChecklist'      => $row['ID_CHECKLIST'],
                    'usuario'          => $row['FK_USUARIO'],
                    'tipo'             => $row['FK_TIPO'],
                    'empilhadeira'     => $row['FK_OBJETO'],
                    'dataInicio'       => $row['DATA_INICIO'],
                    'dataFim'          => $row['DATA_FIM'],
                    'status'           => $row['STATUS_CHECKLIST'],
                    'horimetroInicial' => $row['HORIMETRO_INICIAL'],
                    'horimetroFinal'   => $row['HORIMETRO_FINAL'],
                ];
            }

            return $lista;
        } catch (Exception $e) {
            Util::inserirErro($e, "recuperarHorimetrosPorChecklist", $this->idUsuarioSessao);
            return [];
        }
    }

    // =========================
    // PENDÊNCIA CHECKLIST
    // =========================

    public function verificarChecklistPorUsuario(int $fkUsuario): ?Checklist
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT *
                FROM {$this->tbl_checklists}
                WHERE FK_USUARIO = ?
                  AND STATUS_CHECKLIST = 1
                ORDER BY ID_CHECKLIST DESC
                LIMIT 1
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) return null;

            $row = $result->fetch_assoc();

            return new Checklist(
                $row['ID_CHECKLIST'],
                $row['FK_USUARIO'],
                $row['FK_TIPO'],
                $row['FK_OBJETO'],
                $row['DATA_INICIO'],
                $row['DATA_FIM'],
                $row['STATUS_CHECKLIST']
            );
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarChecklistPorUsuario", $this->idUsuarioSessao);
            return null;
        }
    }

    public function verificarChecklistPendente(int $fkUsuario): int
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT COUNT(ID_CHECKLIST) AS QTD
                FROM {$this->tbl_checklists}
                WHERE FK_USUARIO = ?
                  AND STATUS_CHECKLIST = 1
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return (int)$row['QTD'];
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarChecklistPendente", $this->idUsuarioSessao);
            return 0;
        }
    }

    // =========================
    // CONTROLE VEICULAR
    // =========================

    public function veicularAtivo(): bool
    {
        $stmt = $this->conexao->prepare("
            SELECT checklist_veicular 
            FROM tbl_usuarios 
            WHERE ID_USUARIO = ? 
            LIMIT 1
        ");

        if (!$stmt) return false;

        $stmt->bind_param("i", $this->idUsuarioSessao);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) return false;

        $dados = $result->fetch_assoc();
        return (int)$dados['checklist_veicular'] === 1;
    }

    // =========================
    // FILTRAGEM E LISTAGEM
    // =========================

    public function filtrarChecklists(array $filtros): array
    {
        try {
            $sql = "SELECT NUMERO_CHECKLIST, USUARIO, TIPO, OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST
                    FROM {$this->view_checklists} WHERE 1=1";

            $params = [];
            $types = "";

            if (!empty($filtros['numero'])) {
                $sql .= " AND NUMERO_CHECKLIST = ?";
                $params[] = $filtros['numero'];
                $types .= "i";
            }

            if (!empty($filtros['data_inicio'])) {
                $dataFiltro = \DateTime::createFromFormat('Y-m-d', $filtros['data_inicio']);
                if ($dataFiltro) {
                    $sql .= " AND DATA_INICIO LIKE ?";
                    $params[] = $dataFiltro->format('d/m/y') . "%";
                    $types .= "s";
                }
            }

            foreach (['tipo', 'objeto', 'usuario'] as $campo) {
                if (!empty($filtros[$campo])) {
                    $sql .= " AND " . strtoupper($campo) . " = ?";
                    $params[] = $filtros[$campo];
                    $types .= "s";
                }
            }

            if (!empty($filtros['status']) && $filtros['status'] != 0) {
                $sql .= " AND STATUS_CHECKLIST = ?";
                $params[] = $filtros['status'];
                $types .= "i";
            }

            $sql .= " ORDER BY NUMERO_CHECKLIST DESC";

            $stmt = $this->conexao->prepare($sql);

            if (!empty($params)) {
                $bindNames = [$types];
                foreach ($params as $k => $param) {
                    $bindNames[] = &$params[$k];
                }
                call_user_func_array([$stmt, 'bind_param'], $bindNames);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $checklists = [];
            while ($row = $result->fetch_assoc()) {
                $checklists[] = new Checklist(
                    $row['NUMERO_CHECKLIST'],
                    $row['USUARIO'],
                    $row['TIPO'],
                    $row['OBJETO'],
                    $row['DATA_INICIO'],
                    $row['DATA_FIM'],
                    $row['STATUS_CHECKLIST']
                );
            }

            return $checklists;
        } catch (Exception $e) {
            Util::inserirErro($e, "filtrarChecklists", $this->idUsuarioSessao);
            return [];
        }
    }

    public function listarChecklists(): array
    {
        try {
            $permissaoService = new PermissaoService($this->conexao, $this->idUsuarioSessao, $this->idEmpresaSessao);
            $usuariosPermitidos = $permissaoService->getUsuariosPermitidos();

            if (empty($usuariosPermitidos)) return [];

            $placeholders = implode(',', array_fill(0, count($usuariosPermitidos), '?'));

            $sql = "
                SELECT NUMERO_CHECKLIST, USUARIO, TIPO, OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST
                FROM {$this->view_checklists}
                WHERE ID_USUARIO IN ($placeholders) AND ID_EMPRESA = ?
                ORDER BY NUMERO_CHECKLIST DESC
            ";

            $stmt = $this->conexao->prepare($sql);

            $usuariosPermitidos[] = $this->idEmpresaSessao;
            $types = str_repeat('i', count($usuariosPermitidos));

            $stmt->bind_param($types, ...$usuariosPermitidos);
            $stmt->execute();
            $result = $stmt->get_result();

            $listaChecklists = [];
            while ($row = $result->fetch_assoc()) {
                $listaChecklists[] = new Checklist(
                    $row['NUMERO_CHECKLIST'],
                    $row['USUARIO'],
                    $row['TIPO'],
                    $row['OBJETO'],
                    $row['DATA_INICIO'],
                    $row['DATA_FIM'],
                    $row['STATUS_CHECKLIST']
                );
            }

            return $listaChecklists;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarChecklists", $this->idUsuarioSessao);
            return [];
        }
    }

    public function buscarHorimetroPendente(int $fkUsuario): ?array
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST,
                       HORIMETRO_INICIAL, HORIMETRO_FINAL
                FROM {$this->view_checklists_horimetros}
                WHERE FK_USUARIO = ?
                  AND DATA_FIM IS NOT NULL
                  AND (HORIMETRO_FINAL IS NULL OR HORIMETRO_FINAL = '')
                ORDER BY ID_CHECKLIST DESC
                LIMIT 1
            ");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows ? $result->fetch_assoc() : null;
        } catch (Exception $e) {
            Util::inserirErro($e, "buscarHorimetroPendente", $this->idUsuarioSessao);
            return null;
        }
    }
}

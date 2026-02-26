<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Checklist;
use Exception;
use Util\Util;

class DaoChecklist
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_checklists = TBL_CHECKLISTS;
    private $view_checklists = V_CHECKLIS_VISAO_GERAL;
    private $view_checklists_horimetros = V_CHECKLISTS_HORIMETRO;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    /* =========================================================
       CHECKLIST CRUD
       ========================================================= */

    function iniciarCheckList(Checklist $checklist)
    {
        try {

            $stmt = $this->conexao->prepare("
                INSERT INTO {$this->tbl_checklists}
                (FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, STATUS_CHECKLIST)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "iiisi",
                $checklist->getFkUsuario(),
                $checklist->getFkTipo(),
                $checklist->getFkObjeto(),
                $checklist->getDataInicio(),
                $checklist->getStatusChecklist()
            );

            return $stmt->execute() ? $stmt->insert_id : -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "iniciarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarChecklist($idChecklist)
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

            if ($result->num_rows === 0) {
                return null;
            }

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

    function atualizarChecklist(Checklist $checklist)
    {
        try {

            $stmt = $this->conexao->prepare("
                UPDATE {$this->tbl_checklists}
                SET DATA_FIM = ?, STATUS_CHECKLIST = ?
                WHERE ID_CHECKLIST = ?
            ");

            $stmt->bind_param(
                "sii",
                $checklist->getDataFim(),
                $checklist->getStatusChecklist(),
                $checklist->getIdChecklist()
            );

            return $stmt->execute() ? $stmt->affected_rows : -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "atualizarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    /* =========================================================
       HORÍMETRO
       ========================================================= */

    function recuperarHorimetrosPorChecklist($fkUsuario)
    {
        try {

            $stmt = $this->conexao->prepare("
                SELECT
                    ID_CHECKLIST,
                    FK_USUARIO,
                    FK_TIPO,
                    FK_OBJETO,
                    DATA_INICIO,
                    DATA_FIM,
                    STATUS_CHECKLIST,
                    HORIMETRO_INICIAL,
                    HORIMETRO_FINAL
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
                    'horimetroFinal'   => $row['HORIMETRO_FINAL'], // <-- ESSENCIAL
                ];
            }

            return $lista;
        } catch (Exception $e) {
            Util::inserirErro($e, "recuperarHorimetrosPorChecklist", $this->idUsuarioSessao);
            return [];
        }
    }

    /* =========================================================
       PENDÊNCIA CHECKLIST
       ========================================================= */

    function verificarChecklistPorUsuario($fkUsuario)
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

            if ($result->num_rows === 0) {
                return null;
            }

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

    function verificarChecklistPendente($fkUsuario)
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

    /* =========================================================
       CONTROLE VEICULAR
       ========================================================= */

    public function veicularAtivo(): bool
    {
        $sql = "SELECT checklist_veicular 
            FROM tbl_usuarios 
            WHERE ID_USUARIO = ? 
            LIMIT 1";

        $stmt = $this->conexao->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $this->idUsuarioSessao);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false;
        }

        $dados = $result->fetch_assoc();

        return (int)$dados['checklist_veicular'] === 1;
    }

    public function listarChecklists()
    {
        try {

            $sql = "
            SELECT *
            FROM {$this->view_checklists}
            ORDER BY ID_CHECKLIST DESC
        ";

            $result = $this->conexao->query($sql);

            $lista = [];

            while ($row = $result->fetch_assoc()) {
                $lista[] = $row;
            }

            return $lista;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarChecklists", $this->idUsuarioSessao);
            return [];
        }
    }

    function listaChecklists()
    {
        try {
            $listaChecklists = [];
            $stmt = $this->conexao->prepare("SELECT * FROM {$this->view_checklists} ORDER BY NUMERO_CHECKLIST DESC");
            $stmt->execute();
            $result = $stmt->get_result();

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
            return [];
        }
    }

    function filtrarChecklists($filtros)
    {
        try {
            $sql = "SELECT NUMERO_CHECKLIST, USUARIO, TIPO, OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST
                    FROM {$this->view_checklists}
                    WHERE 1=1";

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

            if (!empty($filtros['tipo'])) {
                $sql .= " AND TIPO = ?";
                $params[] = $filtros['tipo'];
                $types .= "s";
            }

            if (!empty($filtros['objeto'])) {
                $sql .= " AND OBJETO = ?";
                $params[] = $filtros['objeto'];
                $types .= "s";
            }

            if (!empty($filtros['usuario'])) {
                $sql .= " AND USUARIO = ?";
                $params[] = $filtros['usuario'];
                $types .= "s";
            }

            if (!empty($filtros['status']) && $filtros['status'] != 0) {
                $sql .= " AND STATUS_CHECKLIST = ?";
                $params[] = $filtros['status'];
                $types .= "i";
            }

            $sql .= " ORDER BY NUMERO_CHECKLIST DESC";

            $stmt = $this->conexao->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
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
            return [];
        }
    }

    public function listarChecklistVeicular()
    {
        try {

            $stmt = $this->conexao->prepare("
            SELECT *
            FROM {$this->view_checklists}
            WHERE FK_TIPO = 1
            ORDER BY ID_CHECKLIST DESC
        ");

            $stmt->execute();
            $result = $stmt->get_result();

            $lista = [];

            while ($row = $result->fetch_assoc()) {
                $lista[] = $row;
            }

            return $lista;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarChecklistVeicular", $this->idUsuarioSessao);
            return [];
        }
    }

    public function buscarHorimetroPendente($fkUsuario)
    {
        try {

            $stmt = $this->conexao->prepare("
            SELECT
                ID_CHECKLIST,
                FK_USUARIO,
                FK_TIPO,
                FK_OBJETO,
                DATA_INICIO,
                DATA_FIM,
                STATUS_CHECKLIST,
                HORIMETRO_INICIAL,
                HORIMETRO_FINAL
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

            if ($result->num_rows === 0) {
                return null;
            }

            return $result->fetch_assoc();
        } catch (Exception $e) {
            Util::inserirErro($e, "buscarHorimetroPendente", $this->idUsuarioSessao);
            return null;
        }
    }
}

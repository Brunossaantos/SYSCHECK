<?php

namespace Dao;

require_once __DIR__ . '/../database/Conexao.php';

use mysqli;

class DaoRelatorio
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new \Database\Conexao())->conectar();
    }

    /** Retorna tipos de checklist ativos */
    public function getTiposAtivos(): array
    {
        $sql = "SELECT ID_TIPO_CHECKLIST, DESCRICAO_TIPO_CHECKLIST 
                FROM tbl_tipos_checklist 
                WHERE STATUS_TIPO_CHECKLIST = 1";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** Retorna equipamentos ativos */
    public function getEquipamentosAtivos(): array
    {
        $sql = "SELECT ID_OBJETO, DESCRICAO_OBJETO FROM tbl_objetos WHERE STATUS_OBJETO = 1";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Retorna dados da visão geral
     */
    public function getRelatorioVisaoGeral($idTipo = null, $idEquip = null, $data = null): array
    {
        $sql = "SELECT * FROM v_checklist_visao_geral WHERE 1=1";

        if (!empty($idTipo)) {
            $sql .= " AND TIPO = '" . $this->conn->real_escape_string($idTipo) . "'";
        }

        if (!empty($idEquip)) {
            $sql .= " AND OBJETO = '" . $this->conn->real_escape_string($idEquip) . "'";
        }

        if (!empty($data)) {
            $sql .= " AND DATE(STR_TO_DATE(DATA_INICIO, '%d/%m/%Y %H:%i:%s')) = '" . $this->conn->real_escape_string($data) . "'";
        }

        $result = $this->conn->query($sql);
        $relatorios = [];

        if ($result) {
            while ($row = $result->fetch_object()) {
                $row->DATA_INICIO_FORMAT = $this->formatDate($row->DATA_INICIO);
                $row->DATA_FIM_FORMAT = $this->formatDate($row->DATA_FIM);
                $relatorios[] = $row;
            }
        }

        return $relatorios;
    }

    /** Formata datas para dd/mm/yyyy hh:mm:ss */
    private function formatDate(?string $dateStr): string
    {
        if (empty($dateStr) || $dateStr === 'NULL') return '-';
        $dt = \DateTime::createFromFormat('d/m/y H:i:s', $dateStr)
            ?: \DateTime::createFromFormat('d/m/Y H:i:s', $dateStr);

        return $dt ? $dt->format('d/m/Y H:i:s') : '-';
    }

    /**
     * Retorna todos os itens reprovados (ACAO = 2), com filtro opcional por equipamento e datas
     */
    public function listarItensReprovados($idEquip = null, $dataInicio = null, $dataFim = null): array
    {
        $sql = "SELECT er.ID_ETAPA_REALIZADA, er.FK_CHECKLIST, er.FK_ETAPA, er.NUMERO_ETAPA, er.ACAO, er.OBSERVACAO,
                       c.DATA_INICIO, o.DESCRICAO_OBJETO
                FROM tbl_etapas_realizadas er
                JOIN tbl_checklists c ON c.ID_CHECKLIST = er.FK_CHECKLIST
                LEFT JOIN tbl_objetos o ON o.ID_OBJETO = c.FK_OBJETO
                WHERE er.ACAO = 2";

        if (!empty($idEquip)) {
            $sql .= " AND c.FK_OBJETO = " . (int)$idEquip;
        }

        if (!empty($dataInicio)) {
            $sql .= " AND STR_TO_DATE(c.DATA_INICIO, '%d/%m/%y %H:%i:%s') >= '" . $this->conn->real_escape_string($dataInicio) . "'";
        }

        if (!empty($dataFim)) {
            $sql .= " AND STR_TO_DATE(c.DATA_INICIO, '%d/%m/%y %H:%i:%s') <= '" . $this->conn->real_escape_string($dataFim) . "'";
        }

        // Ordenação: Checklist crescente, depois Número da Etapa crescente
        $sql .= " ORDER BY er.FK_CHECKLIST ASC, er.NUMERO_ETAPA ASC";

        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}

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
            $dataSql = $this->conn->real_escape_string($data);
        }

        if (!empty($data)) {
            $dataSql = $this->conn->real_escape_string($data);
            $sql .= " AND DATE(STR_TO_DATE(DATA_INICIO, '%d/%m/%Y %H:%i:%s')) = '" . $dataSql . "'";
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
    public function gerarRelatorioItensReprovados($idEquip = null, $dataInicio = null, $dataFim = null): array
    {
        $sql = "
        SELECT 
            e.FK_CHECKLIST,
            e.NUMERO_ETAPA,
            o.DESCRICAO_OBJETO AS EQUIPAMENTO,
            c.STATUS_CHECKLIST,
            c.DATA_INICIO,
            c.DATA_FIM
        FROM tbl_etapas_realizadas e
        INNER JOIN tbl_checklists c ON c.ID_CHECKLIST = e.FK_CHECKLIST
        INNER JOIN tbl_objetos o ON o.ID_OBJETO = c.FK_OBJETO
        WHERE e.ACAO = 2
    ";

        // Filtro por equipamento
        if (!empty($idEquip)) {
            $idEquip = $this->conn->real_escape_string($idEquip);
            $sql .= " AND o.DESCRICAO_OBJETO = '{$idEquip}'";
        }

        // Filtro por intervalo de datas (convertendo o varchar para datetime)
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicio = $this->conn->real_escape_string($dataInicio);
            $dataFim = $this->conn->real_escape_string($dataFim);

            $sql .= "
            AND STR_TO_DATE(c.DATA_INICIO, '%d/%m/%Y %H:%i:%s') BETWEEN '{$dataInicio} 00:00:00' AND '{$dataFim} 23:59:59'
        ";
        } elseif (!empty($dataInicio)) {
            $dataInicio = $this->conn->real_escape_string($dataInicio);
            $sql .= "
            AND DATE(STR_TO_DATE(c.DATA_INICIO, '%d/%m/%Y %H:%i:%s')) = '{$dataInicio}'
        ";
        }

        $sql .= " ORDER BY e.FK_CHECKLIST, e.NUMERO_ETAPA";

        $result = $this->conn->query($sql);
        $relatorios = [];

        if ($result) {
            while ($row = $result->fetch_object()) {
                $relatorios[] = $row;
            }
        }

        return $relatorios;
    }
}

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
      public function gerarRelatorioUso($idEquip = null, $dataInicio = null, $dataFim = null): array
    {
        $filtroEquip = '';
        if (!empty($idEquip)) {
            $idEquip     = $this->conn->real_escape_string($idEquip);
            $filtroEquip = "AND v.FK_OBJETO = '{$idEquip}'";
        }
 
        $filtroDatas = '';
        if (!empty($dataInicio) && !empty($dataFim)) {
            $dataInicio  = $this->conn->real_escape_string($dataInicio);
            $dataFim     = $this->conn->real_escape_string($dataFim);
            $filtroDatas = "AND STR_TO_DATE(v.DATA_INICIO, '%d/%m/%Y %H:%i:%s') BETWEEN '{$dataInicio} 00:00:00' AND '{$dataFim} 23:59:59'";
        }
 
        // Exclui checklists do tipo VEICULAR (1) e CHECKLIST TI (6)
        $filtroTipo = "AND v.FK_TIPO NOT IN (1, 6)";
 
        // --------------------------------------------------
        // 1. Histórico detalhado — inclui "em uso" (HORIMETRO_FINAL NULL)
        // --------------------------------------------------
        $sqlDetalhado = "
            SELECT
                v.DATA_INICIO,
                o.DESCRICAO_OBJETO                              AS EQUIPAMENTO,
                u.NOME                                         AS OPERADOR,
                v.HORIMETRO_INICIAL,
                v.HORIMETRO_FINAL,
                CASE
                    WHEN v.HORIMETRO_FINAL IS NULL THEN NULL
                    ELSE (v.HORIMETRO_FINAL - v.HORIMETRO_INICIAL)
                END                                            AS HORAS_USO
            FROM v_checklist_horimetro v
            INNER JOIN tbl_objetos  o ON o.ID_OBJETO  = v.FK_OBJETO
            INNER JOIN tbl_usuarios u ON u.ID_USUARIO = v.FK_USUARIO
            WHERE 1=1
            {$filtroTipo}
            {$filtroDatas}
            {$filtroEquip}
            ORDER BY STR_TO_DATE(v.DATA_INICIO, '%d/%m/%Y %H:%i:%s') ASC
        ";
 
        // --------------------------------------------------
        // 2. Total por equipamento — apenas finalizados
        // --------------------------------------------------
        $sqlResumo = "
            SELECT
                o.DESCRICAO_OBJETO                              AS EQUIPAMENTO,
                COUNT(*)                                        AS TOTAL_USOS,
                SUM(v.HORIMETRO_FINAL - v.HORIMETRO_INICIAL)   AS TOTAL_HORAS
            FROM v_checklist_horimetro v
            INNER JOIN tbl_objetos o ON o.ID_OBJETO = v.FK_OBJETO
            WHERE v.HORIMETRO_FINAL IS NOT NULL
            {$filtroTipo}
            {$filtroDatas}
            {$filtroEquip}
            GROUP BY v.FK_OBJETO, o.DESCRICAO_OBJETO
            ORDER BY TOTAL_HORAS DESC
        ";
 
        // --------------------------------------------------
        // 3. Resumo por dia — apenas finalizados
        // --------------------------------------------------
        $sqlPorDia = "
            SELECT
                DATE(STR_TO_DATE(v.DATA_INICIO, '%d/%m/%Y %H:%i:%s'))   AS DATA,
                COUNT(DISTINCT v.FK_OBJETO)                              AS EQUIPAMENTOS_EM_USO,
                COUNT(*)                                                 AS TOTAL_USOS,
                SUM(v.HORIMETRO_FINAL - v.HORIMETRO_INICIAL)            AS TOTAL_HORAS_DIA
            FROM v_checklist_horimetro v
            WHERE v.HORIMETRO_FINAL IS NOT NULL
            {$filtroTipo}
            {$filtroDatas}
            {$filtroEquip}
            GROUP BY DATE(STR_TO_DATE(v.DATA_INICIO, '%d/%m/%Y %H:%i:%s'))
            ORDER BY DATA ASC
        ";
 
        $detalhado = [];
        $result = $this->conn->query($sqlDetalhado);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $detalhado[] = $row;
            }
        }
 
        $resumo = [];
        $result = $this->conn->query($sqlResumo);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $resumo[] = $row;
            }
        }
 
        $porDia = [];
        $result = $this->conn->query($sqlPorDia);
        if ($result) {
            while ($row = $result->fetch_object()) {
                $porDia[] = $row;
            }
        }
 
        return [
            'detalhado' => $detalhado,
            'resumo'    => $resumo,
            'porDia'    => $porDia,
        ];
    }
}

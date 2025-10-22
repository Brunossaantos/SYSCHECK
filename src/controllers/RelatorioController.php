<?php

namespace Controller;

require_once __DIR__ . '/../dao/DaoRelatorio.php';

use Dao\DaoRelatorio;

class RelatorioController
{
    private DaoRelatorio $dao;

    public function __construct()
    {
        $this->dao = new DaoRelatorio();
    }

    /** Lista todos os tipos de checklist ativos */
    public function listarTipos(): array
    {
        return $this->dao->getTiposAtivos();
    }

    /** Lista todos os equipamentos ativos */
    public function listarTodosEquipamentos(): array
    {
        return $this->dao->getEquipamentosAtivos();
    }

    /**
     * Gera relatório da visão geral
     */
    public function gerarRelatorioVisaoGeral($idTipo = null, $idEquip = null, $data = null): array
    {
        return $this->dao->getRelatorioVisaoGeral($idTipo, $idEquip, $data);
    }

    /**
     * Lista todos os itens reprovados
     * @param int|null $idEquip
     * @param string|null $dataInicio
     * @param string|null $dataFim
     */
    public function listarItensReprovados($idEquip = null, $dataInicio = null, $dataFim = null): array
    {
        return $this->dao->listarItensReprovados($idEquip, $dataInicio, $dataFim);
    }
}

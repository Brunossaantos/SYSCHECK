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
     * @param int|null $idTipo
     * @param int|null $idEquip
     * @param string|null $data yyyy-mm-dd
     * @return array
     */
    public function gerarRelatorioVisaoGeral($idTipo = null, $idEquip = null, $data = null): array
    {
        return $this->dao->getRelatorioVisaoGeral($idTipo, $idEquip, $data);
    }

    /**
     * Gera relatório de itens reprovados
     * @param string|null $idEquip Descrição do equipamento
     * @param string|null $data Data no formato yyyy-mm-dd
     * @return array
     */
    public function gerarRelatorioItensReprovados($idEquip = null, $dataInicio = null, $dataFim = null): array
{
    return $this->dao->gerarRelatorioItensReprovados($idEquip, $dataInicio, $dataFim);

    
}


}

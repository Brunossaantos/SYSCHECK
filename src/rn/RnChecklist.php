<?php

namespace rn;

use DAO\DaoChecklist;
use models\Checklist;

class RnChecklist
{
    private DaoChecklist $dao;
    private int $idUsuario;
    private int $idEmpresa;

    private int $idPerfil;

    public function __construct(int $idUsuario, int $idEmpresa)
    {
        $conexao = (new \database\Conexao())->conectar();

        $this->idUsuario = $idUsuario;
        $this->idEmpresa = $idEmpresa;

        $this->dao = new \DAO\DaoChecklist(
            $conexao,
            $idUsuario,
            $idEmpresa
        );
    }

    // =========================
    // CRUD BÁSICO
    // =========================

    public function iniciarChecklist(Checklist $checklist): int
    {
        return method_exists($this->dao, 'iniciarCheckList')
            ? $this->dao->iniciarCheckList($checklist)
            : -1;
    }

    public function selecionarChecklist(int $idChecklist): ?Checklist
    {
        return method_exists($this->dao, 'selecionarChecklist')
            ? $this->dao->selecionarChecklist($idChecklist)
            : null;
    }

    public function atualizarChecklist(Checklist $checklist): int
    {
        return method_exists($this->dao, 'atualizarChecklist')
            ? $this->dao->atualizarChecklist($checklist)
            : -1;
    }

    public function listarChecklists(): array
    {
        return method_exists($this->dao, 'listarChecklists')
            ? $this->dao->listarChecklists()
            : [];
    }
    public function listarComFiltros(array $filtros): array
    {
        return method_exists($this->dao, 'filtrarChecklists')
            ? $this->dao->filtrarChecklists($filtros)
            : [];
    }

    // =========================
    // HORÍMETRO
    // =========================

    public function recuperarHorimetrosPorChecklist(int $fkUsuario): array
    {
        return method_exists($this->dao, 'recuperarHorimetrosPorChecklist')
            ? $this->dao->recuperarHorimetrosPorChecklist($fkUsuario)
            : [];
    }

    public function verificarChecklistPendentes(int $fkUsuario): ?Checklist
    {
        return method_exists($this->dao, 'verificarChecklistPorUsuario')
            ? $this->dao->verificarChecklistPorUsuario($fkUsuario)
            : null;
    }

    public function contarChecklistPendentes(int $fkUsuario): int
    {
        return method_exists($this->dao, 'verificarChecklistPendente')
            ? $this->dao->verificarChecklistPendente($fkUsuario)
            : 0;
    }

    public function buscarHorimetroPendente(int $fkUsuario): ?array
    {
        return method_exists($this->dao, 'buscarHorimetroPendente')
            ? $this->dao->buscarHorimetroPendente($fkUsuario)
            : null;
    }

    public function verificarSeExisteHorimetroPendente(): ?array
    {
        $lista = $this->recuperarHorimetrosPorChecklist($this->idUsuario);
        foreach ($lista as $checklist) {
            $horimetro = $checklist['horimetroFinal'] ?? null;
            if (empty($horimetro)) {
                return $checklist;
            }
        }
        return null;
    }

    // =========================
    // FILTRAGEM DE TIPOS POR PERFIL
    // =========================

    public function filtrarTiposPorPerfil(array $listaTipos, int $perfilId): array
    {
        $perfisEmpilhadeira = [1, 2, 3, 7, 8];

        return array_filter($listaTipos, function ($tipoChecklist) use ($perfilId, $perfisEmpilhadeira) {
            $idTipo = (int) $tipoChecklist->getIdTipoChecklist();
            if (in_array($perfilId, $perfisEmpilhadeira) && in_array($idTipo, [3, 4, 14])) return true;
            if ($perfilId === 1 && $idTipo === 6) return true;
            if ($idTipo === 1 && $this->veicularAtivo()) return true;
            return false;
        });
    }

    // =========================
    // VEICULAR ATIVO
    // =========================

    public function veicularAtivo(): bool
    {
        return method_exists($this->dao, 'veicularAtivo')
            ? $this->dao->veicularAtivo()
            : false;
    }

    public function listarChecklistsVeiculares()
    {
        return $this->dao->listarChecklistsVeiculares();
    }
    /**
     * Verifica se existe um checklist aberto para o usuário
     * @param int $fkUsuario
     * @return \models\Checklist|null
     */
    public function verificarChecklistPorUsuario(int $fkUsuario): ?\models\Checklist
    {
        // Repassa a busca para o DAO que já codificamos antes
        return $this->dao->verificarChecklistPorUsuario($fkUsuario);
    }
}

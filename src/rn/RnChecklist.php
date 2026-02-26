<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Checklist;
use DAO\DaoChecklist;
use database\Conexao;

class RnChecklist
{
    private $idUsuarioSessao;

    public function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    private function dao(): DaoChecklist
    {
        return new DaoChecklist(
            (new Conexao())->conectar(),
            $this->idUsuarioSessao
        );
    }

    /* =========================================================
       CRUD BÁSICO
       ========================================================= */

    public function iniciarChecklist(Checklist $checklist)
    {
        return $this->dao()->iniciarCheckList($checklist);
    }

    public function selecionarChecklist($idChecklist)
    {
        return $this->dao()->selecionarChecklist($idChecklist);
    }

    public function atualizarChecklist(Checklist $checklist)
    {
        return $this->dao()->atualizarChecklist($checklist);
    }

    public function listarChecklists()
    {
        return $this->dao()->listarChecklists();
    }

    public function listarComFiltros($filtros)
    {
        return $this->dao()->filtrarChecklists($filtros);
    }

    public function listarChecklistsVeiculares()
    {
        return $this->dao()->listarChecklistVeicular();
    }

    /* =========================================================
       VERIFICAÇÕES
       ========================================================= */

    public function verificarChecklistPendente($fkUsuario)
    {
        return $this->dao()->verificarChecklistPorUsuario($fkUsuario);
    }

    public function verificarPendencia($fkUsuario)
    {
        return $this->dao()->verificarChecklistPendente($fkUsuario);
    }

    public function recuperarHorimetrosPorChecklist($fkUsuario)
    {
        return $this->dao()->recuperarHorimetrosPorChecklist($fkUsuario);
    }

    /* =========================================================
       🔎 REGRA DEFINITIVA DE HORÍMETRO PENDENTE
       - Só verifica se existe horímetro final NULL
       - Não importa o STATUS (1, 2 ou 3)
       ========================================================= */

    public function verificarSeExisteHorimetroPendente(): ?array
{
    $lista = $this->dao()->recuperarHorimetrosPorChecklist($this->idUsuarioSessao);

    if (empty($lista)) {
        return null;
    }

    foreach ($lista as $checklist) {

        $horimetro = $checklist['horimetroFinal'] ?? null;

        if (empty($horimetro)) {
            return $checklist;
        }
    }

    return null;
}

    /* =========================================================
       🔐 PERMISSÃO POR PERFIL
       ========================================================= */

    public function filtrarTiposPorPerfil(array $listaTipos, int $perfilId): array
    {
        $perfisEmpilhadeira = [1, 2, 3, 7, 8];

        return array_filter($listaTipos, function ($tipoChecklist) use ($perfilId, $perfisEmpilhadeira) {

            $idTipo = (int) $tipoChecklist->getIdTipoChecklist();

            if (in_array($perfilId, $perfisEmpilhadeira) && in_array($idTipo, [3, 4, 14])) {
                return true;
            }

            if ($perfilId === 1 && $idTipo === 6) {
                return true;
            }

            if ($idTipo === 1 && $this->veicularAtivo()) {
                return true;
            }

            return false;
        });
    }

    private function veicularAtivo(): bool
    {
        return $this->dao()->veicularAtivo();
    }
}
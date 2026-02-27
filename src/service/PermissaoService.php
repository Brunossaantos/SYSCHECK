<?php

namespace service;

class PermissaoService
{
    private $conexao;
    private $idUsuario;
    private $idEmpresa;
    private $idPerfil;

    public function __construct($conexao, int $idUsuario, int $idEmpresa)
    {
        $this->conexao   = $conexao;
        $this->idUsuario = $idUsuario;
        $this->idEmpresa = $idEmpresa;

        $this->carregarPerfilUsuario();
    }

    private function carregarPerfilUsuario(): void
    {
        $stmt = $this->conexao->prepare("
            SELECT fk_perfil 
            FROM tbl_usuarios 
            WHERE ID_USUARIO = ?
        ");
        if (!$stmt) {
            throw new \Exception("Erro ao preparar SQL: " . $this->conexao->error);
        }

        $stmt->bind_param("i", $this->idUsuario);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $this->idPerfil = (int) $row['fk_perfil'];
    }

    /**
     * Retorna array com IDs de usuários que ele pode visualizar
     */
    public function getUsuariosPermitidos(): array
    {
        switch ($this->idPerfil) {
            case 1:
            case 7:
            case 8:
                return $this->getTodosDaEmpresa();

            case 2:
                return [$this->idUsuario];

            case 3:
                return $this->getUsuariosPorPerfis([2]);

            case 4:
                return [$this->idUsuario];

            case 5:
                return $this->getUsuariosPorPerfis([4]);

            default:
                return [$this->idUsuario];
        }
    }

    private function getTodosDaEmpresa(): array
    {
        $stmt = $this->conexao->prepare("
            SELECT ID_USUARIO 
            FROM tbl_usuarios
            WHERE fk_empresa = ?
        ");
        if (!$stmt) {
            throw new \Exception("Erro ao preparar SQL: " . $this->conexao->error);
        }

        $stmt->bind_param("i", $this->idEmpresa);
        $stmt->execute();

        $result = $stmt->get_result();
        $usuarios = [];

        while ($row = $result->fetch_assoc()) {
            $usuarios[] = (int) $row['ID_USUARIO'];
        }

        return $usuarios;
    }

    private function getUsuariosPorPerfis(array $perfis): array
    {
        $placeholders = implode(',', array_fill(0, count($perfis), '?'));
        $types = str_repeat('i', count($perfis) + 1); // +1 para fk_empresa

        $sql = "
            SELECT ID_USUARIO 
            FROM tbl_usuarios
            WHERE fk_empresa = ?
            AND fk_perfil IN ($placeholders)
        ";

        $stmt = $this->conexao->prepare($sql);
        if (!$stmt) {
            throw new \Exception("Erro ao preparar SQL: " . $this->conexao->error);
        }

        $params = array_merge([$this->idEmpresa], $perfis);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $result = $stmt->get_result();
        $usuarios = [$this->idUsuario];

        while ($row = $result->fetch_assoc()) {
            $usuarios[] = (int) $row['ID_USUARIO'];
        }

        return array_unique($usuarios);
    }

    /* =========================================================
       CARDS VISÍVEIS POR PERFIL
       ========================================================= */
    public function getCardsVisiveis(): array
    {
        $linksChecklists = [
            ['texto' => 'Iniciar', 'url' => '/syscheck/checklist/iniciarChecklist'],
            ['texto' => 'Consultar', 'url' => '/syscheck/checklist/listarChecklists']
        ];

        $linksTiposChecklist  = [
            ['texto' => 'Cadastrar', 'url' => '/syscheck/tiposchecklist/cadastrarnovotipo'],
            ['texto' => 'Consultar', 'url' => '/syscheck/tiposchecklist/gerenciarTipos']
        ];
        $linksEtapasChecklist = [
            ['texto' => 'Cadastrar', 'url' => '/syscheck/etapaschecklist/cadastrarnovaetapa'],
            ['texto' => 'Consultar', 'url' => '/syscheck/etapaschecklist/consultarChecklists']
        ];
        $linksItensChecklist  = [
            ['texto' => 'Cadastrar', 'url' => '/syscheck/objeto/cadastrarobjeto'],
            ['texto' => 'Consultar', 'url' => '/syscheck/objeto/listarobjetos']
        ];

        $cardsCompletos = [
            ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linksChecklists],
            ['titulo' => 'Tipos de checklist', 'descricao' => 'Gerenciar tipos de checklist', 'links' => $linksTiposChecklist],
            ['titulo' => 'Etapas de checklist', 'descricao' => 'Gerenciar etapas de checklist', 'links' => $linksEtapasChecklist],
            ['titulo' => 'Itens de checklist', 'descricao' => 'Gerenciar itens de checklist', 'links' => $linksItensChecklist]
        ];

        $cardsChecklists = [
            ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linksChecklists]
        ];

        switch ($this->idPerfil) {
            case 1:
            case 7:
            case 8:
                return $cardsCompletos;

            case 2:
            case 3:
            case 4:
            case 5:
                return $cardsChecklists;

            default:
                return $cardsChecklists;
        }
    }
}
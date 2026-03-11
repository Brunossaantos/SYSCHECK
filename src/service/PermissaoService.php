<?php

namespace service;

use Util\Util;
use Exception;

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

    /**
     * Retorna o perfil do usuário logado
     */
    public function getPerfil(): int
    {
        return $this->idPerfil;
    }

    /**
     * Carrega o perfil do usuário logado
     */
    private function carregarPerfilUsuario(): void
    {
        $stmt = $this->conexao->prepare("
            SELECT fk_perfil 
            FROM tbl_usuarios 
            WHERE ID_USUARIO = ? AND fk_empresa = ?
        ");

        if (!$stmt) {
            throw new Exception("Erro ao preparar SQL: " . $this->conexao->error);
        }

        $stmt->bind_param("ii", $this->idUsuario, $this->idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            throw new Exception("Usuário não pertence à empresa.");
        }

        $this->idPerfil = (int)$row['fk_perfil'];
    }

    /**
     * Retorna array com IDs de usuários que o usuário pode visualizar
     */
    public function getUsuariosPermitidos(): array
    {
        try {
            $perfilLogado = $this->getPerfil();

            // 🔹 Perfis 1, 7 e 8 veem TODOS da empresa
            if (in_array($perfilLogado, [1, 7, 8])) {
                return $this->getTodosDaEmpresa();
            }

            // 🔹 Perfis 2 e 4 veem apenas os próprios
            //if (in_array($perfilLogado, [2, 4])) {
            //    return [$this->idUsuario];
            // }

            if ($perfilLogado == 2) {
                return [$this->idUsuario];
            }

            // 🔹 Perfil 3 vê ele + todos do perfil 2
            if ($perfilLogado == 3) {
                return $this->getUsuariosPorPerfis([2]);
            }

            if ($perfilLogado == 4) {
                return [$this->idUsuario];
            }

            // 🔹 Perfil 5 vê ele + todos do perfil 4
            if ($perfilLogado == 5) {
                return $this->getUsuariosPorPerfis([4]);
            }

            // 🔹 fallback de segurança
            return [$this->idUsuario];
        } catch (\Exception $e) {
            Util::inserirErro($e, "getUsuariosPermitidos", $this->idUsuario);
            return [$this->idUsuario];
        }
    }

    /**
     * Retorna todos os usuários ativos da empresa
     */
    private function getTodosDaEmpresa(): array
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_USUARIO 
                FROM tbl_usuarios 
                WHERE fk_empresa = ? AND STATUS_USUARIO = 1
            ");

            if (!$stmt) {
                throw new Exception("Erro ao preparar SQL: " . $this->conexao->error);
            }

            $stmt->bind_param("i", $this->idEmpresa);
            $stmt->execute();
            $result = $stmt->get_result();

            $usuarios = [];
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = (int)$row['ID_USUARIO'];
            }

            return $usuarios;
        } catch (Exception $e) {
            Util::inserirErro($e, "getTodosDaEmpresa", $this->idUsuario);
            return [$this->idUsuario];
        }
    }

    /**
     * Retorna todos os usuários ativos da empresa com perfis específicos
     */
    private function getUsuariosPorPerfis(array $perfis): array
    {
        try {
            $placeholders = implode(',', array_fill(0, count($perfis), '?'));
            $types = 'i' . str_repeat('i', count($perfis)); // primeiro i = idEmpresa

            $sql = "
                SELECT ID_USUARIO
                FROM tbl_usuarios
                WHERE fk_empresa = ? AND fk_perfil IN ($placeholders) AND STATUS_USUARIO = 1
            ";

            $stmt = $this->conexao->prepare($sql);
            if (!$stmt) {
                throw new Exception("Erro ao preparar SQL: " . $this->conexao->error);
            }

            $params = array_merge([$this->idEmpresa], $perfis);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $usuarios = [$this->idUsuario]; // sempre inclui o próprio usuário

            while ($row = $result->fetch_assoc()) {
                $usuarios[] = (int)$row['ID_USUARIO'];
            }

            return array_unique($usuarios);
        } catch (Exception $e) {
            Util::inserirErro($e, "getUsuariosPorPerfis", $this->idUsuario);
            return [$this->idUsuario];
        }
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

            default:
                return $cardsChecklists;
        }
    }
}

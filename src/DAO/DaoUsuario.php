<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Usuario;
use Util\Util;

class DaoUsuario
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_usuarios = TBL_USUARIOS;

    public function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    // =========================
    // CADASTRAR NOVO USUÁRIO
    // =========================
    public function cadastrarNovoUsuario(Usuario $usuario)
    {
        $conn = $this->conexao;

        try {
            $nome          = $usuario->getNome();
            $departamento  = $usuario->getDepartamento();
            $cargo         = $usuario->getCargo();
            $nomeUsuario   = $usuario->getNomeUsuario();
            $senha         = !empty($usuario->getSenha()) ? password_hash($usuario->getSenha(), PASSWORD_DEFAULT) : null;
            $statusUsuario = $usuario->getStatusUsuario();
            $checklist     = $usuario->getChecklistVeicular();
            $tipoChecklist = 0;
            $fkEmpresa     = $usuario->getFkEmpresa() ?: 1; // Default = Matriz
            $fkPerfil      = $usuario->getFkPerfil() ?: 1;  // Default = 1

            // Declarar variáveis para bind_param (passadas por referência)
            $tipoChecklistVar = $tipoChecklist;
            $fkEmpresaVar     = $fkEmpresa;
            $fkPerfilVar      = $fkPerfil;

            $sql = "INSERT INTO tbl_usuarios
            (NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, SENHA, STATUS_USUARIO, checklist_veicular, TIPO_CHECKLIST, fk_empresa, fk_perfil)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Erro prepare(): " . $conn->error);
            }

            $departamento  = 1;
            $cargo         = 2;
            $tipoChecklist = 0;
            $checklist     = $usuario->getChecklistVeicular();
            $fk_empresa    = $usuario->getFkEmpresa() ?: 1;
            $fk_perfil     = $usuario->getFkPerfil() ?: 2;

            $stmt->bind_param(
                "siissiiiii",
                $nome,
                $departamento,
                $cargo,
                $nomeUsuario,
                $senha,
                $statusUsuario,
                $checklist,
                $tipoChecklist,
                $fk_empresa,
                $fk_perfil
            );

            if (!$stmt->execute()) {
                throw new \Exception("Erro execute(): " . $stmt->error);
            }

            return $stmt->insert_id;
        } catch (\Exception $e) {
            // Log de erro via Util
            Util::inserirErro($e, "cadastrarNovoUsuario", $this->idUsuarioSessao);
            return -1;
        }
    }

    // =========================
    // SELECIONAR USUÁRIO POR ID
    // =========================
    public function selecionarUsuario($idUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO,
                       SENHA, STATUS_USUARIO, TIPO_CHECKLIST, fk_empresa, fk_perfil
                FROM {$this->tbl_usuarios}
                WHERE ID_USUARIO = ?
            ");

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Usuario(
                    $row['ID_USUARIO'],
                    $row['NOME'],
                    $row['DEPARTAMENTO'],
                    $row['CARGO'],
                    $row['NOME_USUARIO'],
                    $row['SENHA'],
                    $row['STATUS_USUARIO'],
                    $row['TIPO_CHECKLIST'],
                    $row['fk_empresa'],
                    $row['fk_perfil']
                );
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarUsuario", $this->idUsuarioSessao);
            return null;
        }
    }

    // =========================
    // ALTERAR USUÁRIO
    // =========================
    public function alterarUsuario(Usuario $usuario)
    {
        try {
            $sql = "
                UPDATE {$this->tbl_usuarios} SET
                    NOME = ?,
                    DEPARTAMENTO = ?,
                    CARGO = ?,
                    NOME_USUARIO = ?,
                    STATUS_USUARIO = ?,
                    checklist_veicular = ?,
                    TIPO_CHECKLIST = ?,
                    fk_empresa = ?,
                    fk_perfil = ?
                WHERE ID_USUARIO = ?";

            $stmt = $this->conexao->prepare($sql);

            $nome             = $usuario->getNome();
            $departamento     = $usuario->getDepartamento();
            $cargo            = $usuario->getCargo();
            $nomeUsuario      = $usuario->getNomeUsuario();
            $statusUsuario    = $usuario->getStatusUsuario();
            $checklistVeicular = $usuario->getChecklistVeicular();
            $tipoChecklist    = 0; // constante ou valor fixo
            $fkEmpresa        = $usuario->getFkEmpresa();
            $fkPerfil         = $usuario->getFkPerfil();
            $idUsuario        = $usuario->getIdUsuario();

            $stmt->bind_param(
                "ssiissiiii",
                $nome,
                $departamento,
                $cargo,
                $nomeUsuario,
                $statusUsuario,
                $checklistVeicular,
                $tipoChecklist,
                $fkEmpresa,
                $fkPerfil,
                $idUsuario
            );

            $stmt->execute();
            return $stmt->affected_rows;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarUsuario", $this->idUsuarioSessao);
            return -1;
        }
    }

    // =========================
    // ALTERAR SENHA DE USUÁRIO
    // =========================
    public function alterarSenhaUsuario(Usuario $usuario)
    {
        try {
            $senha = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);
            $stmt = $this->conexao->prepare("
                UPDATE {$this->tbl_usuarios}
                SET SENHA = ?
                WHERE ID_USUARIO = ?
            ");

            $stmt->bind_param("si", $senha, $usuario->getIdUsuario());
            $stmt->execute();
            return $stmt->affected_rows;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarSenhaUsuario", $this->idUsuarioSessao);
            return -1;
        }
    }

    // =========================
    // LISTAR TODOS OS USUÁRIOS
    // =========================
    public function retornarListaUsuarios()
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO,
                       SENHA, STATUS_USUARIO, TIPO_CHECKLIST, fk_empresa, fk_perfil
                FROM {$this->tbl_usuarios}
            ");

            $stmt->execute();
            $result = $stmt->get_result();
            $listaUsuarios = [];

            while ($row = $result->fetch_assoc()) {
                $listaUsuarios[] = new Usuario(
                    $row['ID_USUARIO'],
                    $row['NOME'],
                    $row['DEPARTAMENTO'],
                    $row['CARGO'],
                    $row['NOME_USUARIO'],
                    $row['SENHA'],
                    $row['STATUS_USUARIO'],
                    $row['TIPO_CHECKLIST'],
                    $row['fk_empresa'],
                    $row['fk_perfil']
                );
            }

            return $listaUsuarios;
        } catch (Exception $e) {
            Util::inserirErro($e, "retornarListaUsuarios", $this->idUsuarioSessao);
            return [];
        }
    }

    // =========================
    // VERIFICAR SENHA EXISTENTE
    // =========================
    public function verificarSenha($idUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT SENHA FROM {$this->tbl_usuarios} WHERE ID_USUARIO = ?
            ");

            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['SENHA'] ?: null;
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarSenha", $this->idUsuarioSessao);
            return null;
        }
    }
}

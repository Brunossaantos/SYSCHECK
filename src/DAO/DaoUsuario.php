<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Usuario;
use database\Conexao;
use Util\Util;

class DaoUsuario
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_usuarios = TBL_USUARIOS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }


    function inserirUsuario(Usuario $usuario)
    {
        $nome = strtoupper($usuario->getNome());
        $departamento = $usuario->getDepartamento();
        $cargo = $usuario->getCargo();
        $nomeUsuario = strtoupper($usuario->getNomeUsuario());
        $senha = $usuario->getSenha();
        $status = $usuario->getStatusUsuario();
        $tipoChecklist = $usuario->getUserTipoChecklist();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_usuarios} (NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, SENHA, STATUS_USUARIO, TIPO_CHECKLIST) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("siissii", $nome, $departamento, $cargo, $nomeUsuario, $senha, $status, $tipoChecklist);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirUsuario", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarUsuario($idUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, SENHA, STATUS_USUARIO, TIPO_CHECKLIST FROM {$this->tbl_usuarios} WHERE ID_USUARIO = ?");
            $stmt->bind_param("i", $idUsuario);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Usuario($row['ID_USUARIO'], $row['NOME'], $row['DEPARTAMENTO'], $row['CARGO'], $row['NOME_USUARIO'], $row['SENHA'], $row['STATUS_USUARIO'], $row['TIPO_CHECKLIST']);
            }

            return null;
        } catch (Exception $e) {
            util::inserirErro($e, "selecionarUsuario", $this->idUsuarioSessao);
            return null;
        }
    }

    function alterarUsuario(Usuario $usuario)
    {
        $idUsuario = $usuario->getIdUsuario();
        $nome = $usuario->getNome();
        $departamento = $usuario->getDepartamento();
        $cargo = $usuario->getCargo();
        $nomeUsuario = $usuario->getNomeUsuario();
        $status = $usuario->getStatusUsuario();

        try {
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_usuarios} SET NOME = ?, DEPARTAMENTO = ?, CARGO = ?, NOME_USUARIO = ?, STATUS_USUARIO = ? WHERE ID_USUARIO = ?");
            $stmt->bind_param("ssssii", $nome, $departamento, $cargo, $nomeUsuario, $status, $idUsuario);

            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarUsuario", $this->idUsuarioSessao);
            return -2;
        }
    }


    function retornarListaUsuarios()
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, SENHA, STATUS_USUARIO, TIPO_CHECKLIST FROM {$this->tbl_usuarios}");
            $stmt->execute();

            $listaUsuarios = [];

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $usuario = new Usuario($row['ID_USUARIO'], $row['NOME'], $row['DEPARTAMENTO'], $row['CARGO'], $row['NOME_USUARIO'], $row['SENHA'], $row['STATUS_USUARIO'], $row['TIPO_CHECKLIST']);
                    $listaUsuarios[] = $usuario;
                }
            }

            return $listaUsuarios;
        } catch (Exception $e) {
            Util::inserirErro($e, "retonarListaUsuarios", $this->idUsuarioSessao);
            return null;
        }
    }


    function alterarSenhaUsuario(Usuario $usuario)
    {

        $idUsuario = $usuario->getIdUsuario();
        $senha = password_hash($usuario->getSenha(), PASSWORD_DEFAULT);

        try {
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_usuarios} SET SENHA = ? WHERE ID_USUARIO = ?");
            $stmt->bind_param("si", $senha, $idUsuario);

            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarSenhaUsuario", $this->idUsuarioSessao);
            return -2;
        }
    }


    function verificarSenha($idUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT SENHA FROM {$this->tbl_usuarios} WHERE ID_USUARIO = ?");
            $stmt->bind_param("i", $idUsuario);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['SENHA'];
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarSenhaUsuario", $this->idUsuarioSessao);
            return -2;
        }
    }
}

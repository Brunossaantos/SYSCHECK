<?php

namespace Util;

require __DIR__ . '/../../vendor/autoload.php';

use models\Login;
use models\Usuario;


class Sessao
{

    public static function iniciarSessao(Usuario $usuario)
    {
        session_start();
        $_SESSION['idUsuario'] = $usuario->getIdUsuario();
        $_SESSION['nome'] = $usuario->getNome();
        $_SESSION['departamento'] = $usuario->getDepartamento();
        $_SESSION['cargo'] = $usuario->getCargo();
        $_SESSION['nomeUsuario'] = $usuario->getNomeUsuario();
        $_SESSION['statusUsuario'] = $usuario->getStatusUsuario();
        $_SESSION['fkEmpresa'] = $usuario->getFkEmpresa();
        $_SESSION['idPerfil']       = $usuario->getFkPerfil();
    }

    public static function verificarSessao()
    {
        session_start();

        if (isset($_SESSION['nomeUsuario'])) {
            return true;
        }

        return false;
    }

    public static function verificarSessaoCompleta()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $campos = ['idUsuario', 'nome', 'departamento', 'cargo', 'nomeUsuario', 'statusUsuario'];
        foreach ($campos as $campo) {
            if (!isset($_SESSION[$campo])) {
                echo '<script>
                    alert("Sessão expirada ou inválida. Faça login novamente.");
                    window.location.href = "/syscheck";
                  </script>';
                exit;
            }
        }
    }

    public static function retornarUsuarioLogado()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $idUsuario       = $_SESSION['idUsuario']      ?? null;
        $nome            = $_SESSION['nome']           ?? null;
        $departamento    = $_SESSION['departamento']   ?? null;
        $cargo           = $_SESSION['cargo']          ?? null;
        $nomeUsuario     = $_SESSION['nomeUsuario']    ?? null;
        $statusUsuario   = $_SESSION['statusUsuario']  ?? null;
        $fkEmpresa       = $_SESSION['fkEmpresa']      ?? null;
        $idPerfil        = $_SESSION['idPerfil']       ?? 0;

        if (!$idUsuario || !$nomeUsuario) {
            return null;
        }

        return new Usuario(
            $idUsuario,
            $nome,
            $departamento,
            $cargo,
            $nomeUsuario,
            null,
            $statusUsuario,
            null,
            $fkEmpresa,
            $idPerfil
        );
    }

    public static function recuperarMensagem()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['mensagem'])) {
            $mensagem = $_SESSION['mensagem'];
            unset($_SESSION['mensagem']); // Remove da sessão depois de recuperar
            return $mensagem;
        }

        return null;
    }


    public static function idusuario()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Inicia a sessão apenas se não estiver já ativa
        }

        if (!isset($_SESSION['idUsuario'])) {
            header("Location: /syscheck"); // Redireciona se o ID do usuário não estiver definido
            exit(); // Garante que o script pare após o redirecionamento
        }

        return $_SESSION['idUsuario'];
    }

    public static function nomeUsuario()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['nome'];
    }

    public static function salvarMensagemNaSessao($mensagem)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['mensagem'] = $mensagem;
    }

    public static function mostrarMensagem()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['mensagem'])) {
            echo '<script>
                alert(' . json_encode($_SESSION['mensagem']) . ');
              </script>';

            unset($_SESSION['mensagem']);
        }
    }
}

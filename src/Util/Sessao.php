<?php

namespace Util;

require __DIR__ . '/../../vendor/autoload.php';

use models\Login;
use models\Usuario;

class Sessao
{
    // Padronizamos uma função privada para não repetir código
    private static function garantirSessao()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function iniciarSessao(Usuario $usuario)
    {
        self::garantirSessao(); // <--- Corrigido aqui
        $_SESSION['idUsuario'] = $usuario->getIdUsuario();
        $_SESSION['nome'] = $usuario->getNome();
        $_SESSION['departamento'] = $usuario->getDepartamento();
        $_SESSION['cargo'] = $usuario->getCargo();
        $_SESSION['nomeUsuario'] = $usuario->getNomeUsuario();
        $_SESSION['statusUsuario'] = $usuario->getStatusUsuario();
        $_SESSION['fkEmpresa'] = $usuario->getFkEmpresa();
        $_SESSION['idPerfil'] = $usuario->getFkPerfil();
    }

    public static function destruirSessao()
    {
        self::garantirSessao();

        // 1. Limpa todas as variáveis da memória (superglobal $_SESSION)
        $_SESSION = [];

        // 2. Se quiser ser rigoroso, apaga o cookie da sessão no navegador
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // 3. Destrói o arquivo físico da sessão no servidor
        session_destroy();
    }

    public static function verificarSessao()
    {
        self::garantirSessao(); // <--- Corrigido aqui

        if (isset($_SESSION['nomeUsuario'])) {
            return true;
        }

        return false;
    }

    public static function verificarSessaoCompleta()
    {
        self::garantirSessao();

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
        self::garantirSessao();

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
        self::garantirSessao();

        if (!empty($_SESSION['mensagem'])) {
            $mensagem = $_SESSION['mensagem'];
            unset($_SESSION['mensagem']);
            return $mensagem;
        }

        return null;
    }

    public static function idusuario(): int
    {
        self::garantirSessao();

        if (!isset($_SESSION['idUsuario'])) {
            header("Location: /syscheck");
            exit();
        }

        return (int) $_SESSION['idUsuario'];
    }

    public static function idempresa(): int
    {
        self::garantirSessao(); // <--- Adicionado garantia aqui

        if (!isset($_SESSION['fkEmpresa'])) {
            header("Location: /syscheck");
            exit();
        }

        return (int) $_SESSION['fkEmpresa'];
    }

    public static function empresaLogada(): int
    {
        self::garantirSessao(); // <--- Adicionado garantia aqui
        return $_SESSION['fkEmpresa'] ?? 0;
    }

    public static function nomeUsuario()
    {
        self::garantirSessao();
        return $_SESSION['nome'] ?? 'Visitante';
    }

    public static function salvarMensagemNaSessao($mensagem)
    {
        self::garantirSessao();
        $_SESSION['mensagem'] = $mensagem;
    }

    public static function mostrarMensagem()
    {
        self::garantirSessao();

        if (!empty($_SESSION['mensagem'])) {
            echo '<script>
                alert(' . json_encode($_SESSION['mensagem']) . ');
              </script>';

            unset($_SESSION['mensagem']);
        }
    }
}

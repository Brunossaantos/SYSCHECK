<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Login;
use DAO\DaoLogin;
use database\Conexao;

class RnLogin
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function selecionarUsuarioPeloNomeUsuario(Login $login)
    {
        return (new DaoLogin((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarUsuarioPeloNome($login);
    }

    //Caso seja o primeiro acesso retorna true, caso não seja, retorna false;
    function verficarPrimerioAcesso(Login $login)
    {
        $usuario = (new DaoLogin((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarUsuarioPeloNome($login);

        if ($usuario != null) {

            if (empty($usuario->getSenha())) {
                return true;
            } else {
                return false;
            }
        }
    }

    function realizarLogin(Login $login)
    {
        $loginBd = (new DaoLogin((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->realizarLogin($login);

        // Caso não encontre usuário, já retorna false
        if ($loginBd === null) {
            return false;
        }

        // Verifica senha
        if (password_verify($login->getSenha(), $loginBd->getSenha())) {
            return true;
        }

        return false;
    }
}

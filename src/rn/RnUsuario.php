<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Usuario;
use DAO\DaoUsuario;
use database\Conexao;

class RnUsuario
{
    private $idUsuarioSessao;
    private $daoUsuario;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
        $this->daoUsuario = new DaoUsuario(
            (new Conexao())->conectar(),
            $this->idUsuarioSessao
        );
    }

    function cadastrarNovoUsuario(Usuario $usuario)
    {
        // chama o método existente no DAO
        return $this->daoUsuario->cadastrarNovoUsuario($usuario);
    }

    function alterarSenhaUsuario(Usuario $usuario)
    {
        return $this->daoUsuario->alterarSenhaUsuario($usuario);
    }

    function selecionarUsuario($idUsuario)
    {
        return $this->daoUsuario->selecionarUsuario($idUsuario);
    }

    function alterarUsuario(Usuario $usuario)
    {
        return $this->daoUsuario->alterarUsuario($usuario);
    }

    function listarUsuarios()
    {
        return $this->daoUsuario->retornarListaUsuarios();
    }

    function verificarSenha($idUsuario)
    {
        $senha = $this->daoUsuario->verificarSenha($idUsuario);

        if (is_null($senha)) {
            return "Não cadastrada";
        }

        if (empty($senha)) {
            return "Não cadastrada";
        }

        return "Cadastrada";
    }
}

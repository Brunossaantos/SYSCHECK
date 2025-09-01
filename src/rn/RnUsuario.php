<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Usuario;
use DAO\DaoUsuario;
use database\Conexao;

class RnUsuario{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function cadastrarNovoUsuario(Usuario $usuario){
        $usuario->setSenha("");
        return (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirUsuario($usuario);
    }

    function alterarSenhaUsuario(Usuario $usuario){
        return (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->alterarSenhaUsuario($usuario);
    }

    function selecionarUsuario($idUsuario){
        return (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarUsuario($idUsuario);
    }

    function alterarUsuario(Usuario $usuario){
        return (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->alterarUsuario($usuario);
    }

    function listarUsuarios(){
        return (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->retornarListaUsuarios();
    }

    function cadastrarSenha(Usuario $usuario){
        return (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->alterarSenhaUsuario($usuario);
    }

    function verificarSenha($idUsuario){
        $senha =  (new DaoUsuario((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarSenha($idUsuario);
        if(!is_null($senha)){
            if(empty($senha)){
                return "Senha não cadastrada";
            } else {
                return "Senha cadastrada";
            }
        }
    }
    
}

?>
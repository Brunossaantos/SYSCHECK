<?php

namespace models;

class Login{
    private $nomeUsuario;
    private $senha;

    function __construct($nomeUsuario, $senha){
        $this->setNomeUsuario($nomeUsuario);
        $this->setSenha($senha);
    }

    function setNomeUsuario($nomeUsuario){
        $this->nomeUsuario = $nomeUsuario;
    }

    function setSenha($senha){
        $this->senha = $senha;
    }

    function getNomeUsuario(){
        return $this->nomeUsuario;
    }

    function getSenha(){
        return $this->senha;
    }
}

?>
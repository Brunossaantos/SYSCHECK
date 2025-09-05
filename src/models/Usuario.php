<?php

namespace models;

class Usuario
{
    private $idUsuario;
    private $nome;
    private $departamento;
    private $cargo;
    private $nomeUsuario;
    private $senha;
    private $statusUsuario;
    private $tipoChecklist;

    function __construct($idUsuario, $nome, $departamento, $cargo, $nomeUsuario, $senha, $statusUsuario, $tipoChecklist = 0)
    {
        $this->setIdUsuario($idUsuario);
        $this->setNome($nome);
        $this->setDepartamento($departamento);
        $this->setCargo($cargo);
        $this->setNomeUsuario($nomeUsuario);
        $this->setSenha($senha);
        $this->setStatusUsuario($statusUsuario);
        $this->setUserTipoChecklist($tipoChecklist);
    }

    //set

    function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    function setNomeUsuario($nomeUsuario)
    {
        $this->nomeUsuario = $nomeUsuario;
    }

    function setSenha($senha)
    {
        $this->senha = $senha;
    }

    function setStatusUsuario($statusUsuario)
    {
        $this->statusUsuario = $statusUsuario;
    }

    function setUserTipoChecklist($tipoChecklist)
    {
        $this->tipoChecklist = $tipoChecklist;
    }

    //get

    function getIdUsuario()
    {
        return $this->idUsuario;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getDepartamento()
    {
        return $this->departamento;
    }

    function getCargo()
    {
        return $this->cargo;
    }

    function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }

    function getSenha()
    {
        return $this->senha;
    }

    function getStatusUsuario()
    {
        return $this->statusUsuario;
    }

    function getUserTipoChecklist()
    {
        return $this->tipoChecklist;
    }
}

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
    private $checklistVeicular; // ALTERADO
    private $fkEmpresa;
    private $fkPerfil;

    function __construct(
        $idUsuario,
        $nome,
        $departamento,
        $cargo,
        $nomeUsuario,
        $senha,
        $statusUsuario,
        $checklistVeicular = 0,
        $fkEmpresa = 0,
        $fkPerfil = 0
    ) {
        $this->setIdUsuario($idUsuario);
        $this->setNome($nome);
        $this->setDepartamento($departamento);
        $this->setCargo($cargo);
        $this->setNomeUsuario($nomeUsuario);
        $this->setSenha($senha);
        $this->setStatusUsuario($statusUsuario);
        $this->setChecklistVeicular($checklistVeicular);
        $this->setFkEmpresa($fkEmpresa);
        $this->setFkPerfil($fkPerfil);
    }

    // =========================
    // SETTERS
    // =========================

    function setIdUsuario($idUsuario)
    {
        $this->idUsuario = (int) $idUsuario;
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
        $this->statusUsuario = (int) $statusUsuario;
    }

    function setChecklistVeicular($checklistVeicular)
    {
        $this->checklistVeicular = (int) $checklistVeicular;
    }

    function setFkEmpresa($fkEmpresa)
    {
        $this->fkEmpresa = (int) $fkEmpresa;
    }

    function setFkPerfil($fkPerfil)
    {
        $this->fkPerfil = (int) $fkPerfil;
    }

    // =========================
    // GETTERS
    // =========================

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

    function getChecklistVeicular()
    {
        return $this->checklistVeicular;
    }

    function getFkEmpresa()
    {
        return $this->fkEmpresa;
    }

    function getFkPerfil()
    {
        return $this->fkPerfil;
    }
}

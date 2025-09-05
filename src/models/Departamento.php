<?php

namespace models;

class Departamento
{
    private $idDepartamento;
    private $descricaoDepartamento;
    private $statusDepartamento;

    function __construct($idDepartamento, $descricaoDepartamento, $statusDepartamento)
    {
        $this->setIdDepartamento($idDepartamento);
        $this->setDescricaoDepartamento($descricaoDepartamento);
        $this->setStatusDepartamento($statusDepartamento);
    }

    function setIdDepartamento($idDepartamento)
    {
        $this->idDepartamento = $idDepartamento;
    }

    function setDescricaoDepartamento($descricaoDepartamento)
    {
        $this->descricaoDepartamento = $descricaoDepartamento;
    }

    function setStatusDepartamento($statusDepartamento)
    {
        $this->statusDepartamento = $statusDepartamento;
    }

    function getIdDepartamento()
    {
        return $this->idDepartamento;
    }

    function getDescricaoDepartamento()
    {
        return $this->descricaoDepartamento;
    }

    function getStatusDepartamento()
    {
        return $this->statusDepartamento;
    }
}

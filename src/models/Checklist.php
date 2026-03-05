<?php

namespace models;

class Checklist
{
    private $idChecklist;
    private $fkUsuario;
    private $usuario;
    private $fkTipo;
    private $fkObjeto;
    private $dataInicio;
    private $dataFim;
    private $statusChecklist;

    public function __construct(
        $idChecklist = null,
        $fkUsuario = null,
        $usuario = null,
        $fkTipo = null,
        $fkObjeto = null,
        $dataInicio = null,
        $dataFim = null,
        $statusChecklist = null
    ) {
        $this->idChecklist = $idChecklist;
        $this->fkUsuario = $fkUsuario;
        $this->usuario = $usuario;
        $this->fkTipo = $fkTipo;
        $this->fkObjeto = $fkObjeto;
        $this->dataInicio = $dataInicio;
        $this->dataFim = $dataFim;
        $this->statusChecklist = $statusChecklist;
    }

    // =========================
    // SETTERS
    // =========================
    function setIdChecklist($idChecklist)
    {
        $this->idChecklist = $idChecklist;
    }
    function setFkUsuario($fkUsuario)
    {
        $this->fkUsuario = $fkUsuario;
    }
    function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    } // NOVO
    function setFkTipo($fkTipo)
    {
        $this->fkTipo = $fkTipo;
    }
    function setFkObjeto($fkObjeto)
    {
        $this->fkObjeto = $fkObjeto;
    }
    function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }
    function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
    }
    function setStatusChecklist($status)
    {
        $this->statusChecklist = $status;
    }

    // =========================
    // GETTERS
    // =========================
    function getIdChecklist()
    {
        return $this->idChecklist;
    }
    function getFkUsuario()
    {
        return $this->fkUsuario;
    }
    function getUsuario()
    {
        return $this->usuario;
    } // NOVO
    function getFkTipo()
    {
        return $this->fkTipo;
    }
    function getFkObjeto()
    {
        return $this->fkObjeto;
    }
    function getDataInicio()
    {
        return $this->dataInicio;
    }
    function getDataFim()
    {
        return $this->dataFim;
    }
    function getStatusChecklist()
    {
        return $this->statusChecklist;
    }
}

<?php

namespace models;

class TipoChecklist
{
    private $idTipoChecklist;
    private $descricaoTipoChecklist;
    private $fkResponsavel;
    private $statusTipoChecklist;

    function __construct($idTipoChecklist = 0, $descricaoTipoChecklist = "", $fkResponsavel = 0, $statusTipoChecklist = 0)
    {
        $this->setIdTipoChecklist($idTipoChecklist);
        $this->setDescricaoTipoChecklist($descricaoTipoChecklist);
        $this->setFkResponsavel($fkResponsavel);
        $this->setStatusTipoChecklist($statusTipoChecklist);
    }

    function setIdTipoChecklist($idTipoChecklist)
    {
        $this->idTipoChecklist = $idTipoChecklist;
    }

    function setDescricaoTipoChecklist($descricaoTipoChecklist)
    {
        $this->descricaoTipoChecklist = $descricaoTipoChecklist;
    }

    function setFkResponsavel($fkResponsavel)
    {
        $this->fkResponsavel = $fkResponsavel;
    }

    function setStatusTipoChecklist($statusTipoChecklist)
    {
        $this->statusTipoChecklist = $statusTipoChecklist;
    }

    function getIdTipoChecklist()
    {
        return $this->idTipoChecklist;
    }

    function getDescricaoTipoChecklist()
    {
        return $this->descricaoTipoChecklist;
    }

    function getFkResponsavel()
    {
        return $this->fkResponsavel;
    }

    function getStatusTipoChecklist()
    {
        return $this->statusTipoChecklist;
    }
}

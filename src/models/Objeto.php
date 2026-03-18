<?php

namespace models;

class Objeto
{
    private $idObjeto;
    private $descricaoObjeto;
    private $fkTipoChecklist;
    private $statusObjeto;
    private $fkEmpresa; // Novo atributo

    function __construct($idObjeto, $descricaoObjeto, $fkTipoChecklist, $statusObjeto, $fkEmpresa = 1)
    {
        $this->setIdObjeto($idObjeto);
        $this->setDescricaoObjeto($descricaoObjeto);
        $this->setFkTipoChecklist($fkTipoChecklist);
        $this->setStatusObjeto($statusObjeto);
        $this->setFkEmpresa($fkEmpresa); // Novo setter
    }

    function setIdObjeto($idObjeto)
    {
        $this->idObjeto = $idObjeto;
    }

    function setDescricaoObjeto($descricaoObjeto)
    {
        $this->descricaoObjeto = $descricaoObjeto;
    }

    function setFkTipoChecklist($fkTipoChecklist)
    {
        $this->fkTipoChecklist = $fkTipoChecklist;
    }

    function setStatusObjeto($statusObjeto)
    {
        $this->statusObjeto = $statusObjeto;
    }

    // Novo Setter
    function setFkEmpresa($fkEmpresa)
    {
        $this->fkEmpresa = $fkEmpresa;
    }

    function getIdObjeto()
    {
        return $this->idObjeto;
    }

    function getDescricaoObjeto()
    {
        return $this->descricaoObjeto;
    }

    function getFkTipoChecklist()
    {
        return $this->fkTipoChecklist;
    }

    function getStatusObjeto()
    {
        return $this->statusObjeto;
    }

    // Novo Getter
    function getFkEmpresa()
    {
        return $this->fkEmpresa;
    }

    function toArray()
    {
        return [
            'idObjeto' => $this->getIdObjeto(),
            'descricaoObjeto' => $this->getDescricaoObjeto(),
            'tipoChecklist' => $this->getFkTipoChecklist(),
            'statusObjeto' => $this->getStatusObjeto(),
            'fkEmpresa' => $this->getFkEmpresa() // Adicionado ao array
        ];
    }
}

<?php

namespace models;

class Objeto{
    private $idObjeto;
    private $descricaoObjeto;
    private $fkTipoChecklist;
    private $statusObjeto;

    function __construct($idObjeto, $descricaoObjeto, $fkTipoChecklist, $statusObjeto){
        $this->setIdObjeto($idObjeto);
        $this->setDescricaoObjeto($descricaoObjeto);
        $this->setFkTipoChecklist($fkTipoChecklist);
        $this->setStatusObjeto($statusObjeto);
    }

    function setIdObjeto($idObjeto){
        $this->idObjeto = $idObjeto;
    }

    function setDescricaoObjeto($descricaoObjeto){
        $this->descricaoObjeto = $descricaoObjeto;
    }

    function setFkTipoChecklist($fkTipoChecklist){
        $this->fkTipoChecklist = $fkTipoChecklist;
    }

    function setStatusObjeto($statusObjeto){
        $this->statusObjeto = $statusObjeto;
    }

    function getIdObjeto(){
        return $this->idObjeto;
    }

    function getDescricaoObjeto(){
        return $this->descricaoObjeto;
    }

    function getFkTipoChecklist(){  
        return $this->fkTipoChecklist;
    }

    function getStatusObjeto(){
        return $this->statusObjeto;
    }

    function toArray(){
        return [
            'idObjeto' => $this->getIdObjeto(),
            'descricaoObjeto' => $this->getDescricaoObjeto(),
            'tipoChecklist' => $this->getFkTipoChecklist(),
            'statusObjeto' => $this->getStatusObjeto()
        ];
    }
}

?>
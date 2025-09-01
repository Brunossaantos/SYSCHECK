<?php

namespace models;

class Checklist{
    private $idChecklist;
    private $fkUsuario;
    private $fkTipo;
    private $fkObjeto;
    private $dataInicio;
    private $dataFim;
    private $statusChecklist;

    function __construct($idChecklist, $fkUsuario, $fkTipo, $fkObjeto, $dataInicio, $dataFim, $status){
        $this->setIdChecklist($idChecklist);
        $this->setFkUsuario($fkUsuario);
        $this->setFkTipo($fkTipo);
        $this->setFkObjeto($fkObjeto);
        $this->setDataInicio($dataInicio);
        $this->setDataFim($dataFim);
        $this->setStatusChecklist($status);
    }

    //set

    function setIdChecklist($idChecklist){
        $this->idChecklist = $idChecklist;
    }

    function setFkUsuario($fkUsuario){
        $this->fkUsuario = $fkUsuario;
    }

    function setFkTipo($fkTipo){
        $this->fkTipo = $fkTipo;
    }

    function setFkObjeto($fkObjeto){
        $this->fkObjeto = $fkObjeto;
    }

    function setDataInicio($dataInicio){
        $this->dataInicio = $dataInicio;
    }

    function setDataFim($dataFim){
        $this->dataFim = $dataFim;
    }

    function setStatusChecklist($status){
        $this->statusChecklist = $status;
    }

    //get

    function getIdChecklist(){
        return $this->idChecklist;
    }

    function getFkUsuario(){
        return $this->fkUsuario;
    }

    function getFkTipo(){
        return $this->fkTipo;
    }

    function getFkObjeto(){
        return $this->fkObjeto;
    }

    function getDataInicio(){
        return $this->dataInicio;
    }

    function getDataFim(){
        return $this->dataFim;
    }

    function getStatusChecklist(){
        return $this->statusChecklist;
    }
}

?>
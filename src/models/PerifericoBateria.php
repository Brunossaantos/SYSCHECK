<?php

namespace models;

class PerifericoBateria{
    private $idPeriferico;
    private $tipoPeriferico;
    private $descricaoPeriferico;
    private $statusPeriferico;

    function __construct($idPeriferico = 0, $tipoPeriferico, $descricaoPeriferico, $statusPeriferico){
        $this->setIdPeriferico($idPeriferico);
        $this->setTipoPeriferico($tipoPeriferico);
        $this->setDescricaoPeriferico($descricaoPeriferico);
        $this->setStatusPeriferico($statusPeriferico);
    }

    function setIdPeriferico($idPeriferico){
        $this->idPeriferico = $idPeriferico;
    }

    function setTipoPeriferico($tipoPeriferico){
        $this->tipoPeriferico = $tipoPeriferico;
    }

    function setDescricaoPeriferico($descricaoPeriferico){
        $this->descricaoPeriferico = $descricaoPeriferico;
    }

    function setStatusPeriferico($statusPeriferico){
        $this->statusPeriferico = $statusPeriferico;
    }

    function getIdPeriferico(){
        return $this->idPeriferico;
    }

    function getTipoPeriferico(){
        return $this->tipoPeriferico;
    }

    function getDescricaoPeriferico(){
        return $this->descricaoPeriferico;
    }

    function getStatusPeriferico(){
        return $this->statusPeriferico;
    }
}

?>
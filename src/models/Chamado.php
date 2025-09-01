<?php

namespace models;

class Chamado{
    private $idChamado;
    private $fkItemChamado;
    private $descricaoChamado;
    private $dataAberturaChamado;
    private $dataFinalizacaoChamado;
    private $fkUsuario;
    private $statusChamado;

    function __construct($idChamado = 0, $fkItemChamado, $descricaoChamado, $dataAberturaChamado, $dataFinalizacaoChamado, $fkUsuario, $statusChamado){
        $this->setIdChamado($idChamado);
        $this->setFkItemChamado($fkItemChamado);
        $this->setDescricaoChamado($descricaoChamado);
        $this->setDataAberturaChamado($dataAberturaChamado);
        $this->setFkUsuario($fkUsuario);
        $this->setStatusChamado($statusChamado);
    }

    function setIdChamado($idChamado){
        $this->idChamado = $idChamado;
    }

    function setFkItemChamado($fkItemChamado){
        $this->fkItemChamado = $fkItemChamado;
    }

    function setDescricaoChamado($descricaoChamado){
        $this->descricaoChamado = $descricaoChamado;
    }

    function setDataAberturaChamado($dataAberturaChamado){
        $this->dataAberturaChamado = $dataAberturaChamado;
    }

    function setDataFinalizacaoChamado($dataFinalizacaoChamado){
        $this->dataFinalizacaoChamado = $dataFinalizacaoChamado;
    }

    function setFkUsuario($fkUsuario){
        $this->fkUsuario = $fkUsuario;
    }

    function setStatusChamado($statusChamado){
        $this->statusChamado = $statusChamado;
    }

    function getIdChamado(){
        return $this->idChamado;
    }

    function getFkItemChamado(){
        return $this->fkItemChamado;
    }

    function getDescricaoChamado(){
        return $this->descricaoChamado;
    }

    function getDataAberturaChamado(){
        return $this->dataAberturaChamado;
    }

    function getDataFinalizacaoChamado(){
        return $this->dataFinalizacaoChamado;
    }

    function getFkUsuario(){
        return $this->fkUsuario;
    }

    function getStatusChamado(){
        return $this->statusChamado;
    }
}

?>
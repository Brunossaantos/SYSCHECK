<?php

namespace models;

class Bateria
{
    private $idBateria;
    private $numeroBateria;
    private $descricaoBateria;
    private $medidas;
    private $observacao;

    function __construct($idBateria = 0, $numeroBateria, $descricaoBateria, $medidas, $observacao = "")
    {
        $this->setIdBateria($idBateria);
        $this->setNumeroBateria($numeroBateria);
        $this->setDescricaoBateria($descricaoBateria);
        $this->setMedidas($medidas);
        $this->setObservacao($observacao);
    }

    function setIdBateria($idBateria)
    {
        $this->idBateria = $idBateria;
    }

    function setNumeroBateria($numeroBateria)
    {
        $this->numeroBateria = $numeroBateria;
    }

    function setDescricaoBateria($descricaoBateria)
    {
        $this->descricaoBateria = $descricaoBateria;
    }

    function setMedidas($medidas)
    {
        $this->medidas = $medidas;
    }

    function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

    //get

    function getIdBateria()
    {
        return $this->idBateria;
    }

    function getNumeroBateria()
    {
        return $this->numeroBateria;
    }

    function getDescricaoBateria()
    {
        return $this->descricaoBateria;
    }

    function getMedidas()
    {
        return $this->medidas;
    }

    function getObservacao()
    {
        return $this->observacao;
    }
}

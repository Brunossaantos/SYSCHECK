<?php

namespace models;

class Foto
{

    private $idFoto;
    private $fkCheckist;
    private $numeroEtapa;
    private $caminhoFoto;

    function __construct($idFoto, $fkCheckist, $numeroEtapa, $caminhoFoto)
    {
        $this->setIdFoto($idFoto);
        $this->setFkChecklist($fkCheckist);
        $this->setNumeroEtapa($numeroEtapa);
        $this->setCaminhoFoto($caminhoFoto);
    }

    function setIdFoto($idFoto)
    {
        $this->idFoto = $idFoto;
    }

    function setFkChecklist($fkCheckist)
    {
        $this->fkCheckist = $fkCheckist;
    }

    function setNumeroEtapa($numeroEtapa)
    {
        $this->numeroEtapa = $numeroEtapa;
    }

    function setCaminhoFoto($caminhoFoto)
    {
        $this->caminhoFoto = $caminhoFoto;
    }

    function getIdFoto()
    {
        return $this->idFoto;
    }

    function getFkChecklist()
    {
        return $this->fkCheckist;
    }

    function getNumeroEtapa()
    {
        return $this->numeroEtapa;
    }

    function getCaminhoFoto()
    {
        return $this->caminhoFoto;
    }
}

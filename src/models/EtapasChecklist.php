<?php

namespace models;

class EtapasChecklist
{
    private $idEtapaChecklist;
    private $fkTipoChecklist;
    private $tituloEtapa;
    private $conteudoEtapa;
    private $numeroEtapa;
    private $fotoObrigatoria;
    private $campoAdicional;
    private $statusEtapa;

    function __construct($idEtapaChecklist, $fkTipoChecklist, $tituloEtapa, $conteudoEtapa, $numeroEtapa = 0, $fotoObrigatoria, $campoAdicional, $statusEtapa)
    {
        $this->setIdEtapaChecklist($idEtapaChecklist);
        $this->setFkTipoChecklist($fkTipoChecklist);
        $this->setTituloEtapa($tituloEtapa);
        $this->setConteudoEtapa($conteudoEtapa);
        $this->setNumeroEtapa($numeroEtapa);
        $this->setFotoObrigatoria($fotoObrigatoria);
        $this->setCampoAdicional($campoAdicional);
        $this->setStatusEtapa($statusEtapa);
    }

    function setIdEtapaChecklist($idEtapaChecklist)
    {
        $this->idEtapaChecklist = $idEtapaChecklist;
    }

    function setFkTipoChecklist($fkTipoChecklist)
    {
        $this->fkTipoChecklist = $fkTipoChecklist;
    }

    function setTituloEtapa($tituloEtapa)
    {
        $this->tituloEtapa = $tituloEtapa;
    }

    function setConteudoEtapa($conteudoEtapa)
    {
        $this->conteudoEtapa = $conteudoEtapa;
    }

    function setNumeroEtapa($numeroEtapa)
    {
        $this->numeroEtapa = $numeroEtapa;
    }

    function setFotoObrigatoria($fotoObrigatoria)
    {
        $this->fotoObrigatoria = $fotoObrigatoria;
    }

    function setCampoAdicional($campoAdicional)
    {
        $this->campoAdicional = $campoAdicional;
    }

    function setStatusEtapa($statusEtapa)
    {
        $this->statusEtapa = $statusEtapa;
    }

    //get

    function getIdEtapaChecklist()
    {
        return $this->idEtapaChecklist;
    }

    function getFkTipoChecklist()
    {
        return $this->fkTipoChecklist;
    }

    function getTituloEtapa()
    {
        return $this->tituloEtapa;
    }

    function getConteudoEtapa()
    {
        return $this->conteudoEtapa;
    }

    function getNumeroEtapa()
    {
        return $this->numeroEtapa;
    }

    function getFotoObrigatoria()
    {
        return $this->fotoObrigatoria;
    }

    function getCampoAdicional()
    {
        return $this->campoAdicional;
    }

    function getStatusEtapa()
    {
        return $this->statusEtapa;
    }
}

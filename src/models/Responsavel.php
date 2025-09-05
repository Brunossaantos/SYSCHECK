<?php

namespace models;

class Responsavel
{
    private $idResponsavel;
    private $nomeResponsavel;
    private $emailResponsavel;

    function __construct($idResponsavel, $nomeResponsavel, $emailResponsavel)
    {
        $this->setIdResponsavel($idResponsavel);
        $this->setNomeResponsavel($nomeResponsavel);
        $this->setEmailResponsavel($emailResponsavel);
    }

    //set

    function setIdResponsavel($idResponsavel)
    {
        $this->idResponsavel = $idResponsavel;
    }

    function setNomeResponsavel($nomeResponsavel)
    {
        $this->nomeResponsavel = $nomeResponsavel;
    }

    function setEmailResponsavel($emailResponsavel)
    {
        $this->emailResponsavel = $emailResponsavel;
    }

    //get

    function getIdResponsavel()
    {
        return $this->idResponsavel;
    }

    function getNomeResponsavel()
    {
        return $this->nomeResponsavel;
    }

    function getEmailResponsavel()
    {
        return $this->emailResponsavel;
    }
}

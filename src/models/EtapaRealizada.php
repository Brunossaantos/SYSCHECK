<?php

namespace models;

class EtapaRealizada{
    private $idEtapaRealizada;
    private $fkChecklist;
    private $fkEtapa;
    private $numeroEtapa;
    private $acao;
    private $observacao;

    function __construct($idEtapaRealizada, $fkCheklist, $fkEtapa, $numeroEtapa, $acao, $observacao){
        $this->setIdEtapaRealizada($idEtapaRealizada);
        $this->setFkChecklist($fkCheklist);
        $this->setFkEtapa($fkEtapa);
        $this->setNumeroEtapa($numeroEtapa);
        $this->setAcao($acao);
        $this->setObservacao($observacao);
    }

    //set
    function setIdEtapaRealizada($idEtapaRealizada){
        $this->idEtapaRealizada = $idEtapaRealizada;
    }

    function setFkChecklist($fkChecklist){
        $this->fkChecklist = $fkChecklist;
    }

    function setFkEtapa($fkEtapa){
        $this->fkEtapa = $fkEtapa;
    }

    function setNumeroEtapa($numeroEtapa){
        $this->numeroEtapa = $numeroEtapa;
    }

    function setAcao($acao){
        $this->acao = $acao;
    }

    function setObservacao($observacao){
        $this->observacao = $observacao;
    }

    //get
    function getIdEtapa(){
        return $this->idEtapaRealizada;
    }

    function getFkChecklist(){
        return $this->fkChecklist;
    }

    function getFkEtapa(){
        return $this->fkEtapa;
    }

    function getNumeroEtapa(){
        return $this->numeroEtapa;
    }

    function getAcao(){
        return $this->acao;
    }

    function getObservacao(){
        return $this->observacao;
    }
}


?>
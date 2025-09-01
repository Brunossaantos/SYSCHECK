<?php

namespace models;

class Local{
    private $idLocal;
    private $descricaoLocal;
    private $statusLocal;

    public function __construct($idLocal, $descricaoLocal, $statusLocal){
        $this->setIdLocal($idLocal);
        $this->setDescricaoLocal($descricaoLocal);
        $this->setStatusLocal($statusLocal);
    }

    public function setIdLocal($idLocal){
        $this->idLocal = $idLocal;
    }

    public function getIdLocal(){
        return $this->idLocal;
    }

    public function setDescricaoLocal($descricaoLocal){
        $this->descricaoLocal = $descricaoLocal;
    }

    public function getDescricaoLocal(){
        return $this->descricaoLocal;
    }

    public function setStatusLocal($statusLocal){
        $this->statusLocal = $statusLocal;
    }

    public function getStatusLocal(){
        return $this->statusLocal;
    }

    public function __toString(){
        return $this->descricaoLocal;
    }

    public function toArray(){
        return [
            'idLocal' => $this->idLocal,
            'descricaoLocal' => $this->descricaoLocal,
            'statusLocal' => $this->statusLocal
        ];
    }
}
?>
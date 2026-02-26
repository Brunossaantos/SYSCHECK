<?php
namespace models;

class Perfil
{
    private $idPerfil;
    private $nome;

    public function __construct($idPerfil, $nome)
    {
        $this->idPerfil = $idPerfil;
        $this->nome = $nome;
    }

    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    public function getNome()
    {
        return $this->nome;
    }
}
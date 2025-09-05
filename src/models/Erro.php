<?php

namespace models;

class Erro
{
    private $idErro;
    private $erro;
    private $arquivo;
    private $linha;
    private $local;
    private $dataHora;
    private $fkUsuario;

    function __construct($idErro, $erro, $arquivo, $linha, $local, $dataHora, $fkUsuario)
    {
        $this->setIdErro($idErro);
        $this->setErro($erro);
        $this->setArquivo($arquivo);
        $this->setLinha($linha);
        $this->setLocal($local);
        $this->setDataHora($dataHora);
        $this->setFkUsuario($fkUsuario);
    }

    function setIdErro($idErro)
    {
        $this->idErro = $idErro;
    }

    function setErro($erro)
    {
        $this->erro = $erro;
    }

    function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    function setLinha($linha)
    {
        $this->linha = $linha;
    }

    function setLocal($local)
    {
        $this->local = $local;
    }

    function setDataHora($dataHora)
    {
        $this->dataHora = $dataHora;
    }

    function setFkUsuario($fkUsuario)
    {
        $this->fkUsuario = $fkUsuario;
    }

    function getIdErro()
    {
        return $this->idErro;
    }

    function getErro()
    {
        return $this->erro;
    }

    function getArquivo()
    {
        return $this->arquivo;
    }

    function getLInha()
    {
        return $this->linha;
    }

    function getLocal()
    {
        return $this->local;
    }

    function getDataHora()
    {
        return $this->dataHora;
    }

    function getFkUsuario()
    {
        return $this->fkUsuario;
    }
}

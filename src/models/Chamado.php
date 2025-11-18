<?php

namespace models;

require_once __DIR__ . '/../database/Conexao.php';

use database\Conexao;

class Chamado
{
    private $idChamado;
    private $fkItemChamado;
    private $descricaoChamado;
    private $dataAberturaChamado;
    private $dataFinalizacaoChamado;
    private $fkUsuario;
    private $statusChamado;

    // novas propriedades para exibição
    private $nomeEquipamento;
    private $nomeUsuario;

    public function __construct(
        $idChamado = 0,
        $fkItemChamado = null,
        $descricaoChamado = null,
        $dataAberturaChamado = null,
        $dataFinalizacaoChamado = null,
        $fkUsuario = null,
        $statusChamado = null
    ) {
        $this->setIdChamado($idChamado);
        $this->setFkItemChamado($fkItemChamado);
        $this->setDescricaoChamado($descricaoChamado);
        $this->setDataAberturaChamado($dataAberturaChamado);
        $this->setDataFinalizacaoChamado($dataFinalizacaoChamado);
        $this->setFkUsuario($fkUsuario);
        $this->setStatusChamado($statusChamado);
    }

    // alterarStatus (mantém seu código já validado)
    public function alterarStatus($idChamado, $novoStatus)
    {
        $con = (new Conexao())->conectar();

        $sql = "UPDATE tbl_chamados 
                SET STATUS_CHAMADO = ? 
                WHERE ID_CHAMADO = ?";

        $stmt = $con->prepare($sql);

        if (!$stmt) {
            die("Erro no prepare: " . $con->error);
        }

        $stmt->bind_param("ii", $novoStatus, $idChamado);

        if (!$stmt->execute()) {
            die("Erro ao executar: " . $stmt->error);
        }

        return true;
    }

    // ------------------ SETTERS ------------------
    public function setIdChamado($idChamado)
    {
        $this->idChamado = $idChamado;
    }
    public function setFkItemChamado($fkItemChamado)
    {
        $this->fkItemChamado = $fkItemChamado;
    }
    public function setDescricaoChamado($descricaoChamado)
    {
        $this->descricaoChamado = $descricaoChamado;
    }
    public function setDataAberturaChamado($dataAberturaChamado)
    {
        $this->dataAberturaChamado = $dataAberturaChamado;
    }
    public function setDataFinalizacaoChamado($dataFinalizacaoChamado)
    {
        $this->dataFinalizacaoChamado = $dataFinalizacaoChamado;
    }
    public function setFkUsuario($fkUsuario)
    {
        $this->fkUsuario = $fkUsuario;
    }
    public function setStatusChamado($statusChamado)
    {
        $this->statusChamado = $statusChamado;
    }

    // setters novos
    public function setNomeEquipamento($nome)
    {
        $this->nomeEquipamento = $nome;
    }
    public function setNomeUsuario($nome)
    {
        $this->nomeUsuario = $nome;
    }

    // ------------------ GETTERS ------------------
    public function getIdChamado()
    {
        return $this->idChamado;
    }
    public function getFkItemChamado()
    {
        return $this->fkItemChamado;
    }
    public function getDescricaoChamado()
    {
        return $this->descricaoChamado;
    }
    public function getDataAberturaChamado()
    {
        return $this->dataAberturaChamado;
    }
    public function getDataFinalizacaoChamado()
    {
        return $this->dataFinalizacaoChamado;
    }
    public function getFkUsuario()
    {
        return $this->fkUsuario;
    }
    public function getStatusChamado()
    {
        return $this->statusChamado;
    }

    // getters novos
    public function getNomeEquipamento()
    {
        return $this->nomeEquipamento;
    }
    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }
}

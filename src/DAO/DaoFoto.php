<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Foto;
use Exception;
use Util\Util;

class DaoFoto
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_foto = TBL_FOTOS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirFoto(Foto $foto)
    {
        $fkChecklist = $foto->getFkChecklist();
        $numeroEtapa = $foto->getNumeroEtapa();
        $caminhoFoto = $foto->getCaminhoFoto();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_foto} (FK_CHECKLIST, NUMERO_ETAPA, CAMINHO_IMAGEM) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $fkChecklist, $numeroEtapa, $caminhoFoto);

            $stmt->execute();

            $idFotoAdicionada = $stmt->insert_id;

            if ($idFotoAdicionada > 0) {
                return $idFotoAdicionada;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirFoto", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarFoto(int $fkChecklist, int $numeroEtapa)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_FOTO, FK_CHECKLIST, NUMERO_ETAPA, CAMINHO_IMAGEM FROM {$this->tbl_foto} WHERE FK_CHECKLIST = ? AND NUMERO_ETAPA = ?");
            $stmt->bind_param("ii", $fkChecklist, $numeroEtapa);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Foto($row['ID_FOTO'], $row['FK_CHECKLIST'], $row['NUMERO_ETAPA'], $row['CAMINHO_IMAGEM']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarFoto", $this->idUsuarioSessao);
            return null;
        }
    }

    function listaFotosChecklist(int $idChecklist)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_FOTO, FK_CHECKLIST, NUMERO_ETAPA, CAMINHO_IMAGEM FROM {$this->tbl_foto} WHERE FK_CHECKLIST = ?");
            $stmt->bind_param("i", $idChecklist);
            $stmt->execute();
            $result = $stmt->get_result();
            $listaFotos = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $foto = new Foto($row['ID_FOTO'], $row['FK_CHECKLIST'], $row['NUMERO_ETAPA'], $row['CAMINHO_IMAGEM']);
                    $listaFotos[] = $foto;
                }
            }

            return $listaFotos;
        } catch (Exception $e) {
            Util::inserirErro($e, "listaFotosChecklist", $this->idUsuarioSessao);
            return null;
        }
    }

    function selecionarFotoEtapa($fkChecklist, $numeroEtapa)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_FOTO, FK_CHECKLIST, NUMERO_ETAPA, CAMINHO_IMAGEM FROM {$this->tbl_foto} WHERE FK_CHECKLIST = ? AND NUMERO_ETAPA = ?");
            $stmt->bind_param("ii", $fkChecklist, $numeroEtapa);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Foto($row['ID_FOTO'], $row['FK_CHECKLIST'], $row['NUMERO_ETAPA'], $row['CAMINHO_IMAGEM']);
            }

            return "";
        } catch (Exception $e) {
            Util::inserirErro($e, "retornarFoto", $this->idUsuarioSessao);
            return "";
        }
    }

    function listarFotosPorEtapa($fkChecklist, $numeroEtapa)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_FOTO, FK_CHECKLIST, NUMERO_ETAPA, CAMINHO_IMAGEM FROM {$this->tbl_foto} WHERE FK_CHECKLIST = ? AND NUMERO_ETAPA = ?");
            $stmt->bind_param("ii", $fkChecklist, $numeroEtapa);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $listaFotos = [];
                while ($row = $result->fetch_assoc()) {
                    $foto = new Foto($row['ID_FOTO'], $row['FK_CHECKLIST'], $row['NUMERO_ETAPA'], $row['CAMINHO_IMAGEM']);
                    $listaFotos[] = $foto;
                }
                return $listaFotos;
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "listarFotosPorEtapa", $this->idUsuarioSessao);
            return [];
        }
    }
}

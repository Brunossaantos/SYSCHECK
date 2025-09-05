<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\PerifericoBateria;
use Util\Util;

class DaoPerifericoBateria
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_perifericos_baterias = TBL_PERIFERICOS_BATERIAS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirPeriferico(PerifericoBateria $perifericoBateria)
    {
        $tipoPeriferico = $perifericoBateria->getTipoPeriferico();
        $descricao = $perifericoBateria->getDescricaoPeriferico();
        $status = $perifericoBateria->getDescricaoPeriferico();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_perifericos_baterias} (TIPO_PERIFERICO, DESCRICAO_PERIFERICO, STATUS_PERIFERICO) VALUES (?,?,?)");
            $stmt->bind_param("isi", $tipoPeriferico, $descricao, $status);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirPeriferico", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarPeriferico($idPeriferico)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_PERIFERICO, TIPO_PERIFERICO, DESCRICAO_PERIFERICO, STATUS_PERIFERICO FROM {$this->tbl_perifericos_baterias} WHERE ID_PERIFERICO = ?");
            $stmt->bind_param("i", $idPeriferico);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new PerifericoBateria($row['ID_PERIFERICO'], $row['TIPO_PERIFERICO'], $row['DESCRICAO_PERIFERICO'], $row['STATUS_PERIFERICO']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarPeriferico", $this->idUsuarioSessao);
            return null;
        }
    }

    function listaPerifericos()
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_PERIFERICO, TIPO_PERIFERICO, DESCRICAO_PERIFERICO, STATUS_PERIFERICO FROM {$this->tbl_perifericos_baterias}");
            $stmt->execute();
            $result = $stmt->get_result();
            $listaPerifericos = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $periferico = new PerifericoBateria($row['ID_PERIFERICO'], $row['TIPO_PERIFERICO'], $row['DESCRICAO_PERIFERICO'], $row['STATUS_PERIFERICO']);
                    $listaPerifericos[] = $periferico;
                }
            }

            return $listaPerifericos;
        } catch (Exception $e) {
            Util::inserirErro($e, "listaPerifericos", $this->idUsuarioSessao);
            return null;
        }
    }
}

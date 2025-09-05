<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Responsavel;
use database\Conexao;
use Util\Util;

class DaoResponsavel
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_responsaveis = TBL_RESPONSAVEIS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function selecionarResponsavel(int $fkResponsavel)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_RESPONSAVEL, NOME_RESPONSAVEL, EMAIL_RESPONSAVEL FROM {$this->tbl_responsaveis} WHERE ID_RESPONSAVEL = ?");
            $stmt->bind_param("i", $fkResponsavel);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Responsavel($row['ID_RESPONSAVEL'], $row['NOME_RESPONSAVEL'], $row['EMAIL_RESPONSAVEL']);
            }
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarResponsavel", $this->idUsuarioSessao);
            return null;
        }
    }

    function gerarListaResponsaveis()
    {
        try {
            $listaResponsaveis = [];
            $stmt = $this->conexao->prepare("SELECT ID_RESPONSAVEL, NOME_RESPONSAVEL, EMAIL_RESPONSAVEL FROM {$this->tbl_responsaveis}");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $responsavel = new Responsavel($row['ID_RESPONSAVEL'], $row['NOME_RESPONSAVEL'], $row['EMAIL_RESPONSAVEL']);
                    $listaResponsaveis[] = $responsavel;
                }
            }

            return $listaResponsaveis;
        } catch (Exception $e) {
            Util::inserirErro($e, "gerarListaResponsaveis", $this->idUsuarioSessao);
            return null;
        }
    }
}

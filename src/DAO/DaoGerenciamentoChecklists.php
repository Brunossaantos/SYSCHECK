<?php

namespace DAO;

use DateTime;
use Exception;
use Util\Util;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../constantes/constTabelasdb.php';

class DaoGerenciamentoChecklists
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_log_horimetro = TBL_LOG_FINALIZACAO_HORIMETRO;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function salvarLogFinalizacaoHorimetro($logHorimetro)
    {
        try {

            $checklist = $logHorimetro['checklist'];
            $empilhadeira = $logHorimetro['empilhadeira'];
            $lider = $logHorimetro['lider'];
            $data = (new DateTime())->format('d/m/Y H:i:s');

            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_log_horimetro} (FK_CHECKLIST, FK_EMPILHADEIRA, FK_LIDER, DATA_FINALIZACAO_HORIMETRO) VALUES (?,?,?,?)");
            $stmt->bind_param("iiis", $checklist, $empilhadeira, $lider, $data);

            $stmt->execute();

            return $stmt->insert_id;
        } catch (Exception $e) {
            Util::inserirErro($e, "salvarLogFinalizacaoHorimetro", $this->idUsuarioSessao);
            return -2;
        }
    }
}

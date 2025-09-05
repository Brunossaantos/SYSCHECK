<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\EtapaRealizada;
use Exception;
use Util\Util;

class DaoEtapaRealizada
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_etapas_realizadas = TBL_ETAPAS_REALIZADAS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirEtapaRealizada(EtapaRealizada $etapaRealizada)
    {
        $fkEtapa = $etapaRealizada->getFkEtapa();
        $fkChecklist = $etapaRealizada->getFkChecklist();
        $numeroEtapa = $etapaRealizada->getNumeroEtapa();
        $acao = $etapaRealizada->getAcao();
        $observacao = $etapaRealizada->getObservacao();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_etapas_realizadas} (FK_CHECKLIST, FK_ETAPA, NUMERO_ETAPA, ACAO, OBSERVACAO) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiis", $fkChecklist, $fkEtapa, $numeroEtapa, $acao, $observacao);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirEtapaRealizada", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarEtapaRealizada($idEtapaRealizada)
    {
        try {
            $stmt = $this->conexao->preapare("SELECT ID_ETAPA_REALIZADA, FK_CHECKLIST, FK_ETAPA, NUMERO_ETAPA, ACAO, OBSERVACAO FROM {$this->tbl_etapas_realizadas} WHERE ID_ETAPA_REALIZADA = ?");
            $stmt->bind_param("i", $idEtapaRealizada);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new EtapaRealizada($row['ID_ETAPA_REALIZADA'], $row['FK_CHECKLIST'], $row['FK_ETAPA'], $row['NUMERO_ETAPA'], $row['ACAO'], $row['OBSERVACAO']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarEtapaRealizada", $this->idUsuarioSessao);
            return null;
        }
    }

    function listaEtapasPorChecklist(int $idChecklist)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ER.FK_CHECKLIST AS NUMERO,  EC.TITULO_ETAPA AS TITULO, EC.CONTEUDO_ETAPA AS CONTEUDO, EC.NUMERO_ETAPA, ER.ACAO, ER.OBSERVACAO, ER.FK_ETAPA
                                            FROM TBL_ETAPAS_REALIZADAS ER
                                            INNER JOIN TBL_ETAPAS_CHECKLISTS EC ON ER.FK_ETAPA = EC.ID_ETAPA_CHECKLIST
                                            WHERE FK_CHECKLIST = ? ORDER BY NUMERO_ETAPA ASC");

            $stmt->bind_param("i", $idChecklist);
            $stmt->execute();

            $listaEtapasRealizadas = [];

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $listaEtapasRealizadas[] = [
                        'NUMERO' => $row['NUMERO'],
                        'TITULO' => $row['TITULO'],
                        'CONTEUDO' => $row['CONTEUDO'],
                        'NUMERO_ETAPA' => $row['NUMERO_ETAPA'],
                        'ACAO' => $row['ACAO'],
                        'OBSERVACAO' => $row['OBSERVACAO'],
                        'FK_ETAPA' => $row['FK_ETAPA']
                    ];
                }
            }

            return $listaEtapasRealizadas;
        } catch (Exception $e) {
            util::inserirErro($e, "listaEtapasPorChecklist", $this->idUsuarioSessao);
            return null;
        }
    }

    function verificarUltimaEtapa($idChecklist)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_ETAPA_REALIZADA, FK_CHECKLIST, FK_ETAPA, NUMERO_ETAPA, ACAO, OBSERVACAO FROM {$this->tbl_etapas_realizadas} WHERE FK_CHECKLIST = ? ORDER by NUMERO_ETAPA DESC LIMIT 1");
            $stmt->bind_param("i", $idChecklist);

            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new EtapaRealizada($row['ID_ETAPA_REALIZADA'], $row['FK_CHECKLIST'], $row['FK_ETAPA'], $row['NUMERO_ETAPA'], $row['ACAO'], $row['OBSERVACAO']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarUltimaEtapa", $this->idUsuarioSessao);
            return null;
        }
    }
}

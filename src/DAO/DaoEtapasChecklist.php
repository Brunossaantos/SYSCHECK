<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\EtapasChecklist;
use Exception;
use Util\Util;

class DaoEtapasChecklist
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_etapas_checklists = TBL_ETAPAS_CHECKLIST;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirEtapaChecklist(EtapasChecklist $etapa)
    {
        $fkTipoChecklist = $etapa->getFkTipoChecklist();
        $titulo = $etapa->getTituloEtapa();
        $conteudoEtapa = $etapa->getConteudoEtapa();
        $numeroEtapa = $etapa->getNumeroEtapa();
        $fotoObrigatoria = $etapa->getFotoObrigatoria(); // Corrigido aqui
        $campoAdicional = $etapa->getCampoAdicional();
        $status = $etapa->getStatusEtapa();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_etapas_checklists} (FK_TIPO_CHECKLIST,
                                                                                        TITULO_ETAPA,
                                                                                        CONTEUDO_ETAPA,
                                                                                        NUMERO_ETAPA,
                                                                                        FOTO_OBRIGATORIA,
                                                                                        CAMPO_ADICIONAL,
                                                                                        STATUS_ETAPA) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issiiii", $fkTipoChecklist, $titulo, $conteudoEtapa, $numeroEtapa, $fotoObrigatoria, $campoAdicional, $status);
            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirEtapaChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }


    function selecionarEtapaChecklist($fkTipoChecklist, $numeroEtapa)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_ETAPA_CHECKLIST,
                                                    FK_TIPO_CHECKLIST,
                                                    TITULO_ETAPA,
                                                    CONTEUDO_ETAPA,
                                                    NUMERO_ETAPA,
                                                    FOTO_OBRIGATORIA,
                                                    CAMPO_ADICIONAL,
                                                    STATUS_ETAPA FROM {$this->tbl_etapas_checklists} WHERE FK_TIPO_CHECKLIST = ? AND NUMERO_ETAPA = ?");
            $stmt->bind_param("ii", $fkTipoChecklist, $numeroEtapa);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new EtapasChecklist($row['ID_ETAPA_CHECKLIST'], $row['FK_TIPO_CHECKLIST'], $row['TITULO_ETAPA'], $row['CONTEUDO_ETAPA'], $row['NUMERO_ETAPA'], $row['FOTO_OBRIGATORIA'], $row['CAMPO_ADICIONAL'], $row['STATUS_ETAPA']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarEtapaChecklist", $this->idUsuarioSessao);
            return null;
        }
    }


    function selecionarEtapaPeloId($idChecklist)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_ETAPA_CHECKLIST,
                                                    FK_TIPO_CHECKLIST,
                                                    TITULO_ETAPA,
                                                    CONTEUDO_ETAPA,
                                                    NUMERO_ETAPA,
                                                    FOTO_OBRIGATORIA,
                                                    CAMPO_ADICIONAL,
                                                    STATUS_ETAPA FROM {$this->tbl_etapas_checklists} WHERE ID_ETAPA_CHECKLIST = ?");
            $stmt->bind_param("i", $idChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new EtapasChecklist($row['ID_ETAPA_CHECKLIST'], $row['FK_TIPO_CHECKLIST'], $row['TITULO_ETAPA'], $row['CONTEUDO_ETAPA'], $row['NUMERO_ETAPA'], $row['FOTO_OBRIGATORIA'], $row['CAMPO_ADICIONAL'], $row['STATUS_ETAPA']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarEtapaPeloId", $this->idUsuarioSessao);
            return null;
        }
    }

    function alterarEtapaChecklist(EtapasChecklist $etapa)
    {
        $idEtapa = $etapa->getIdEtapaChecklist();
        $titulo = $etapa->getTituloEtapa();
        $conteudo = $etapa->getConteudoEtapa();
        $numero = $etapa->getNumeroEtapa();
        $fotoObrigadotoria = $etapa->getFotoObrigatoria();
        $campoAdicional = $etapa->getCampoAdicional();
        $status = $etapa->getStatusEtapa();

        try {
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_etapas_checklists} SET TITULO_ETAPA = ?,
                                                                                       CONTEUDO_ETAPA = ?,
                                                                                       NUMERO_ETAPA = ?,
                                                                                       FOTO_OBRIGATORIA = ?,
                                                                                       CAMPO_ADICIONAL = ?,
                                                                                       STATUS_ETAPA = ? WHERE ID_ETAPA_CHECKLIST = ?");
            $stmt->bind_param("ssiiiii", $titulo, $conteudo, $numero, $fotoObrigadotoria, $campoAdicional, $status, $idEtapa);
            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarEtapaChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    function organizarNumeroEtapas($fkTipoChecklist)
    {
        try {

            $listaetapas = [];

            $stmt = $this->conexao->prepare("SELECT ID_ETAPA_CHECKLIST,
                                                    FK_TIPO_CHECKLIST,
                                                    TITULO_ETAPA,
                                                    CONTEUDO_ETAPA,
                                                    NUMERO_ETAPA,
                                                    FOTO_OBRIGATORIA,
                                                    CAMPO_ADICIONAL,
                                                    STATUS_ETAPA FROM {$this->tbl_etapas_checklists} WHERE FK_TIPO_CHECKLIST = ? ORDER By ID_ETAPA_CHECKLIST ASC");

            $stmt->bind_param("i", $fkTipoChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $etapa = new EtapasChecklist($row['ID_ETAPA_CHECKLIST'], $row['FK_TIPO_CHECKLIST'], $row['TITULO_ETAPA'], $row['CONTEUDO_ETAPA'], $row['NUMERO_ETAPA'], $row['FOTO_OBRIGATORIA'], $row['CAMPO_ADICIONAL'], $row['STATUS_ETAPA']);
                    $listaetapas[] = $etapa;
                }
            }

            //echo "Ante das organização das etapas<pre>";
            //var_dump($listaetapas);

            $numeroEtapa = 1;

            if (!empty($listaetapas)) {
                foreach ($listaetapas as $etapa) {
                    if ($etapa->getStatusEtapa() == 1) {
                        $idEtapa = $etapa->getIdEtapaChecklist();
                        $etapa->setNumeroEtapa($numeroEtapa);
                        $stmt = $this->conexao->prepare("UPDATE {$this->tbl_etapas_checklists} SET NUMERO_ETAPA = ? WHERE ID_ETAPA_CHECKLIST = ?");
                        $stmt->bind_param("ii", $numeroEtapa, $idEtapa);
                        $stmt->execute();

                        $numeroEtapa++;
                    }
                }

                foreach ($listaetapas as $etapa) {
                    if ($etapa->getStatusEtapa() == 0) {
                        $idEtapa = $etapa->getIdEtapaChecklist();
                        $etapa->setNumeroEtapa($numeroEtapa);
                        $stmt = $this->conexao->prepare("UPDATE {$this->tbl_etapas_checklists} SET NUMERO_ETAPA = ? WHERE ID_ETAPA_CHECKLIST = ?");
                        $stmt->bind_param("ii", $numeroEtapa, $idEtapa);
                        $stmt->execute();

                        $numeroEtapa++;
                    }
                }
            }

            //echo "Após a organização das etapas<pre>";
            //var_dump($listaetapas);  

            return true;
        } catch (Exception $e) {
            Util::inserirErro($e, "organizarNumeroEtapas", $this->idUsuarioSessao);
            return false;
        }
    }

    function retornarListaEtapas($fkTipoChecklist)
    {
        $listaDeEtapas = [];
        try {
            $stmt = $this->conexao->prepare("SELECT ID_ETAPA_CHECKLIST,
                                                    FK_TIPO_CHECKLIST,
                                                    TITULO_ETAPA,
                                                    CONTEUDO_ETAPA,
                                                    NUMERO_ETAPA,
                                                    FOTO_OBRIGATORIA,
                                                    CAMPO_ADICIONAL,
                                                    STATUS_ETAPA FROM {$this->tbl_etapas_checklists} WHERE FK_TIPO_CHECKLIST = ? ORDER By NUMERO_ETAPA ASC");

            $stmt->bind_param("i", $fkTipoChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $etapa = new EtapasChecklist($row['ID_ETAPA_CHECKLIST'], $row['FK_TIPO_CHECKLIST'], $row['TITULO_ETAPA'], $row['CONTEUDO_ETAPA'], $row['NUMERO_ETAPA'], $row['FOTO_OBRIGATORIA'], $row['CAMPO_ADICIONAL'], $row['STATUS_ETAPA']);
                    $listaDeEtapas[] = $etapa;
                }
            }

            return $listaDeEtapas;
        } catch (Exception $e) {
            Util::inserirErro($e, "retornarListaEtapas", $this->idUsuarioSessao);
            return null;
        }
    }

    function quantidadeEtapas($fkTipoChecklist)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT COUNT(FK_TIPO_CHECKLIST) AS QTDETAPAS FROM {$this->tbl_etapas_checklists} WHERE FK_TIPO_CHECKLIST = ?");
            $stmt->bind_param("i", $fkTipoChecklist);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['QTDETAPAS'];
            }

            return 0;
        } catch (Exception $e) {
            Util::inserirErro($e, "quantidadeEtapas", $this->idUsuarioSessao);
            return -2;
        }
    }
}

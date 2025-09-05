<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Checklist;
use database\Conexao;
use Exception;
use Util\Util;
use \DateTime;


class DaoChecklist
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_checklists = TBL_CHECKLISTS;
    private $view_checklists = V_CHECKLIS_VISAO_GERAL;
    private $view_checklists_horimetros = V_CHECKLISTS_HORIMETRO;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function iniciarCheckList(Checklist $checklist)
    {
        $fkUsuario = $checklist->getFkUsuario();
        $fkTipo = $checklist->getFkTipo();
        $fkObjeto = $checklist->getFkObjeto();
        $dataInicio = $checklist->getDataInicio();
        $status = $checklist->getStatusChecklist();

        try {
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_checklists} (FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, STATUS_CHECKLIST) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisi", $fkUsuario, $fkTipo, $fkObjeto, $dataInicio, $status);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inicarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarChecklist($idChecklist)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_CHECKLIST, FK_USUARIO, FK_TIPO, FK_OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST FROM {$this->tbl_checklists} WHERE ID_CHECKLIST = ?");
            $stmt->bind_param("i", $idChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Checklist($row['ID_CHECKLIST'], $row['FK_USUARIO'], $row['FK_TIPO'], $row['FK_OBJETO'], $row['DATA_INICIO'], $row['DATA_FIM'], $row['STATUS_CHECKLIST']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarCHecklist", $this->idUsuarioSessao);
            return null;
        }
    }

    function atualizarChecklist(Checklist $checklist)
    {
        $idChecklist = $checklist->getIdChecklist();
        $dataFim = $checklist->getDataFim();
        $status = $checklist->getStatusChecklist();

        try {
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_checklists} SET DATA_FIM = ?, STATUS_CHECKLIST = ? WHERE ID_CHECKLIST = ?");
            $stmt->bind_param("sii", $dataFim, $status, $idChecklist);

            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "atualizarChecklist", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listaChecklists()
    {
        try {
            $listaChecklists = [];
            $stmt = $this->conexao->prepare("SELECT * FROM {$this->view_checklists} ORDER BY NUMERO_CHECKLIST DESC");

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $checklist = new Checklist($row['NUMERO_CHECKLIST'], $row['USUARIO'], $row['TIPO'], $row['OBJETO'], $row['DATA_INICIO'], $row['DATA_FIM'], $row['STATUS_CHECKLIST']);
                    $listaChecklists[] = $checklist;
                }
            }

            return $listaChecklists;
        } catch (Exception $e) {
            util::inserirErro($e, "listaChecklists", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listarChecklistVeicular()
    {
        try {
            $listaChecklists = [];

            $stmt = $this->conexao->prepare("SELECT 
                                                CHECKLISTS.ID_CHECKLIST AS NUMERO_CHECKLIST,
                                                USUARIO.NOME AS USUARIO, 
                                                VEICULO.DESCRICAO_OBJETO AS OBJETO, 
                                                USO.DATA_HORA AS DATA_INICIO, 
                                                USO.DATA_HORA_DEVOLUCAO AS DATA_FIM, 
                                                USO.STATUS_USO, 
                                                CHECKLISTS.STATUS_CHECKLIST,
                                                CHECKLISTS.FK_TIPO AS TIPO
                                            FROM TBL_LISTA_USO_VEICULO USO
                                            INNER JOIN TBL_CHECKLISTS CHECKLISTS ON USO.FK_USUARIO = CHECKLISTS.FK_USUARIO 
                                            AND USO.FK_VEICULO = CHECKLISTS.FK_OBJETO 
                                            AND USO.DATA_HORA = CHECKLISTS.DATA_INICIO
                                            INNER JOIN TBL_USUARIOS USUARIO ON USO.FK_USUARIO = USUARIO.ID_USUARIO
                                            INNER JOIN TBL_OBJETOS VEICULO ON USO.FK_VEICULO = VEICULO.ID_OBJETO ORDER By NUMERO_CHECKLIST DESC LIMIT 10");

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $checklist = new Checklist($row['NUMERO_CHECKLIST'], $row['USUARIO'], $row['TIPO'], $row['OBJETO'], $row['DATA_INICIO'], $row['DATA_FIM'], $row['STATUS_CHECKLIST']);
                    $listaChecklists[] = $checklist;
                }
            }

            return $listaChecklists;
        } catch (Exception $e) {
            util::inserirErro($e, "listaChecklistVeicula", $this->idUsuarioSessao);
            return [];
        }
    }


    function verificarChecklistPorUsuario($fkUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT * FROM {$this->tbl_checklists} WHERE FK_USUARIO = ? AND STATUS_CHECKLIST = 1 ORDER BY ID_CHECKLIST DESC LIMIT 1");
            $stmt->bind_param("i", $fkUsuario);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Checklist($row['ID_CHECKLIST'], $row['FK_USUARIO'], $row['FK_TIPO'], $row['FK_OBJETO'], $row['DATA_INICIO'], $row['DATA_FIM'], $row['STATUS_CHECKLIST']);
            }

            return null;
        } catch (Exception $e) {
            util::inserirErro($e, "verificarCheklistPorUsuario", $this->idUsuarioSessao);
            return null;
        }
    }

    function verificarChecklistPendente($fkUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT COUNT(ID_CHECKLIST) AS QTD_CHECKLIST_PENDENTE FROM tbl_checklists WHERE FK_USUARIO = ? AND STATUS_CHECKLIST = 1");
            $stmt->bind_param("i", $fkUsuario);
            $qtdChecklistPendente = -1;

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $qtdChecklistPendente = $row['QTD_CHECKLIST_PENDENTE'];
            }

            return $qtdChecklistPendente;
        } catch (Exception $e) {
            Util::inserirErro($e, "verificarChecklistPendente", $this->idUsuarioSessao);
            return null;
        }
    }


    function recuperarHorimetrosPorChecklist($fkUsuario)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_CHECKLIST,
                                                    FK_USUARIO,
                                                    FK_TIPO,
                                                    FK_OBJETO,
                                                    DATA_INICIO,
                                                    DATA_FIM,
                                                    STATUS_CHECKLIST,
                                                    HORIMETRO_INICIAL,
                                                    HORIMETRO_FINAL FROM {$this->view_checklists_horimetros} WHERE FK_USUARIO = ?");

            $stmt->bind_param("i", $fkUsuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $listaChecklists[] = [
                        'idChecklist' => $row['ID_CHECKLIST'],
                        'usuario'  => $row['FK_USUARIO'],
                        'tipo'  => $row['FK_TIPO'],
                        'empilhadeira'  => $row['FK_OBJETO'],
                        'dataInicio'  => $row['DATA_INICIO'],
                        'dataFim'  => $row['DATA_FIM'],
                        'status'  => $row['STATUS_CHECKLIST'],
                        'horimetroInicial'  => $row['HORIMETRO_INICIAL'],
                        'horimetroFinal'  => $row['HORIMETRO_FINAL'],
                    ];
                }

                return $listaChecklists;
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "recuperarListaChecklistPorUsuario", $this->idUsuarioSessao);
            return [];
        }
    }


    function filtrarChecklists($filtros)
    {
        try {
            $sql = "SELECT NUMERO_CHECKLIST, USUARIO, TIPO, OBJETO, DATA_INICIO, DATA_FIM, STATUS_CHECKLIST 
                FROM v_checklist_visao_geral
                WHERE 1=1";

            $params = [];
            $types = "";

            // Número do checklist
            if (!empty($filtros['numero'])) {
                $sql .= " AND NUMERO_CHECKLIST = ?";
                $params[] = $filtros['numero'];
                $types .= "i"; // número é inteiro
            }

            // Data início
            if (!empty($filtros['data_inicio'])) {
                // A data que o usuário selecionou vem em Y-m-d do input date
                $dataFiltro = \DateTime::createFromFormat('Y-m-d', $filtros['data_inicio']);
                if ($dataFiltro) {
                    $dataFiltroStr = $dataFiltro->format('d/m/y'); // Formata igual ao seu varchar
                    $sql .= " AND DATA_INICIO LIKE ?";
                    $params[] = $dataFiltroStr . "%"; // o % pega qualquer hora
                    $types .= "s";
                }
            }


            // Tipo checklist (string)
            if (!empty($filtros['tipo']) && $filtros['tipo'] != 0) {
                $sql .= " AND TIPO = ?";
                $params[] = $filtros['tipo'];
                $types .= "s";
            }

            // Objeto (string)
            if (!empty($filtros['objeto']) && $filtros['objeto'] != 0) {
                $sql .= " AND OBJETO = ?";
                $params[] = $filtros['objeto'];
                $types .= "s";
            }

            // Usuário (string)
            if (!empty($filtros['usuario']) && $filtros['usuario'] != 0) {
                $sql .= " AND USUARIO = ?";
                $params[] = $filtros['usuario'];
                $types .= "s";
            }

            // Status (int)
            if (!empty($filtros['status']) && $filtros['status'] != 0) {
                $sql .= " AND STATUS_CHECKLIST = ?";
                $params[] = $filtros['status'];
                $types .= "i";
            }

            $sql .= " ORDER BY NUMERO_CHECKLIST DESC";

            $stmt = $this->conexao->prepare($sql);

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $checklists = [];
            while ($row = $result->fetch_assoc()) {
                $checklists[] = new Checklist(
                    $row['NUMERO_CHECKLIST'],
                    $row['USUARIO'],
                    $row['TIPO'],
                    $row['OBJETO'],
                    $row['DATA_INICIO'],
                    $row['DATA_FIM'],
                    $row['STATUS_CHECKLIST']
                );
            }

            return $checklists;
        } catch (Exception $e) {
            Util::inserirErro($e, "filtrarChecklists", $this->idUsuarioSessao);
            return [];
        }
    }




    /*function verificarUsuarioEmpilhadeira(int $idUsuario){
        try{
            $stmt = $this->conexao->prepare("SELECT ");

        } catch (Exception $e){
            Util::inserirErro($e, "verificarUsuarioEmpilhadeira", $this->idUsuarioSessao);
            return null;
        }
    }*/
}

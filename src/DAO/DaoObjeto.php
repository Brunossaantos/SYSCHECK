<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Objeto;
use database\Conexao;
use Util\Util;

class DaoObjeto
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_objetos = TBL_OBJETOS;
    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirObjeto(Objeto $objeto)
    {
        $descricao = $objeto->getDescricaoObjeto();
        $fkTipo = $objeto->getFkTipoChecklist();
        $status = $objeto->getStatusObjeto();
        $fkEmpresa = $objeto->getFkEmpresa(); // Certifique-se que o Model Objeto tem esse getter

        try {
            // Adicionada a coluna FK_EMPRESA e mais um placeholder "?"
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_objetos} (DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO, FK_EMPRESA) VALUES (?,?,?,?)");

            // Ajustado para "siii" (String, Int, Int, Int)
            $stmt->bind_param("siii", $descricao, $fkTipo, $status, $fkEmpresa);

            if ($stmt->execute()) {
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "inserirObjeto", $this->idUsuarioSessao);
            return -2;
        }
    }

    // Método extra para não precisar de DaoEmpresa
    public function listarEmpresasSimples()
    {
        $empresas = [];
        // Usamos query direta aqui para simplificar já que não há parâmetros externos
        $result = $this->conexao->query("SELECT id_empresa, nome FROM tbl_empresas WHERE ativa = 1");

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $empresas[] = $row;
            }
        }
        return $empresas;
    }
    function selecionarObjeto($idObjeto)
    {
        try {
            $stmt = $this->conexao->prepare("SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO, FK_EMPRESA FROM {$this->tbl_objetos} WHERE ID_OBJETO = ?");
            $stmt->bind_param("i", $idObjeto);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Objeto($row['ID_OBJETO'], $row['DESCRICAO_OBJETO'], $row['FK_TIPO_CHECKLIST'], $row['STATUS_OBJETO'], $row['FK_EMPRESA']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarObjeto", $this->idUsuarioSessao);
            return null;
        }
    }

    function alterarObjeto(Objeto $objeto)
    {
        $idObjeto = $objeto->getIdObjeto();
        $descricao = $objeto->getDescricaoObjeto();
        $fkTipo = $objeto->getFkTipoChecklist();
        $status = $objeto->getStatusObjeto();
        $fkEmpresa = $objeto->getFkEmpresa(); // ADICIONE ESTA LINHA AQUI!

        try {
            // 1. Adicionamos o campo FK_EMPRESA = ? no SET
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_objetos} SET DESCRICAO_OBJETO = ?, FK_TIPO_CHECKLIST = ?, STATUS_OBJETO = ?, FK_EMPRESA = ? WHERE ID_OBJETO = ?");

            // 2. Ajustamos o bind_param para "siiii" (String, Int, Int, Int, Int)
            $stmt->bind_param("siiii", $descricao, $fkTipo, $status, $fkEmpresa, $idObjeto);

            if ($stmt->execute()) {
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e) {
            Util::inserirErro($e, "alterarObjeto", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listarObjetosPeloTipo($fkTipo, $fkEmpresa)
    {
        $listaObjetos = [];
        try {
            $stmt = $this->conexao->prepare("
    SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO 
    FROM {$this->tbl_objetos} 
    WHERE FK_TIPO_CHECKLIST = ? 
    AND FK_EMPRESA = ?
        ");
            $stmt->bind_param("ii", $fkTipo, $fkEmpresa);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $objeto = new Objeto($row['ID_OBJETO'], $row['DESCRICAO_OBJETO'], $row['FK_TIPO_CHECKLIST'], $row['STATUS_OBJETO']);
                    $listaObjetos[] = $objeto;
                }
            }

            return $listaObjetos;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarObjetosPeloTIpo", $this->idUsuarioSessao);
            return null;
        }
    }

    function listarObjetos($fkEmpresa)
    {
        $listaObjetos = [];
        try {
            $sql = "SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO, FK_EMPRESA 
                FROM {$this->tbl_objetos}";

            // Só adiciona o WHERE se NÃO for acesso total (fkEmpresa não nulo)
            if ($fkEmpresa !== null) {
                $sql .= " WHERE FK_EMPRESA = ?";
            }

            $stmt = $this->conexao->prepare($sql);

            if ($fkEmpresa !== null) {
                $stmt->bind_param("i", $fkEmpresa);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $listaObjetos[] = new Objeto(
                    $row['ID_OBJETO'],
                    $row['DESCRICAO_OBJETO'],
                    $row['FK_TIPO_CHECKLIST'],
                    $row['STATUS_OBJETO'],
                    $row['FK_EMPRESA']
                );
            }
            return $listaObjetos;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarObjetos", $this->idUsuarioSessao);
            return [];
        }
    }

    public function listarObjetosAtivos($fkEmpresa)
    {
        $listaObjetos = [];
        try {
            $stmt = $this->conexao->prepare("
            SELECT ID_OBJETO, DESCRICAO_OBJETO, FK_TIPO_CHECKLIST, STATUS_OBJETO 
            FROM {$this->tbl_objetos}
            WHERE STATUS_OBJETO = 1
            AND FK_EMPRESA = ?
        ");

            $stmt->bind_param("i", $fkEmpresa);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $listaObjetos[] = new Objeto(
                    $row['ID_OBJETO'],
                    $row['DESCRICAO_OBJETO'],
                    $row['FK_TIPO_CHECKLIST'],
                    $row['STATUS_OBJETO']
                );
            }

            return $listaObjetos;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarObjetosAtivos", $this->idUsuarioSessao);
            return [];
        }
    }
}

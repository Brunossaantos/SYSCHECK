<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Chamado;
use Exception;
use Util\Util;
use DateTime;

class DaoChamado{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_chamados = TBL_CHAMADOS;
    private $tbl_fotos_chamados = TBL_FOTOS_CHAMADOS;
    private $tbl_follow_up_chamados = TBL_FOLLOW_UP_CHAMADOS;

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function salvarChamado(Chamado $chamado){
        $item = $chamado->getFkItemChamado();
        $descricao = $chamado->getDescricaoChamado();
        $dataAberturaChamado = $chamado->getDataAberturaChamado();
        $usuario = $chamado->getFkUsuario();
        $status = $chamado->getStatusChamado();

        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_chamados} (FK_ITEM_CHAMADO, DESCRICAO_CHAMADO, DATA_ABERTURA_CHAMADO, FK_USUARIO, STATUS_CHAMADO) VALUES (?,?,?,?,?)");
            $stmt->bind_param("issii", $item, $descricao, $dataAberturaChamado, $usuario, $status);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e){
            Util::inserirErro($e, "salvarChamado", $this->idUsuarioSessao);
            return null;
        }
    }

    function atualizarChamado(Chamado $chamado){
        $idChamado = $chamado->getIdChamado();
        $item = $chamado->getFkItemChamado();
        $descricao = $chamado->getDescricaoChamado();
        $dataAberturaChamado = $chamado->getDataAberturaChamado();
        $dataFinalizacaoChamado = $chamado->getDataFinalizacaoChamado();
        $usuario = $chamado->getFkUsuario();
        $status = $chamado->getStatusChamado();

        try{
            $stmt = $this->conexao->prepare("UPDATE {$this->tbl_chamados} SET DATA_FINALIZACAO_CHAMADO = ?, STATUS = ? WHERE ID_CHAMADO = ?");
            $stmt->bind_param("sii", $dataFinalizacaoChamado, $status, $idChamado);

            if($stmt->execute()){
                return $stmt->affected_rows;
            }

            return -1;
        } catch (Exception $e){
            Util::inserirErro($e, "atualizarChamado", $this->idUsuarioSessao);
            return -2;
        }
    }

    function selecionarChamado($idChamado){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_CHAMADO, FK_ITEM_CHAMADO, DESCRICAO_CHAMADO, DATA_ABERTURA_CHAMADO, DATA_FINALIZACAO_CHAMADO, FK_USUARIO, STATUS_CHAMADO FROM {$this->tbl_chamados} WHERE ID_CHAMADO = ?");
            $stmt->bind_param("i", $idChamado);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return new Chamado($row['ID_CHAMADO'], $row['FK_ITEM_CHAMADO'], $row['DESCRICAO_CHAMADO'], $row['DATA_ABERTURA_CHAMADO'], $row['DATA_FINALIZACAO_CHAMADO'], $row['FK_USUARIO'], $row['STATUS_CHAMADO']);
            }

            return null;
        } catch (Exception $e){
            Util::inserirErro($e, "selecionarChamado", $this->idUsuarioSessao);
            return null;
        }
    }

    function listarChamados(){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_CHAMADO, FK_ITEM_CHAMADO, DESCRICAO_CHAMADO, DATA_ABERTURA_CHAMADO, DATA_FINALIZACAO_CHAMADO, FK_USUARIO, STATUS_CHAMADO FROM {$this->tbl_chamados}");
            $stmt->execute();

            $listaChamados = [];

            $result = $stmt->get_result();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $chamado = new Chamado($row['ID_CHAMADO'], $row['FK_ITEM_CHAMADO'], $row['DESCRICAO_CHAMADO'], $row['DATA_ABERTURA_CHAMADO'], $row['DATA_FINALIZACAO_CHAMADO'], $row['FK_USUARIO'], $row['STATUS_CHAMADO']);
                    $listaChamados[] = $chamado;
                }
            }

            return $listaChamados;
        } catch(Exception $e){
            Util::inserirErro($e, "listarChamados", $this->idUsuarioSessao);
            return null;
        }
    }

    function salvarFollowup($followup){

        $fkChamado = $followup['fkChamado'];
        $fkUsuario = $followup['fkUsuario'];
        $desc = $followup['desc'];
        $data = (new DateTime())->format('d/m/Y H:i');

        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_follow_up_chamados} (FK_CHAMADO, FK_USUARIO, FOLLOW_UP, DATA_HORA) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $fkChamado, $fkUsuario, $desc, $data);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;
        }catch(Exception $e){
            Util::inserirErro($e, "salvarFollowUp", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listarFollowUp($fkChamado){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_FOLLOW_UP, FK_CHAMADO, FK_USUARIO, FOLLOW_UP, DATA_HORA FROM {$this->tbl_follow_up_chamados} WHERE FK_CHAMADO = ? ORDER By ID_FOLLOW_UP DESC");
            $stmt->bind_param("i", $fkChamado);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $followUp[] = [
                        'idFollowUp' => $row['ID_FOLLOW_UP'],
                        'fkChamado' => $row['FK_CHAMADO'],
                        'fkUsuario' => $row['FK_USUARIO'],
                        'followUp' => $row['FOLLOW_UP'],
                        'dataHora' => $row['DATA_HORA']
                    ];
                }

                return $followUp;
            }
            
            return [];
        } catch (Exception $e){
            Util::inserirErro($e, "listarFollowUp", $this->idUsuarioSessao);
            return [];
        }
    }

    function salvarFoto($foto){
        $fkChamado = $foto['fkChamado'];
        $caminhoImagem = $foto['path'];

        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_fotos_chamados} (FK_CHAMADO, CAMINHO_IMAGEM) VALUES (?,?)");
            $stmt->bind_param("is", $fkChamado, $caminhoImagem);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;
        }catch(Exception $e){
            Util::inserirErro($e, "salvarFoto", $this->idUsuarioSessao);
            return -2;
        }
    }

    function listarFotosChamado($fkChamado){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_FOTO_CHAMADO, FK_CHAMADO, CAMINHO_IMAGEM FROM {$this->tbl_fotos_chamados} WHERE FK_CHAMADO = ?");
            $stmt->bind_param("i", $fkChamado);

            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $listaFotos[] = [
                        'idFoto' => $row['ID_FOTO_CHAMADO'],
                        'fkChamado' => $row['FK_CHAMADO'],
                        'caminhoImagem' => $row['CAMINHO_IMAGEM']
                    ];
                }

                return $listaFotos;
            }

            return [];
        } catch (Exception $e){
            Util::inserirErro($e, "listarFotosChamado", $this->idUsuarioSessao);
            return [];
        }
    }
}

?>
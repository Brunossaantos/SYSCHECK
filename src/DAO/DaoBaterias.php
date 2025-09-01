<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Bateria;
use Exception;
use database\Conexao;
use Util\Util;

class DaoBaterias{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_baterias = TBL_BATERIAS;
    private $tbl_empilhadeira_bateria = TBL_EMPILHADEIRA_BATERIA;
    private $tbl_carga_bateria_comum = TBL_CARGA_BATERIA_COMUM;

    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }    
    
    function cadastrarBateria(Bateria $bateria){
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_baterias} (NUMERO_BATERIA, DESCRICAO_BATERIA, MEDIDAS, OBSERVACAO) VALUES (?,?,?,?)");
            $stmt->bind_param("isss", $bateria->getNumeroBateria(), $bateria->getDescricaoBateria(), $bateria->getMedidas(), $bateria->getObservacao());

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;

        }catch(Exception $e){
            Util::inserirErro($e, "cadastrarBateria", $this->idUsuarioSessao);
            return -2;
        }
    }

    function gerarListaBaterias(){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_BATERIA, NUMERO_BATERIA, DESCRICAO_BATERIA, MEDIDAS, OBSERVACAO FROM {$this->tbl_baterias}");
            $stmt->execute();

            $result = $stmt->get_result();

            $listaBaterias = [];

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $bateria = new Bateria($row['ID_BATERIA'], $row['NUMERO_BATERIA'], $row['DESCRICAO_BATERIA'], $row['MEDIDAS'], $row['OBSERVACAO']);
                    $listaBaterias[] = $bateria;
                }
            }

            return $listaBaterias;

        }catch(Exception $e){
            Util::inserirErro($e, "gerarListaBaterias", $this->idUsuarioSessao);
            return null;
        }
    }

    function salvarBateriaDeUso($fkChecklist, $fkEmpilhadeira, $fkBateria, $nivelBateria, $dataEhora){
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_empilhadeira_bateria} (FK_CHECKLIST, FK_EMPILHADEIRA, FK_BATERIA, NIVEL_BATERIA, DATA_HORA) VALUES (?,?,?,?,?)");
            $stmt->bind_param("iiiis", $fkChecklist, $fkEmpilhadeira, $fkBateria, $nivelBateria, $dataEhora);

            if($stmt->execute()){
                return $stmt->insert_id;
            }           

            return -1;

        }catch(Exception $e){
            Util::inserirErro($e, "salvarBateriaDeUso", $this->idUsuarioSessao);
            return -2;
        }
    }

    function recuperarBateriasUtilizadas(int $fkChecklist){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_EMPILHADEIRA_BATERIA, FK_CHECKLIST, FK_EMPILHADEIRA, FK_BATERIA, NIVEL_BATERIA, DATA_HORA FROM {$this->tbl_empilhadeira_bateria} WHERE FK_CHECKLIST = ?");
            $stmt->bind_param("i", $fkChecklist);

            $stmt->execute();
            $result = $stmt->get_result();

            $listaDeBaterias = [];

            if($result->num_rows >0){
                while($row = $result->fetch_assoc()){

                    $bateria = $this->selecionarBateria($row['FK_BATERIA']);

                    $listaDeBaterias[] = [
                        'ID_RELACIONAMENTO' => $row['ID_EMPILHADEIRA_BATERIA'],
                        'FK_CHECKLIST' => $row['FK_CHECKLIST'],
                        'FK_EMPILHADEIRA' => $row['FK_EMPILHADEIRA'],
                        'BATERIA' => $bateria->getNumeroBateria(),
                        'DESC_BATERIA' => $bateria->getDescricaoBateria(),
                        'NIVEL_BATERIA' => $row['NIVEL_BATERIA'],
                        'DATA_HORA' => Util::formatarDataHora($row['DATA_HORA'])
                    ];
                }
            }

            return $listaDeBaterias;

        } catch (Exception $e){
            Util::inserirErro($e, "recuperarBateriasUtilizadas", $this->idUsuarioSessao);
            return null;
        }
    }

    function selecionarBateria(int $idBateria){
        try{
            $stmt = $this->conexao->prepare("SELECT NUMERO_BATERIA, DESCRICAO_BATERIA, MEDIDAS, OBSERVACAO FROM {$this->tbl_baterias} WHERE ID_BATERIA = ?");
            $stmt->bind_param("i", $idBateria);

            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return new Bateria($idBateria, $row['NUMERO_BATERIA'], $row['DESCRICAO_BATERIA'], $row['MEDIDAS'], $row['OBSERVACAO']);
            }

            return null;
        } catch (Exception $e){
            Util::inserirErro($e, "selecionarBateria", $this->idUsuarioSessao);
            return null;
        }
    }

    //baterias comuns

    function salvarCargaBateriaComum($cargaBateriaComum){
        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_carga_bateria_comum} (FK_CHECKLIST, FK_EMPILHADEIRA, NIVEL_BATERIA, DATA_HORA) VALUES (?,?,?,?)");
            $stmt->bind_param("iiis", $cargaBateriaComum['FK_CHECKLIST'], $cargaBateriaComum['FK_EMPILHADEIRA'], $cargaBateriaComum['NIVEL_BATERIA'], $cargaBateriaComum['DATA_HORA']);

            if($stmt->execute()){
                return $stmt->insert_id;
            }

            return -1;

        } catch(Exception $e){
            Util::inserirErro($e, "salvarCargaBateriaComum", $this->idUsuarioSessao);
            echo "<pre>";
            var_dump($e);
            return -2;
        }
    }

    function recuperarCargaBateriaComum($fkChecklist){
        try{
            $stmt = $this->conexao->prepare("SELECT ID_CARGA_BATERIA, FK_CHECKLIST, FK_EMPILHADEIRA, NIVEL_BATERIA, DATA_HORA FROM {$this->tbl_carga_bateria_comum} WHERE FK_CHECKLIST = ?");
            $stmt->bind_param("i", $fkChecklist);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return [
                    'ID_CARGA' => $row['ID_CARGA_BATERIA'],
                    'FK_CHECKLIST' => $row['FK_CHECKLIST'],
                    'FK_EMPILHADEIRA' => $row['FK_EMPILHADEIRA'],
                    'NIVEL_BATERIA' => $row['NIVEL_BATERIA'],
                    'DATA_HORA' => $row['DATA_HORA']
                ];
            }

            return [];

        } catch(Exception $e){
            Util::inserirErro($e, "recuperarCargaBateriaComum", $this->idUsuarioSessao);
            return null;
        }
    }

}

?>
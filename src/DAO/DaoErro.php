<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use DateTime;
use models\Erro;

class DaoErro{

    private $tbl_erros = TBL_ERROS;
    private $conexao;
    private $idUsuarioSessao;
    
    function __construct($conexao, $idUsuarioSessao){
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirErro(Erro $erro){
                
        $logErro = $erro->getErro();
        $arquivo = $erro->getArquivo();
        $linha = $erro->getLinha();
        $local = $erro->getLocal();
        $dataHora = $erro->getDataHora();
        $usuario = $erro->getFkUsuario();

        try{
            $stmt = $this->conexao->prepare("INSERT INTO {$this->tbl_erros} (ERRO, ARQUIVO, LINHA, LOCAL, DATA_HORA, FK_USUARIO)VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssi', $logErro, $arquivo, $linha, $local, $dataHora, $usuario);

            if($stmt->execute()){                
                return $stmt->insert_id;
            }

            return -1;
        } catch (Exception $e){           
            return $e;
        }
    }

    function recuperarListaDeErros(){
        $listaErros = [];
        try{
            $stmt = $this->conexao->prepare("SELECT ID_ERRO, ERRO, ARQUIVO, LINHA, LOCAL, DATA_HORA, FK_USUARIO FROM {$this->tbl_erros} order By ID_ERRO DESC LIMIT 10");
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $erro = new Erro($row['ID_ERRO'], $row['ERRO'], $row['ARQUIVO'], $row['LINHA'], $row['LOCAL'], $row['DATA_HORA'], $row['FK_USUARIO']);
                    $listaErros[] = $erro;
                }
            }
            
            return $listaErros;
        } catch (Exception $e){
            $erro = new Erro(0, $e->getMessage(), $e->getFile(), $e->getLine(), "DaoErro.recuperarListaDeErros", (new DateTime())->format('d-m-Y H:i:s'), $this->idUsuarioSessao);
            $this->inserirErro($erro);
            return null;
        }
    }

    function limparTabela(){
        try{
            $this->conexao->query("SET FOREIGN_KEY_CHECKS = 0");
            $stmt = $this->conexao->prepare("TRUNCATE TABLE {$this->tbl_erros}");
            $stmt2 = $this->conexao->prepare("ALTER TABLE {$this->tbl_erros} AUTO_INCREMENT = 1");

            $resultado = $stmt->execute() && $stmt2->execute();
            $this->conexao->query("SET FOREIGN_KEY_CHECKS = 1");

            if($resultado){
                return true;
            }

            return false;
        } catch (Exception $e){
            $erro = new Erro(0, $e->getMessage(), $e->getFile(), $e->getLine(), "DaoErro.recuperarListaDeErros", (new DateTime())->format('d-m-Y H:i:s'), $this->idUsuarioSessao);
            $this->inserirErro($erro);
            return false;
        }
    }
}

?>
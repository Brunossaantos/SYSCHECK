<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Bateria;
use DAO\DaoBaterias;
use database\Conexao;
use DateTime;
use DateTimeZone;
use Exception;

class RnBateria{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao){
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function gerarListaBaterias(){
        return (new DaoBaterias((new Conexao())->conectar(), $this->idUsuarioSessao))->gerarListaBaterias();
    }

    function salvarBateriaDeUso($idChecklist, $fkBateria, $nivelBateria) {
        // Recupera o checklist
        $checklist = (new RnChecklist($this->idUsuarioSessao))->selecionarChecklist($idChecklist);
    
        // Verifica se o checklist foi encontrado
        if (!$checklist) {
            throw new Exception("Checklist não encontrado.");
        }
    
        // Obtém o ID da empilhadeira associada ao checklist
        $fkEmpilhadeira = $checklist->getFkObjeto();
    
        // Captura a data e hora atuais
        $dataHora = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));
        $dataHoraFormatado = $dataHora->format("Y-m-d H:i:s");
    
        // Salva o uso da bateria
        $isSuccess = (new DaoBaterias((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarBateriaDeUso($checklist->getIdChecklist(), $fkEmpilhadeira, $fkBateria, $nivelBateria, $dataHoraFormatado);
    
        if (!$isSuccess) {
            throw new Exception("Erro ao salvar bateria de uso.");
        }

        $verificarTroca = (new RnusuarioEmpilhadeira($this->idUsuarioSessao))->trocarBateria($idChecklist);

        var_dump($verificarTroca);        

        if($verificarTroca){            
            header("Location: /syscheck/");                
            exit;
        }    
        
        header("Location: /syscheck/checklist/horimetro/".$idChecklist);        
        //header("Location: /syscheck/etapaschecklist/etapa/".$idChecklist."/".$checklist->getFkTipo()."/1");
        exit;
    }
    

    function selecionarBateriasParaChecklist(int $fkChecklist){
        return (new DaoBaterias((new Conexao())->conectar(), $this->idUsuarioSessao))->recuperarBateriasUtilizadas($fkChecklist);
    }

    function selecionarBateria($idBateria){
        return (new DaoBaterias((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarBateria($idBateria);
    }

    function salvarNivelBateriaComum($fkChecklist, $nivelBateria){
        $checklist = (new RnChecklist($this->idUsuarioSessao))->selecionarChecklist($fkChecklist);
        $dataHora = (new DateTime())->format('d/m/Y H:i:s');
        
        $cargaBateriaComum = [
            'FK_CHECKLIST' =>$checklist->getIdChecklist(),
            'FK_EMPILHADEIRA' => $checklist->getFkObjeto(),
            'NIVEL_BATERIA' => $nivelBateria,
            'DATA_HORA' => $dataHora
        ];

        $isSuccess = (new DaoBaterias((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarCargaBateriaComum($cargaBateriaComum);

        header("Location: /syscheck/checklist/horimetro/".$fkChecklist);
        exit;
    }

    function selecionarNivelBateriaComum($fkChecklist){
        return (new DaoBaterias((new Conexao())->conectar(), $this->idUsuarioSessao))->recuperarCargaBateriaComum($fkChecklist);
    }
}

?>
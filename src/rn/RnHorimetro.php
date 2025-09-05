<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoHorimetro;
use database\Conexao;
use DateTime;
use Random\Engine\Secure;
use Util\Sessao;

class RnHorimetro
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function salvarHorimetro($fkChecklist, $fkEmpilhadeira, $horimetro)
    {

        $qtdHorimetrosRegistrados = $this->recuperarListaHorimetros($fkChecklist);

        if (sizeof($qtdHorimetrosRegistrados) === 0) {
            $isSucess = (new DaoHorimetro((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarHorimetro($fkChecklist, $fkEmpilhadeira, $horimetro);
            $checklist = (new RnChecklist($this->idUsuarioSessao))->selecionarChecklist($fkChecklist);

            if ($isSucess > 0) {
                header("Location: /syscheck/etapaschecklist/etapa/" . $fkChecklist . "/" . $checklist->getFkTipo() . "/1");
                exit;
            }
        } else {
            $this->salvarHorimetroFinal($fkChecklist, $fkEmpilhadeira, $horimetro);
        }
    }

    function salvarHorimetroFinal($fkChecklist, $fkEmpilhadeira, $horimetro)
    {
        $isSucess = (new DaoHorimetro((new Conexao())->conectar(), $this->idUsuarioSessao))->salvarHorimetro($fkChecklist, $fkEmpilhadeira, $horimetro);
        //atualizar a finalização do uso da empilhadeira
        $checklist = (new RnChecklist($this->idUsuarioSessao))->selecionarChecklist($fkChecklist);
        $dataFinalizacaoUso = (new DateTime())->format('d/m/Y H:i:s');

        $checklist->setDataFim($dataFinalizacaoUso);

        $qtdChecklistAlterados = (new RnChecklist($this->idUsuarioSessao))->atualizarChecklist($checklist);

        if ($qtdChecklistAlterados > 0) {
            Sessao::salvarMensagemNaSessao("Uso da empilhadeira finalizado com sucesso");
            header("Location: /syscheck/");
            exit;
        } else {
            Sessao::salvarMensagemNaSessao("Não foi possível encerrar o uso da empilhadeira, contate o desenvolvedor");
            header("Location: /syscheck/");
            exit;
        }
    }

    function selecionarHorimetro($fkChecklist)
    {
        return (new DaoHorimetro((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarHorimetro($fkChecklist);
    }


    function recuperarListaHorimetros($fkChecklist)
    {
        return (new DaoHorimetro((new Conexao())->conectar(), $this->idUsuarioSessao))->recuperarHorimetros($fkChecklist);
    }

    function recuperarHorimetroPorEquipamento($fkEmpilhadeira)
    {
        return (new DaoHorimetro((new Conexao())->conectar(), $this->idUsuarioSessao))->recuperarHorimetroPorEquipamento($fkEmpilhadeira);
    }
}

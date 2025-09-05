<?php

namespace rn;

use DAO\DaoUsuarioEmpilhadeira;
use models\Usuario;
use models\Objeto;
use database\Conexao;
use DateTime;
use DateTimeZone;
use Util\Sessao;

class RnusuarioEmpilhadeira
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function iniciarExpediente($fkChecklist)
    {
        $checklist = (new RnChecklist(Sessao::idusuario()))->selecionarChecklist($fkChecklist);

        $dataHoraInicio = (new DateTime("now", new DateTimeZone("America/Sao_Paulo")))->format("Y-m-d H:i:s");

        return (new DaoUsuarioEmpilhadeira((new Conexao())->conectar(), $this->idUsuarioSessao))->iniciarExpediente($fkChecklist, Sessao::idusuario(), $checklist->getFkObjeto(), $dataHoraInicio);
    }

    function encerrarExpediente($fkChecklist)
    {
        $dataHoraEncerramento = (new DateTime("now", new DateTimeZone("America/Sao_Paulo")))->format("Y-m-d H:i:s");
        return (new DaoUsuarioEmpilhadeira((new Conexao())->conectar(), $this->idUsuarioSessao))->encerrarExpediente($fkChecklist, $dataHoraEncerramento);
    }

    // Adicionado
    function listarHorimetrosAbertos($fkEmpilhadeira)
    {
        return (new \DAO\DaoUsuarioEmpilhadeira((new \database\Conexao())->conectar(), $this->idUsuarioSessao))->verificarChecklistAbertoPorEmpilhadeira($fkEmpilhadeira);
    }


    function verificarChecklistAberto($fkUsuario)
    {

        $checklistAberto = (new DaoUsuarioEmpilhadeira((new Conexao())->conectar(), $this->idUsuarioSessao))->verificarChecklistAberto($fkUsuario);

        if (!empty($checklistAberto) && $checklistAberto['DATA_FIM'] == 0) {
            //echo "condicional atendida";
            return $checklistAberto;
        } else {
            //echo "condicional não atendida";
            return "";
        }
    }

    function trocarBateria($fkChecklist)
    {
        $etapaRealizada = (new RnEtapaRealizada($this->idUsuarioSessao))->verificarUltimaEtapa($fkChecklist);

        if ($etapaRealizada == null) {
            return false;
        } else {
            return true;
        }
    }
}

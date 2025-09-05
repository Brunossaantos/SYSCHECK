<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use models\EtapaRealizada;
use models\EtapasChecklist;
use rn\RnEtapaRealizada;
use rn\RnEtapasChecklist;
use Util\Sessao;

class EtapaRealizadaController
{
    private $rnEtapaRealizada;

    function __construct($rnEtapaRealizada)
    {
        $this->rnEtapaRealizada = $rnEtapaRealizada;
    }

    function confirmarEtapa($idChecklist, $fktipo, $numeroEtapa, $observacao = "")
    {
        $rnEtapasChecklist = new RnEtapasChecklist(Sessao::idusuario());

        $etapa = $rnEtapasChecklist->seleionarEtapaChecklist($fktipo, $numeroEtapa);
        $etapaRealizada = new EtapaRealizada(1, $idChecklist, $etapa->getIdEtapaChecklist(), $numeroEtapa, 1, $observacao);

        $idEtapaRealizada = $this->rnEtapaRealizada->inserirEtapaRealizada($etapaRealizada);

        if ($idEtapaRealizada > 0) {
            $quantEtapas = count($rnEtapasChecklist->listarEtapasChecklist($fktipo));
            header("Location: /syscheck/etapaschecklist/etapa/" . $idChecklist . "/" . $fktipo . "/" . $numeroEtapa + 1);
        }
    }

    function reprovarEtapa($idChecklist, $fktipo, $numeroEtapa, $observacao = "")
    {
        $rnEtapasChecklist = new RnEtapasChecklist(Sessao::idusuario());

        $etapa = $rnEtapasChecklist->seleionarEtapaChecklist($fktipo, $numeroEtapa);
        $etapaRealizada = new EtapaRealizada(1, $idChecklist, $etapa->getIdEtapaChecklist(), $numeroEtapa, 2, $observacao);

        $idEtapaRealizada = $this->rnEtapaRealizada->inserirEtapaRealizada($etapaRealizada);

        if ($idEtapaRealizada > 0) {
            $quantEtapas = count($rnEtapasChecklist->listarEtapasChecklist($fktipo));
            header("Location: /syscheck/etapaschecklist/etapa/" . $idChecklist . "/" . $fktipo . "/" . $numeroEtapa + 1);
        }
    }

    function reprovarChecklist($idChecklist, $fktipo, $numeroEtapa)
    {
        $rnEtapasChecklist = new RnEtapasChecklist(Sessao::idusuario());

        $etapa = $rnEtapasChecklist->seleionarEtapaChecklist($fktipo, $numeroEtapa);
        $etapaRealizada = new EtapaRealizada(1, $idChecklist, $etapa->getIdEtapaChecklist(), $numeroEtapa, 1, "");
        $idEtapaRealizada = $this->rnEtapaRealizada->inserirEtapaRealizada($etapaRealizada);

        if ($idEtapaRealizada > 0) {
            header("Location: ");
        }
    }

    function enviarFoto()
    {
        echo "rota para envio de imagens";
    }
}

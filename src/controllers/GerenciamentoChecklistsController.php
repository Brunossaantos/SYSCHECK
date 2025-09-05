<?php

namespace controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use rn\RnChecklist;
use rn\RnGerenciamentoChecklist;
use rn\RnGerenciamentoChecklists;
use rn\RnHorimetro;
use rn\RnObjeto;
use rn\RnTipoChecklist;
use rn\RnUsuario;
use Util\Sessao;

class GerenciamentoChecklistsController
{
    private $rnGerenciamentoChecklist;

    function __construct($rnGerenciamentoChecklist)
    {
        $this->rnGerenciamentoChecklist = $rnGerenciamentoChecklist;
    }

    function index()
    {
        echo 'Estou respondendo';
    }

    function listarChecklists()
    {
        $rnGerenciamentoChecklist = new RnGerenciamentoChecklists(Sessao::idusuario());
        $listaChecklists = $rnGerenciamentoChecklist->listaChecklists();

        $rnChecklist = new RnChecklist(Sessao::idusuario());
        $rnUsuario = new RnUsuario(Sessao::idusuario());
        $rnTipo = new RnTipoChecklist(Sessao::idusuario());
        $rnObjeto = new RnObjeto(Sessao::idusuario());

        $qtdChecklists = 0;

        foreach ($listaChecklists as &$checklist) {
            $usuario = $rnUsuario->selecionarUsuario($checklist['usuario']);
            $checklist['usuario'] = $usuario;

            $tipo = $rnTipo->selecionarTipoChecklist($checklist['tipo']);
            $checklist['tipo'] = $tipo;

            $empilhadeira = $rnObjeto->selecionarObjeto($checklist['empilhadeira']);
            $checklist['empilhadeira'] = $empilhadeira;

            if ($checklist['horimetroFinal'] == null) {
                $qtdChecklists++;
            }

            //lista composta por objetos usuario, objeto tipo, e empilhadeira. Utilizar métodos getters para consultar os dados.
        }

        require_once __DIR__ . '/../views/features/checklists/gerenciamento/gerenciarchecklists.php';
    }


    function finalizarhorimetro($idChecklist)
    {
        $checklist = (new RnChecklist(Sessao::idusuario()))->selecionarChecklist($idChecklist);
        $empilhadeira = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($checklist->getFkObjeto());

        $logHorimetro = [
            'checklist' => $checklist->getIdChecklist(),
            'empilhadeira' => $empilhadeira->getIdObjeto(),
            'lider' => Sessao::idusuario()
        ];

        $idLog = (new RnGerenciamentoChecklist(Sessao::idusuario()))->salvarLogFinalizarHorimetro($logHorimetro);

        if ($idLog > 0) {
            header("Location:/syscheck/checklist/horimetro/" . $checklist->getIdChecklist());
            exit;
        }
    }
}

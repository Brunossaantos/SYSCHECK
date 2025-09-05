<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use DateTime;
use rn\RnLista;
use rn\RnObjeto;
use rn\RnChecklist;
use Util\Sessao;

class ListaController
{

    private $rnLista;

    function __construct(RnLista $rnLista)
    {
        $this->rnLista = $rnLista;
    }

    function index()
    {

        $nome = "";
        $data = (new DateTime())->format('d/m/y H:i:s');
        $fkUsuario = 0;

        if (isset($_GET['tagdec'])) {
            $colaborador = (new RnLista())->selecionarColaborador($_GET['tagdec']);

            //echo "<pre>";
            //var_dump($colaborador);

            if (empty($colaborador)) {
                Sessao::salvarMensagemNaSessao("Usuário não encontrado na base do eTreinamento, contate a TI para verificar o cadastro.");
                header("Location:/syscheck/lista");
            }

            $usuario = (new RnLista())->selecionarFkUsuario($colaborador['nome']);

            if ($usuario == null) {
                Sessao::salvarMensagemNaSessao("Usuário não encontrado na base do Syscheck, contate a TI para verificar o cadastro.");
                header("Location:/syscheck/lista");
            }

            $nome = $usuario->getNome();
            $fkUsuario = $usuario->getIdUsuario();
        }

        $listaCarros = (new RnObjeto(Sessao::idusuario()))->listarObjetosPeloTipo(1);

        $listaChecklists = (new RnChecklist(Sessao::idusuario()))->listarChecklistsVeiculares();

        foreach ($listaChecklists as $checklist) {
            switch ($checklist->getStatusChecklist()) {
                case 1:
                    $checklist->setStatusChecklist("Pendente");
                    break;
                case 3:
                    $checklist->setStatusChecklist("Concluído");
                    break;
                default:
                    $checklist->setStatusChecklist("Status desconhecido");
            }
        }

        require_once __DIR__ . '/../views/features/lista/retiradadevolucao.php';
    }

    function verificarStatusVeiculo($fkVeiculo)
    {
        echo (new RnLista())->verificarStatus($fkVeiculo);
    }

    function selvarMovimentacao($fkUsuario, $fkVeiculo, $status)
    {

        $movimentacao = [
            'usuario' => $fkUsuario,
            'veiculo' => $fkVeiculo,
            'data' => (new DateTime())->format('d/m/Y H:i:s'),
            'status' => $status
        ];

        (new RnLista())->salvarMovimentacao($movimentacao);
    }
}

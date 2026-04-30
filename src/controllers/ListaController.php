<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use DateTime;
use models\Usuario;
use rn\RnLista;
use rn\RnObjeto;
use rn\RnChecklist;
use Util\Sessao;

class ListaController
{

    private RnLista $rn;

    public function __construct(RnLista $rn)
    {
        $this->rn = $rn;
    }


    function index()
    {
        $nome      = "";
        $data      = (new DateTime())->format('d/m/y H:i:s');
        $fkUsuario = 0;

        // Leitura do crachá via GET (formulário na view)
        if (isset($_GET['tagdec'])) {
            $colaborador = (new RnLista())->selecionarColaborador($_GET['tagdec']);

            if (empty($colaborador)) {
                Sessao::salvarMensagemNaSessao("Usuário não encontrado na base do eTreinamento, contate a TI para verificar o cadastro.");
                header("Location:/syscheck/lista");
                exit;
            }

            $usuario = (new RnLista())->selecionarFkUsuario($colaborador['nome']);

            if ($usuario == null) {
                Sessao::salvarMensagemNaSessao("Usuário não encontrado na base do Syscheck, contate a TI para verificar o cadastro.");
                header("Location:/syscheck/lista");
                exit;
            }

            $nome      = $usuario->getNome();
            $fkUsuario = $usuario->getIdUsuario();
        }

        $listaCarros = (new RnObjeto(Sessao::idusuario()))->listarObjetosPeloTipo(1);

        $listaChecklists = (new RnChecklist(
            Sessao::idusuario(),
            Sessao::idempresa()
        ))->listarChecklistsVeiculares();

        foreach ($listaChecklists as $checklist) {
            switch ($checklist->getStatusChecklist()) {
                case 1:
                    $checklist->setStatusChecklist("Pendente");
                    break;
                case 3:
                    $checklist->setStatusChecklist("Concluído");
                    break;
                default:
                    $checklist->setStatusChecklist("Pendente");
            }
        }

        require_once __DIR__ . '/../views/features/lista/retiradadevolucao.php';
    }


    /**
     * Endpoint AJAX — retorna o status atual do veículo (1 = Disponível, 2 = Ocupado).
     */
    function verificarStatusVeiculo($fkVeiculo)
    {
        echo (int) (new RnLista())->verificarStatus($fkVeiculo);
        exit;
    }


    /**
     * Endpoint AJAX — busca o usuário pelo tag do crachá e retorna JSON.
     * Usado pelo frontend para preencher Nome e fkUsuario dinamicamente.
     */
    function buscarUsuarioPorTag($tag)
    {
        header('Content-Type: application/json');

        $colaborador = (new RnLista())->selecionarColaborador($tag);

        if (empty($colaborador)) {
            echo json_encode(['erro' => 'Usuário não encontrado no eTreinamento.']);
            exit;
        }

        $usuario = (new RnLista())->selecionarFkUsuario($colaborador['nome']);

        if ($usuario == null) {
            echo json_encode(['erro' => 'Usuário não encontrado no Syscheck.']);
            exit;
        }

        echo json_encode([
            'id'   => $usuario->getIdUsuario(),
            'nome' => $usuario->getNome(),
        ]);
        exit;
    }


    /**
     * Registra a movimentação do veículo.
     *
     * A RnLista decide automaticamente:
     *   Status 1 (Disponível) → RETIRADA: insere registro com DATA_HORA
     *   Status 2 (Ocupado)    → DEVOLUÇÃO: atualiza DATA_HORA_DEVOLUCAO
     */
    function salvarMovimentacao($fkUsuario, $fkVeiculo)
    {
        $movimentacao = [
            'usuario' => (int) $fkUsuario,
            'veiculo' => (int) $fkVeiculo,
        ];

        // A RnLista cuida de toda a lógica e já redireciona ao final
        (new RnLista())->salvarMovimentacao($movimentacao);
    }
}

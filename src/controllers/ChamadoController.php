<?php

namespace controllers;

include_once __DIR__ . '/../../vendor/autoload.php';

use DateTime;
use models\Chamado;
use rn\RnChamado;
use rn\RnObjeto;
use rn\RnTipoChecklist;
use rn\RnUsuario;
use Util\Sessao;

/**
 * Controller responsável por gerenciar ações relacionadas a Chamados.
 * Atua como intermediário entre as regras de negócio (RN) e as views.
 */
class ChamadoController
{
    private $rnChamado;

    /**
     * Construtor
     * @param RnChamado $rnChamado Instância de regra de negócio de chamados
     */
    function __construct($rnChamado)
    {
        $this->rnChamado = $rnChamado;
    }

    /** Teste simples do controlador */
    function index()
    {
        echo "Controlador funcionando";
    }

    /**
     * Abre um novo chamado.
     * - POST → cria o chamado e envia arquivos
     * - GET → carrega listas e exibe o formulário
     */
    function abrirChamado()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $fkUsuario = $_POST['fkusuario'];
            $fkTipo = $_POST['fktipo'];
            $fkEquipamento = $_POST['fkequipamento'];
            $descChamado = $_POST['deschamado'];
            $dataHora = $_POST['datahora'];

            /** Cria o modelo de chamado */
            $chamado = new Chamado(0, $fkEquipamento, $descChamado, $dataHora, null, $fkUsuario, 1);

            /** RN responsável por abrir o chamado */
            (new RnChamado(Sessao::idusuario()))->abrirChamado($chamado, $_FILES);
        } else {
            /** Carregamento de dados necessários para o formulário */
            $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
            $listaEquipamentos = (new RnObjeto(Sessao::idusuario()))->listarObjetosAtivos();
            $dataHora = (new DateTime())->format('d/m/Y H:i');
            $usuario = (new RnUsuario(Sessao::idusuario()))->selecionarUsuario(Sessao::idusuario());

            require_once __DIR__ . '/../views/features/chamados/abrirchamado.php';
        }
    }

    /**
     * Lista e organiza informações dos chamados para a tela de gerenciamento.
     * Enriquecimento dos modelos com nome do usuário e nome do equipamento.
     */
    function gerenciarChamados()
    {
        $listaChamados = (new RnChamado(Sessao::idusuario()))->listarChamados();
        $rnUsuario = new RnUsuario(Sessao::idusuario());
        $rnObjeto = new RnObjeto(Sessao::idusuario());

        /** Preenche informações adicionais no objeto Chamado */
        foreach ($listaChamados as $chamado) {

            // Obtém nome do usuário
            $usuario = $rnUsuario->selecionarUsuario($chamado->getFkUsuario());
            $nomeUsuario = $usuario ? ($usuario->getNome() ?? $usuario->getNOME() ?? "Desconhecido") : "Desconhecido";
            $chamado->setNomeUsuario($nomeUsuario);

            // Obtém nome do equipamento
            $equip = $rnObjeto->selecionarObjeto($chamado->getFkItemChamado());
            $nomeEquip = $equip && method_exists($equip, "getDescricaoObjeto") ? $equip->getDescricaoObjeto() : "Sem equipamento";
            $chamado->setNomeEquipamento($nomeEquip);
        }

        require_once __DIR__ . '/../views/features/chamados/gerenciarchamados.php';
    }

    /** Carrega view de follow-up (uso simples) */
    function followupChamado()
    {
        require_once __DIR__ . '/../views/features/chamados/followupchamado.php';
    }

    /**
     * Seleciona um chamado específico e prepara dados de follow-up, fotos e usuário.
     * @param int $fkChamado ID do chamado a ser carregado
     */
    function selecionarChamado($fkChamado)
    {
        $rnChamado = (new RnChamado(Sessao::idusuario()));
        $rnUsuario = (new RnUsuario(Sessao::idusuario()));

        $chamado = $rnChamado->selecionarChamado($fkChamado);
        $listaFollowUp = $rnChamado->listaFollowUp($fkChamado);
        $listaFotos = $rnChamado->listarFotosChamado($fkChamado);

        $usuario = $rnUsuario->selecionarUsuario($chamado->getFkUsuario());
        $equipamento = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($chamado->getFkItemChamado());

        /** Substitui IDs por objetos de usuário */
        foreach ($listaFollowUp as &$followUp) {
            $followUp['fkUsuario'] = $rnUsuario->selecionarUsuario($followUp['fkUsuario']);
        }

        require_once __DIR__ . '/../views/features/chamados/followupchamado.php';
    }


    public function alterarStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            exit;
        }

        $id = $_POST['idChamado'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$id || !$status) {
            http_response_code(422);
            exit;
        }

        /** Chama método do Model para atualizar o status */
        $model = new Chamado();
        $model->alterarStatus($id, $status);

        http_response_code(200);
        exit;
    }
}

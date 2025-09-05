<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use models\EtapasChecklist;
use rn\RnChecklist;
use rn\RnEtapaRealizada;
use rn\RnEtapasChecklist;
use rn\RnTipoChecklist;
use Util\Sessao;

class EtapasChecklistController
{
    private $rnEtapasChecklist;

    function __construct($rnEtapasChecklist)
    {
        $this->rnEtapasChecklist = $rnEtapasChecklist;
    }

    function carregarFormulario()
    {
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
        require_once __DIR__ . '/../views/features/checklists/etapas/cadastraretapa.php';
    }

    //implementar o uso do ID do usuário da sessão aqui.
    function continuarcadastro($fkTipo)
    {
        $tipoChecklist = (new RnTipoChecklist(Sessao::idusuario()))->selecionarTipoChecklist($fkTipo);
        $quantidadeEtapas = count($this->rnEtapasChecklist->listarEtapasChecklist($fkTipo));

        require_once __DIR__ . '/../views/features/checklists/etapas/continuarcadastroetapas.php';
    }

    function cadastrarnovaetapa()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $fotoObrigatoria = 0;
            $campoAdicional = 0;
            $fkTipo = $_POST['fktipo'];

            if (isset($_POST['numero'])) {
                $numero = $_POST['numero'];
            } else {
                $numero = 1;
            }

            if (isset($_POST['fotoobrigatoria'])) {
                $fotoObrigatoria = 1;
            }

            if (isset($_POST['campoadicional'])) {
                $campoAdicional = 1;
            }

            $etapa = new EtapasChecklist(1, (int) $_POST['fktipo'], $_POST['titulo'], $_POST['conteudo'], $numero, $fotoObrigatoria, $campoAdicional, $_POST['status']);

            echo "<pre>";
            print_r($etapa);

            $idEtapa = $this->rnEtapasChecklist->cadastraNovaEtapa($etapa);

            if ($idEtapa > 0) {
                header("Location:/syscheck/etapaschecklist/continuarcadastro/" . $fkTipo);
            } else {
                echo "Não foi possível cadastrar a etapa no banco de dados";
            }
        } else {
            $this->carregarFormulario();
        }
    }

    function finalizarcadastro($fkTipo)
    {
        $tipoChecklist = (new RnTipoChecklist(Sessao::idusuario()))->selecionarTipoChecklist($fkTipo);

        $listaEtapas = [];

        if ((new RnEtapasChecklist(Sessao::idusuario()))->organizarNumeroEtapas($fkTipo)) {
            $listaEtapas = $this->rnEtapasChecklist->listarEtapasChecklist($fkTipo);
        } else {
            Sessao::salvarMensagemNaSessao("Não foi possível organizar a sequencia das etapas, contate o desenvolvedor do sistema");
        }

        require_once __DIR__ . '/../views/features/checklists/etapas/checklistcompleto.php';
    }

    function consultarChecklists()
    {
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
        require_once __DIR__ . '/../views/features/checklists/tipos/consultartipos.php';
    }

    function etapa($idChecklist, $fkTipo, $numeroEtapa)
    {
        $etapa = $this->rnEtapasChecklist->seleionarEtapaChecklist($fkTipo, $numeroEtapa);

        if ($numeroEtapa <= count($this->rnEtapasChecklist->listarEtapasChecklist($fkTipo)) && $etapa->getStatusEtapa() != 0) {

            if ($etapa !== null) {
                $fotoObrigatoria = false;
                $titulo = $etapa->getTituloEtapa();
                $conteudo = $etapa->getConteudoEtapa();

                if ($etapa->getFotoObrigatoria() == 1) {
                    $fotoObrigatoria = true;
                }
            } else {
                $titulo = "Título da etapa não encontrado";
                $conteudo = "Conteúdo da etapa não encontrado";
            }

            //$titulo = $etapa->getTituloEtapa();
            //$conteudo = $etapa->getConteudoEtapa();       

            require_once __DIR__ . '/../views/features/checklists/checklists/etapachecklist.php';
        } else {
            header("Location: /syscheck/checklist/finalizarChecklist/" . $idChecklist);
        }
    }


    function continuarChecklist($idChecklist)
    {
        $checklist = (new RnChecklist(Sessao::idusuario()))->selecionarChecklist($idChecklist);

        //echo "<pre>";        
        //var_dump($checklist);

        $ultimaEtapaRealizada = (new RnEtapaRealizada(Sessao::idusuario()))->verificarUltimaEtapa($idChecklist);

        //var_dump($ultimaEtapaRealizada);

        if ($checklist != null && $ultimaEtapaRealizada != null) {
            header("Location: /syscheck/etapaschecklist/etapa/" . $checklist->getIdChecklist() . "/" . $checklist->getFkTipo() . "/" . $ultimaEtapaRealizada->getNumeroEtapa() + 1);
        } else {
            header("Location: /syscheck/etapaschecklist/etapa/" . $checklist->getIdChecklist() . "/" . $checklist->getFkTipo() . "/1");
            //echo "Não é possível recuperar os dados do checklist";
        }
    }

    function gerenciaretapa($idEtapaChecklist)
    {

        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
        $etapa = (new RnEtapasChecklist(Sessao::idusuario()))->selecionarEtapaPeloId($idEtapaChecklist);

        require_once __DIR__ . '/../views/features/checklists/etapas/alteraretapa.php';
    }

    function salvaralteracaoetapa()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idEtapa = $_POST['idetapa'];
            $fkTipoChecklist = $_POST['fktipo'];
            $tituloEtapa = $_POST['titulo'];
            $conteudoEtapa = $_POST['conteudo'];
            $numeroEtapa = $_POST['numero'];
            $fotoObrigatoria = isset($_POST['fotoobrigatoria']) ? 1 : 0;
            $campoAdicional = isset($_POST['campoadicional']) ? 1 : 0;
            $status = $_POST['status'];


            $etapa = new EtapasChecklist($idEtapa, $fkTipoChecklist, $tituloEtapa, $conteudoEtapa, $numeroEtapa, $fotoObrigatoria, $campoAdicional, $status);

            $qtdLinhas = (new RnEtapasChecklist(Sessao::idusuario()))->alterarEtapaChecklist($etapa);

            if ($qtdLinhas > 0) {
                var_dump($qtdLinhas);
                (new RnEtapasChecklist(Sessao::idusuario()))->organizarNumeroEtapas($fkTipoChecklist);
            }

            header("Location:/syscheck/etapaschecklist/finalizarcadastro/" . $fkTipoChecklist);
        }
    }
}

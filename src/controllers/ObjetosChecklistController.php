<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use rn\RnObjeto;
use models\Objeto;
use rn\RnTipoChecklist;
use Util\Sessao;

class ObjetosChecklistController
{

    private $rnObjeto;

    function __construct(RnObjeto $rnObjeto)
    {
        $this->rnObjeto = $rnObjeto;
    }

    function cadastrarobjeto()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $descricao = $_POST['descricao'];
            $fkTipo = $_POST['fktipo'];
            $status = $_POST['statusitem'];

            $objeto = new Objeto(1, strtoupper($descricao), $fkTipo, $status);

            echo "<pre>";
            var_dump($objeto);

            $idObjetoCadastrado = $this->rnObjeto->cadastraNovoObjeto($objeto);

            var_dump($idObjetoCadastrado);

            if ($idObjetoCadastrado > 0) {
                header("Location: /syscheck/checklist");
            } else {
                echo "Não foi possível cadastrar um novo objeto";
            }
        } else {

            $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
            require_once __DIR__ . '/../views/features/checklists/objetos/cadastrarobjeto.php';
        }
    }

    function listarobjetos()
    {
        $listaObjetos = (new RnObjeto(Sessao::idusuario()))->listarObejetos();
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();

        //recuperar lista de objetos

        require_once __DIR__ . '/../views/features/checklists/objetos/listarobjetos.php';
    }

    function alterarobjeto($idobjeto)
    {
        $objeto = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($idobjeto);
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();

        require_once __DIR__ . '/../views/features/checklists/objetos/alterarobjeto.php';
    }

    function salvarAlteracaoObjeto()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $idObjeto = $_POST['idobjeto'];
            $descricao = $_POST['descricao'];
            $fkTipoChecklist = $_POST['fktipo'];
            $status = $_POST['statusitem'];

            $objeto = new Objeto($idObjeto, $descricao, $fkTipoChecklist, $status);


            $objetoAlterado = (new RnObjeto(Sessao::idusuario()))->alterarObjeto($objeto);


            if ($objetoAlterado > 0) {
                header("Location: /syscheck/objeto/listarobjetos");
            } else {
                echo "Não foi possível alterar o objeto";
            }
        }
    }

    function excluirobjeto()
    {
        echo "Função para excluir objeto cadastrado";
    }

    function retornarListaObjetos()
    {
        echo "Função que retorna listagem de objetos cadastrados";
    }
}

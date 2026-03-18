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
            $fkEmpresa = $_POST['fk_empresa'];

            $objeto = new Objeto(1, strtoupper($descricao), $fkTipo, $status, $fkEmpresa);

            $idObjetoCadastrado = $this->rnObjeto->cadastraNovoObjeto($objeto);

            if ($idObjetoCadastrado > 0) {
                // REDIRECIONA PARA A MESMA PÁGINA COM SUCESSO
                header("Location: /syscheck/objeto/cadastrarobjeto?sucesso=1");
                exit();
            } else {
                // REDIRECIONA PARA A MESMA PÁGINA COM ERRO
                header("Location: /syscheck/objeto/cadastrarobjeto?erro=1");
                exit();
            }
        } else {
            $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
            $listaUnidades = $this->rnObjeto->listarEmpresasSimples();

            require_once __DIR__ . '/../views/features/checklists/objetos/cadastrarobjeto.php';
        }
    }
    function listarobjetos()
    {
        $listaObjetos = (new RnObjeto(Sessao::idusuario()))->listarObjetos();
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();

        //recuperar lista de objetos

        require_once __DIR__ . '/../views/features/checklists/objetos/listarobjetos.php';
    }

    function alterarobjeto($idobjeto)
    {
        $objeto = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($idobjeto);
        $listaTipos = (new RnTipoChecklist(Sessao::idusuario()))->retornarListaTiposChecklist();
        // Buscamos as unidades para o select de alteração também
        $listaUnidades = (new RnObjeto(Sessao::idusuario()))->listarEmpresasSimples();

        require_once __DIR__ . '/../views/features/checklists/objetos/alterarobjeto.php';
    }

    function salvarAlteracaoObjeto()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idObjeto = $_POST['idobjeto'];
            $descricao = $_POST['descricao'];
            $fkTipoChecklist = $_POST['fktipo'];
            $status = $_POST['statusitem'];
            $fkEmpresa = $_POST['fk_empresa']; // Novo campo

            // Atualize o construtor do Objeto no seu model para aceitar o 5º parâmetro
            $objeto = new Objeto($idObjeto, $descricao, $fkTipoChecklist, $status, $fkEmpresa);

            $objetoAlterado = (new RnObjeto(Sessao::idusuario()))->alterarObjeto($objeto);

            if ($objetoAlterado >= 0) { // >= 0 porque se o usuário não mudar nada, o affected_rows é 0 mas não é erro
                header("Location: /syscheck/objeto/alterarobjeto/" . $idObjeto . "?sucesso=1");
                exit();
            } else {
                header("Location: /syscheck/objeto/alterarobjeto/" . $idObjeto . "?erro=1");
                exit();
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

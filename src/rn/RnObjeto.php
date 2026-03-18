<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use models\Objeto;
use DAO\DaoObjeto;
use database\Conexao;
use Util\Sessao;

class RnObjeto
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    // Método novo para alimentar o Select da View sem precisar de RnEmpresa
    public function listarEmpresasSimples()
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->listarEmpresasSimples();
    }

    function cadastraNovoObjeto(Objeto $objeto)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirObjeto($objeto);
    }

    function selecionarObjeto($idObjeto)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarObjeto($idObjeto);
    }

    function alterarObjeto(Objeto $objeto)
    {
        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))->alterarObjeto($objeto);
    }

    function listarObjetosPeloTipo($fkTipo)
    {
        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();

        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->listarObjetosPeloTipo($fkTipo, $fkEmpresa);
    }

    function listarObjetos()
    {
        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();
        $idPerfil = $usuario->getFkPerfil(); // Pega o ID do perfil do usuário logado

        // Lista de perfis com acesso TOTAL (1, 7 e 8)
        $perfisAcessoTotal = [1, 7, 8];

        // Se o perfil do cara estiver na lista, ignoramos o filtro de empresa
        if (in_array($idPerfil, $perfisAcessoTotal)) {
            $fkEmpresa = null;
        }

        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->listarObjetos($fkEmpresa);
    }
    public function listarObjetosAtivos()
    {
        $usuario = Sessao::retornarUsuarioLogado();
        $fkEmpresa = $usuario->getFkEmpresa();

        return (new DaoObjeto((new Conexao())->conectar(), $this->idUsuarioSessao))
            ->listarObjetosAtivos($fkEmpresa);
    }
}

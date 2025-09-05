<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoLista;
use database\Conexao2;
use database\Conexao;
use Util\Sessao;

class RnLista
{

    function __construct()
    {
        return true;
    }

    function selecionarColaborador($hexadecimal)
    {
        return (new DaoLista((new Conexao2)->conectar(), Sessao::idusuario()))->selecionarUsuario($hexadecimal);
    }

    function selecionarFkUsuario($nomeColaborador)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->buscarFkUsuario($nomeColaborador);
    }

    function selecionarVeiculo() {}

    function verificarStatus($fkVeiculo)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->verificarStatusVeiculo($fkVeiculo);
    }

    function verificarUltimaMovimentacao($movimentacao)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->selecionarUltimaMovimentacao($movimentacao);
    }

    function salvarMovimentacao($movimentacao)
    {

        switch ($this->verificarStatus($movimentacao['veiculo'])) {
            case 1:
                $movimentacao['status'] = 2;
                (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->salvarMovimentacao($movimentacao);
                break;
            case 2:

                $ultimaMovimentacao = $this->verificarUltimaMovimentacao($movimentacao);

                if ($movimentacao['usuario'] == $ultimaMovimentacao['usuario']) {
                    (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->salvarDevolucao($movimentacao['veiculo']);
                } else {
                    Sessao::salvarMensagemNaSessao("Apenas o usuário que retirou o veículo pode fazer a devolução");
                }

                $movimentacao['status'] = 1;
                break;
            default:
                $movimentacao['status'] = 3;
        }

        header("Location:/syscheck/checklist/iniciarChecklistVeicular/" . $movimentacao['usuario'] . "/" . $movimentacao['veiculo']);
    }
}

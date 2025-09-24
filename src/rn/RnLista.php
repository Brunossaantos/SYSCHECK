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

    public function atualizarStatusVeiculo($fkVeiculo, $novoStatus)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
            ->atualizarStatusVeiculo($fkVeiculo, $novoStatus);
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
            case 1: // Disponível → Retirada
                $movimentacao['status'] = 2; // Ocupado
                (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->salvarMovimentacao($movimentacao);
                break;

            case 2: // Ocupado → Devolução (qualquer usuário)
                (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))->salvarDevolucao($movimentacao['veiculo']);
                $movimentacao['status'] = 1; // Disponível
                break;

            default:
                $movimentacao['status'] = 3; // Status desconhecido
        }

        // Atualiza o status do veículo na tabela
        $this->atualizarStatusVeiculo($movimentacao['veiculo'], $movimentacao['status']);

        header("Location:/syscheck/checklist/iniciarChecklistVeicular/" . $movimentacao['usuario'] . "/" . $movimentacao['veiculo']);
    }
}

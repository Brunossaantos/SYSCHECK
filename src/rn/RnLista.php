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
        $statusAtual = $this->verificarStatus($movimentacao['veiculo']);

        $validacao = $this->validarUsuarioVeiculo(
            $movimentacao['veiculo'],
            $movimentacao['usuario']
        );

        if ($validacao !== true) {
            Sessao::salvarMensagemNaSessao(
                "Veículo em uso por {$validacao['nome']}. Somente ele pode devolver."
            );
            header("Location:/syscheck/lista");
            exit;
        }


        switch ($statusAtual) {
            case 1: // Disponível → Retirada
                (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
                    ->salvarMovimentacao($movimentacao);
                $novoStatus = 2;
                break;

            case 2: // Ocupado → Devolução
                (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
                    ->salvarDevolucao($movimentacao['veiculo']);
                $novoStatus = 1;
                break;

            default:
                $novoStatus = 3;
        }

        // Atualiza o status do veículo na tabela
        $this->atualizarStatusVeiculo($movimentacao['veiculo'], $novoStatus);

        header("Location:/syscheck/checklist/iniciarChecklistVeicular/" . $movimentacao['usuario'] . "/" . $movimentacao['veiculo']);
    }

    public function validarUsuarioVeiculo($fkVeiculo, $fkUsuarioAtual)
    {
        $dao = new DaoLista((new Conexao())->conectar(), Sessao::idusuario());

        $status = $dao->verificarStatusVeiculo($fkVeiculo);

        if ($status == 1) {
            return true; // disponível
        }

        $dados = $dao->buscarUsuarioUltimaMovimentacao($fkVeiculo);

        if (!$dados) {
            return false;
        }

        if ($dados['FK_USUARIO'] != $fkUsuarioAtual) {
            return [
                'permitido' => false,
                'nome' => $dados['NOME']
            ];
        }

        return true;
    }
}

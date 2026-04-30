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
        return (new DaoLista((new Conexao2)->conectar(), Sessao::idusuario()))
            ->selecionarUsuario($hexadecimal);
    }


    function selecionarFkUsuario($nomeColaborador)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
            ->buscarFkUsuario($nomeColaborador);
    }


    function selecionarVeiculo() {}


    function verificarStatus($fkVeiculo)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
            ->verificarStatusVeiculo($fkVeiculo);
    }


    function verificarUltimaMovimentacao($movimentacao)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
            ->selecionarUltimaMovimentacao($movimentacao);
    }


    /**
     * Verifica se existe movimentação em aberto (retirada sem devolução).
     */
    public function buscarMovimentacaoAberta($fkVeiculo)
    {
        return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
            ->buscarMovimentacaoAberta($fkVeiculo);
    }


    /**
     * Decide automaticamente entre RETIRADA e DEVOLUÇÃO com base no status atual do veículo.
     *
     * Status 1 = Disponível → registra RETIRADA (INSERT com DATA_HORA)
     * Status 2 = Ocupado    → registra DEVOLUÇÃO (UPDATE com DATA_HORA_DEVOLUCAO)
     */
    function salvarMovimentacao($movimentacao)
    {
        $statusAtual = $this->verificarStatus($movimentacao['veiculo']);

        // Valida se o usuário tem permissão para devolver
        // (caso o veículo esteja ocupado, só quem retirou pode devolver)
        $validacao = $this->validarUsuarioVeiculo(
            $movimentacao['veiculo'],
            $movimentacao['usuario']
        );

        if ($validacao !== true) {
            Sessao::salvarMensagemNaSessao(
                "Veículo em uso por {$validacao['nome']}. Somente ele pode devolver."
            );
            header("Location:/syscheck/checklist/iniciarChecklistVeicular/{$movimentacao['usuario']}/{$movimentacao['veiculo']}");
            exit;
        }

        $dao = new DaoLista((new Conexao())->conectar(), $movimentacao['usuario']);

        switch ($statusAtual) {
            case 1: // Disponível → RETIRADA
                $resultado = $dao->salvarMovimentacao($movimentacao);
                $novoStatus = 2;
                break;

            case 2: // Ocupado → DEVOLUÇÃO
                $resultado = $dao->salvarDevolucao($movimentacao['veiculo']);
                $novoStatus = 1;
                break;

            default:
                $novoStatus = 1;
                $resultado = 0;
        }

        if ($resultado <= 0) {
            Sessao::salvarMensagemNaSessao("Erro ao registrar movimentação. Tente novamente.");
            header("Location:/syscheck/lista");
            exit;
        }

        // Atualiza o status do veículo na tabela
        $this->atualizarStatusVeiculo($movimentacao['veiculo'], $novoStatus);

        // Redireciona para o checklist veicular
        header("Location:/syscheck/checklist/iniciarChecklistVeicular/{$movimentacao['usuario']}/{$movimentacao['veiculo']}");
        exit;
    }


    /**
     * Valida se o usuário atual tem permissão para interagir com o veículo.
     *
     * - Se o veículo está disponível: qualquer usuário pode retirar → retorna true
     * - Se o veículo está ocupado: apenas quem retirou pode devolver
     *   → retorna true se for o mesmo usuário
     *   → retorna array com nome do usuário que está com o veículo, se for outro
     */
    public function validarUsuarioVeiculo($fkVeiculo, $fkUsuarioAtual)
    {
        $dao = new DaoLista((new Conexao())->conectar(), Sessao::idusuario());

        $status = $dao->verificarStatusVeiculo($fkVeiculo);

        if ($status == 1) {
            return true; // Disponível, qualquer um pode retirar
        }

        $dados = $dao->buscarUsuarioUltimaMovimentacao($fkVeiculo);

        if (!$dados) {
            return true; // Sem histórico, permite a ação
        }

        if ($dados['FK_USUARIO'] != $fkUsuarioAtual) {
            return [
                'permitido' => false,
                'nome'      => $dados['NOME']
            ];
        }

        return true;
    }
    public function buscarUltimoIdUso($fkVeiculo, $fkUsuario)
{
    return (new DaoLista((new Conexao())->conectar(), Sessao::idusuario()))
        ->buscarUltimoIdUso($fkVeiculo, $fkUsuario);
}
 
}

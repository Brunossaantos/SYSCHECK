<?php

namespace rn;

require __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoPermissao;
use database\Conexao;

class RnPermissao
{
    private $daoPermissao;

    public function __construct()
    {
        $this->daoPermissao = new DaoPermissao(
            (new Conexao())->conectar()
        );
    }

    public function usuarioTemPermissao($idPerfil, $nomePermissao)
    {
        // ADMINISTRADOR = acesso total
        if ($idPerfil == 1) {
            return true;
        }

        $permissoes = $this->daoPermissao->buscarPermissoesPorPerfil($idPerfil);

        foreach ($permissoes as $permissao) {
            if ($permissao['nome'] === $nomePermissao) {
                return true;
            }
        }

        return false;
    }
}
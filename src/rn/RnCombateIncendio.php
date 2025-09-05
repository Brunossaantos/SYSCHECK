<?php

namespace rn;

require_once __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoLocal;
use models\Local;
use models\Objeto;
use models\TipoChecklist;
use Util\Util;
use Exception;
use database\Conexao;

class RnCombateIncendio
{
    private $idUsuarioSessao;

    function __construct($idUsuarioSessao)
    {
        $this->idUsuarioSessao = $idUsuarioSessao;
    }

    function inserirLocal(Local $local)
    {
        if ($local != null) {
            try {
                return (new DaoLocal((new Conexao())->conectar(), $this->idUsuarioSessao))->inserirLocal($local);
            } catch (Exception $e) {
                Util::inserirErro($e, "inserirLocal", $this->idUsuarioSessao);
                return -1;
            }
        }
    }

    function selecionarLocal($idLocal)
    {
        try {
            return (new DaoLocal((new Conexao())->conectar(), $this->idUsuarioSessao))->selecionarLocal($idLocal);
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarLocal", $this->idUsuarioSessao);
            return null;
        }
    }

    function listarLocaisCadatrados()
    {
        try {
            $listaLocais = (new DaoLocal((new Conexao())->conectar(), $this->idUsuarioSessao))->listarLocais();
            return $listaLocais;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarLocaisCadatrados", $this->idUsuarioSessao);
            return [];
        }
    }

    function listarEquipamentosLocal($fkLocal)
    {
        try {
            $listaEquipamentos = (new DaoLocal((new Conexao())->conectar(), $this->idUsuarioSessao))->listarEquipamentosPorLocal($fkLocal);

            if (count($listaEquipamentos) > 0) {
                $listaPopulada = [];

                for ($cont = 0; $cont <= count($listaEquipamentos) - 1; $cont++) {
                    $equipamento = (new RnObjeto($this->idUsuarioSessao))->selecionarObjeto($listaEquipamentos[$cont]);

                    $listaPopulada[] = $equipamento->toArray();
                }

                return $listaPopulada;
            }

            return [];
        } catch (Exception $e) {
            Util::inserirErro($e, "listarEquipamentosLocal", $this->idUsuarioSessao);
            return [];
        }
    }
}

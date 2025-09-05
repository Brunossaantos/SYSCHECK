<?php

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Login;
use database\Conexao;
use Exception;
use models\Usuario;
use Util\Util;

class DaoLogin
{
    private $conexao;
    private $idUsuarioSessao;
    private $tbl_usuarios = TBL_USUARIOS;

    function __construct($conexao, $idUsuarioSessao)
    {
        $this->conexao = $conexao;
        $this->idUsuarioSessao = $idUsuarioSessao;
    }


    function selecionarUsuarioPeloNome(Login $login)
    {
        $nomeUsuario = "'%" . $login->getNomeUsuario() . "%'";

        //var_dump($nomeUsuario);

        try {
            $stmt = $this->conexao->prepare("SELECT ID_USUARIO, NOME, DEPARTAMENTO, CARGO, NOME_USUARIO, SENHA, STATUS_USUARIO, TIPO_CHECKLIST FROM {$this->tbl_usuarios} WHERE NOME_USUARIO LIKE {$nomeUsuario}");
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Usuario($row['ID_USUARIO'], $row['NOME'], $row['DEPARTAMENTO'], $row['CARGO'], $row['NOME_USUARIO'], $row['SENHA'], $row['STATUS_USUARIO'], $row['TIPO_CHECKLIST']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "selecionarUsuario", $this->idUsuarioSessao);
            return null;
        }
    }


    function realizarLogin(Login $login)
    {
        $nomeUsuario = "'%" . $login->getNomeUsuario() . "%'";

        try {
            $stmt = $this->conexao->prepare("SELECT NOME_USUARIO, SENHA FROM {$this->tbl_usuarios} WHERE NOME_USUARIO LIKE {$nomeUsuario}");
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return new Login($row['NOME_USUARIO'], $row['SENHA']);
            }

            return null;
        } catch (Exception $e) {
            Util::inserirErro($e, "realizarLogin", $this->idUsuarioSessao);
            return null;
        }
    }
}

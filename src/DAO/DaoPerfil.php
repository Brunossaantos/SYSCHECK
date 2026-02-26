<?php

namespace DAO;

require __DIR__ . '/../../vendor/autoload.php';

use models\Perfil;
use Util\Util;
use database\Conexao;
use Exception;

namespace DAO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use models\Perfil;
use Util\Util;
use Exception;

class DaoPerfil
{
    private $conexao;

    function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function listarPerfis()
    {
        try {
            $stmt = $this->conexao->prepare("
                SELECT id_perfil, nome
                FROM tbl_perfil
                WHERE ativo = 1
                ORDER BY nome
            ");

            if (!$stmt) {
                die("Erro ao preparar SQL: " . $this->conexao->error);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $lista = [];
            while ($row = $result->fetch_assoc()) {
                $lista[] = new Perfil($row['id_perfil'], $row['nome']);
            }

            return $lista;
        } catch (Exception $e) {
            Util::inserirErro($e, "listarPerfis", 0); // 0 = sem usuário logado
            return null;
        }
    }
}
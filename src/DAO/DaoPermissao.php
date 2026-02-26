<?php

namespace DAO;

use PDO;

require_once __DIR__ . '/../constantes/constTabelasdb.php';
require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use models\Permissao;
use Util\Util;

class DaoPermissao
{
    private $conexao;
    private $tbl_permissoes = TBL_PERMISSOES;
    private $tbl_perfil_permissao = TBL_PERFIL_PERMISSAO;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function buscarPermissoesPorPerfil($idPerfil)
    {
        $sql = "
            SELECT p.*
            FROM {$this->tbl_permissoes} p
            INNER JOIN {$this->tbl_perfil_permissao} pp 
                ON pp.id_permissao = p.id
            WHERE pp.id_perfil = ?
        ";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$idPerfil]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
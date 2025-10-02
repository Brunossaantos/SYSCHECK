<?php

namespace Rn;

require_once __DIR__ . '/../database/Conexao.php';

use database\Conexao;

class RnRelatorio
{
    private $con;

    public function __construct()
    {
        $this->con = (new Conexao())->conectar();
    }

    public function getCon()
    {
        return $this->con;
    }
}

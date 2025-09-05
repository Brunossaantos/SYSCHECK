<?php

namespace controllers;

require __DIR__ . '/../../vendor/autoload.php';

use DAO\DaoFoto;
use rn\RnFoto;
use models\Foto;

class FotosController
{
    private $rnFoto;

    function __construct(RnFoto $rnFoto)
    {
        $this->rnFoto = $rnFoto;
    }

    function uploadimagem()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
            $fkChecklist = $_POST['fkchecklist'];
            $numeroEtapa = $_POST['numeroEtapa'];

            $diretorioDestino = __DIR__ . '/../views/fotos/';
            $arquivo = $_FILES['foto'];

            $nomeArquivo = $fkChecklist . "_" . $numeroEtapa . "." . pathinfo($arquivo['name'], PATHINFO_EXTENSION);

            //$nomeArquivo = basename($arquivo['name']);
            $caminhoCompleto = $diretorioDestino . $nomeArquivo;

            $caminhoFoto = "fotos/" . $nomeArquivo;
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];

            if (!in_array($arquivo['type'], $tiposPermitidos)) {
                return ['error' => 'Tipo de arquivo não permitido'];
            }

            if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                $this->inserirfoto($fkChecklist, $numeroEtapa, $caminhoFoto);
                header("Location:" . $_POST['url']);
            }
        }
    }

    function inserirfoto($fkChecklist, $numeroEtapa, $caminhoFoto)
    {
        $foto = new Foto(1, $fkChecklist, $numeroEtapa, $caminhoFoto);
        $rnFoto = new RnFoto(1);
        $rnFoto->inserirFoto($foto);
    }

    function selecionarFoto(int $fkChecklist, int $numeroEtapa)
    {
        $rnFoto = new RnFoto(1);
        $foto = $rnFoto->selecionarFoto($fkChecklist, $numeroEtapa);

        if ($foto != null) {
            return $foto;
        } else {
            return null;
        }
    }
}

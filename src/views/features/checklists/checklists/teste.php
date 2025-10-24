<?php

use Util\Util;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Finalizado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }

        h1,
        h2,
        h3 {
            color: #333;
        }

        a.btn-block {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #2020207e;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-header {
            background-color: #d9d9d9;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }

        .imagem {
            max-width: 100px;
            max-height: 100px;
            display: block;
        }

        /* Para quebrar linhas de texto longas */
        td {
            word-wrap: break-word;
        }

        /* Larguras proporcionais */
        th:nth-child(1),
        td:nth-child(1) {
            width: 10%;
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 40%;
        }

        th:nth-child(3),
        td:nth-child(3) {
            width: 15%;
        }

        th:nth-child(4),
        td:nth-child(4) {
            width: 15%;
        }

        th:nth-child(5),
        td:nth-child(5) {
            width: 20%;
        }

        th:nth-child(6),
        td:nth-child(6) {
            width: 15%;
        }
    </style>
</head>

<body>

    <a class="btn btn-danger btn-block" href="/syscheck/usuario/logout">Finalizar checklist</a>

    <h1><?= $tipo->getDescricaoTipoChecklist() ?></h1>
    <h2><?= $itemChecado->getDescricaoObjeto() ?></h2>

    <p><strong>Data de início:</strong> <?= $checklist->getDataInicio() ?></p>
    <p><strong>Data de finalização do checklist:</strong> <?= $checklist->getDataFim() ?></p>
    <p><strong>Status:</strong> <?= Util::statusChecklist($checklist->getStatusChecklist()) ?></p>
    <p><strong>Responsável:</strong> <?= $responsavel->getNome() ?></p>
    <p><strong>Número do checklist:</strong> <?= $checklist->getIdChecklist() ?></p>

    <p>Caso a resposta da "Ação" da etapa seja "NÃO", descrever o motivo no campo "Observação" e informar seu Líder.</p>

    <!-- Seções gerais como horímetro, bateria etc -->
    <?php if ($empilhadeira) { ?>
        <table class="table table-bordered">
            <tr>
                <td>Horímetro inicial</td>
                <td><?= $listaHorimetros[0]['horimetro'] ?? 'Não preenchido' ?> horas</td>
            </tr>
            <tr>
                <td>Horímetro final</td>
                <td><?= $listaHorimetros[1]['horimetro'] ?? 'Não preenchido' ?> horas</td>
            </tr>
        </table>
    <?php } ?>

    <?php if ($empilhadeiraBateriaComum) { ?>
        <table class="table table-bordered">
            <tr>
                <th>Nível da bateria no início do expediente</th>
                <td><?= $nivelBateria ?>%</td>
            </tr>
        </table>
    <?php } ?>

    <?php if ($empilhadeiraEletrica) { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número da bateria</th>
                    <th>Descrição</th>
                    <th>Nível de carga</th>
                    <th>Data e hora da troca</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaBaterias as $bateria) { ?>
                    <tr>
                        <td><?= $bateria['BATERIA'] ?></td>
                        <td><?= $bateria['DESC_BATERIA'] ?></td>
                        <td><?= $bateria['NIVEL_BATERIA'] ?>%</td>
                        <td><?= $bateria['DATA_HORA'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>

    <!-- Seções de etapas -->
    <?php foreach ($listaTitulos as $titulo) { ?>
        <div class="section">
            <h2><?= $titulo ?></h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Etapas</th>
                        <th>Ações</th>
                        <th>Aprovado / Reprovado</th>
                        <th>Observação</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaEtapas as $etapas) {
                        if ($titulo == $etapas['TITULO']) { ?>
                            <tr>
                                <td><?= $etapas['NUMERO_ETAPA'] ?></td>
                                <td><?= $etapas['CONTEUDO'] ?></td>
                                <td><?= ($etapas['ACAO'] == 1) ? 'APROVADO' : 'REPROVADO' ?></td>
                                <td><?= $etapas['OBSERVACAO'] ?? '' ?></td>
                                <td>
                                    <?php
                                    $fotoEncontrada = false;
                                    foreach ($listaFotos as $foto) {
                                        if ($foto->getNumeroEtapa() == $etapas['NUMERO_ETAPA']) {
                                            $caminhoFoto = '/syscheck/src/views/' . $foto->getCaminhoFoto();
                                            echo "<a href='" . $caminhoFoto . "' target='_blank'>Visualizar foto</a>";
                                            $fotoEncontrada = true;
                                            break;
                                        }
                                    }
                                    if (!$fotoEncontrada) echo "Etapa sem foto";
                                    ?>
                                </td>

                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

</body>

</html>
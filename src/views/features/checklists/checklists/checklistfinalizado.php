<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Consulta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">

    <style>
        .cabecalho {
            border: 1px #000000 solid;
            border-radius: 5px;
        }
    </style>

</head>

<body>
    <?php

    use controllers\FotosController;

    include __DIR__ . '/../../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Consulta</h2>
        <table class="table cabecalho">
            <tbody>
                <tr>
                    <td>Tipo do checklist</td>
                    <td><?= $tipo->getDescricaoTipoChecklist() ?></td>
                </tr>
                <tr>
                    <td>Item checado</td>
                    <td><?= $item->getDescricaoObjeto() ?></td>
                </tr>
                <tr>
                    <td>Data de início do checklist</td>
                    <td><?= $checklist->getDataInicio() ?></td>
                </tr>
                <tr>
                    <td>Data de finalização do checklist</td>
                    <td><?= $checklist->getDataFim() ?></td>
                </tr>
                <tr>
                    <td>Status do checklist</td>
                    <td><?= $status ?></td>
                </tr>
                <tr>
                    <td>Número do checklist</td>
                    <td><?= $listaEtapas[0]['NUMERO'] ?></td>
                </tr>
            </tbody>
        </table>

        <?php if (!empty($listaEtapas)) { ?>
            <table class="table">
                <thead>
                    <th>Titulo Etapa</th>
                    <th>Conteúdo etapa</th>
                    <th>Numero Etapa</th>
                    <th>Ação</th>
                    <th>Observação</th>
                    <th>Foto</th>
                </thead>
                <tbody>
                    <?php foreach ($listaEtapas as $etapa) { ?>
                        <tr>
                            <td><?= $etapa['TITULO'] ?></td>
                            <td><?= $etapa['CONTEUDO'] ?></td>
                            <td><?= $etapa['NUMERO_ETAPA'] ?></td>
                            <td><?= ($etapa['ACAO'] == 1) ? 'Aprovado' : 'Reprovado' ?></td>
                            <td><?= $etapa['OBSERVACAO'] ?></td>
                            <td>
                                <?php
                                foreach ($listaFotos as $foto) {
                                    if ($foto->getNumeroEtapa() == $etapa['NUMERO_ETAPA']) {
                                        $caminhoCompleto = "/syscheck/src/views/" . $foto->getCaminhoFoto();
                                        echo "<a href='" . $caminhoCompleto . "' target='_blank'>Foto</a>";
                                        break;
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else {
            echo "Não existem etapas para esse checklist.";
        } ?>
    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
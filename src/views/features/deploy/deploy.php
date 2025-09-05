<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Erros - Deploy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h3 class="text-center">Lista de erros - Deploy</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID_ERRO</th>
                        <th>Erro</th>
                        <th>Arquivo</th>
                        <th>Linha</th>
                        <th>Local</th>
                        <th>Data e hora</th>
                        <th>Usuário</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaErros as $erro) { ?>
                        <tr>
                            <td><?= $erro->getIdErro() ?></td>
                            <td><?= $erro->getErro() ?></td>
                            <td><?= $erro->getArquivo() ?></td>
                            <td><?= $erro->getLinha() ?></td>
                            <td><?= $erro->getLocal() ?></td>
                            <td><?= $erro->getDataHora() ?></td>
                            <td><?= $erro->getFkUsuario() ?></td>
                        </tr>
                    <?php } ?>
                    <!-- Adicione mais linhas conforme necessário -->
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
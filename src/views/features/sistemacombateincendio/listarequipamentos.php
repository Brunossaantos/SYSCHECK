<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Equipamentos por Local</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    include_once __DIR__ . '/../../public/components/navbar.php';
    ?>
    <div class="container mt-5">
        <h2>Listagem de Equipamentos - <?= $local->getDescricaoLocal() ?></h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaEquipamentos as $equipamento) { ?>
                    <tr>
                        <td>1</td>
                        <td>Extintor ABC</td>
                        <td>Extintor</td>
                        <td><button class="btn btn-danger btn-sm">Excluir</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>
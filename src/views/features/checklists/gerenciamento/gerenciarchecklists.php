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
    <?php include __DIR__ . '/../../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Gerenciamento de checklists de empilhadeiras</h2>
        <h3><?=$qtdChecklists?> checklists de empilhadeiras com horimetro em aberto</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Número do checklist</th>
                    <th>Usuário</th>
                    <th>Tipo</th>
                    <th>Empilhadeira</th>
                    <th>Inicio do checklist</th>
                    <th>Horimetro inicial</th>
                    <th></th>
                    <th>Status do checklist</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($listaChecklists as &$checklist) {
                    if ($checklist['horimetroFinal'] == null) {
                ?>
                        <tr>
                            <td><a href="/syscheck/checklist/checklistfinalizado/<?= $checklist['idChecklist'] ?>"><?= $checklist['idChecklist'] ?></a></td>
                            <td><a href="#"><?= $checklist['usuario']->getNome() ?></a></td>
                            <td><?= $checklist['tipo']->getDescricaoTipoChecklist() ?></td>
                            <td><?= $checklist['empilhadeira']->getDescricaoObjeto() ?></td>
                            <td><?= $checklist['dataInicio'] ?></td>
                            <td><?= $checklist['horimetroInicial'] ?></td>
                            <td><a href="/syscheck/gerenciamentochecklists/finalizarhorimetro/<?=$checklist['idChecklist']?>" class="btn btn-danger">Finalizar horímetro</a></td>
                            <td><?= ($checklist['status'] == 3) ? "Checklist finalizado " . $checklist['dataFim'] : "Checklist pendente" ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
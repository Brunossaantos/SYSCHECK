<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Consulta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">

    <style>
        .cabecalho{
            border: 1px #000000 solid;
            border-radius: 5px;
        }

    </style>

</head>

<body>
    <?php include_once __DIR__ . '/../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Gerenciamento de chamados abertos</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Numero do chamado</th>
                    <th>abertura do chamado</th>
                    <th>Equipamento</th>
                    <th>Ultima providência</th>
                    <th>Status</th>
                    <th>Usuário</th>                    
                </tr>
            </thead>
            <tbody>     
                <?php foreach($listaChamados as $chamado){?>           
                <tr>
                    <td><a href="/syscheck/chamado/selecionarChamado/<?=$chamado->getIdChamado()?>"><?=$chamado->getIdChamado()?></a></td>
                    <td><?=$chamado->getDataAberturaChamado()?></td>
                    <td><?=$chamado->getFkItemChamado()?></td>
                    <td>*iterar sobre a lista do follow up e colocar a data do ultimo status*</td>
                    <td><?=$chamado->getStatusChamado()?></td>
                    <td><?=$chamado->getFkUsuario()?></td>                   
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>

    <!-- <?php include __DIR__ . '/../../../public/components/footer.php'; ?> -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

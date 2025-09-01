<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Consulta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">
</head>
<body>
    <?php 
        use Util\Util;
        use Util\Sessao;
        use rn\RnEtapasChecklist;
        include __DIR__ .'/../../../public/components/navbar.php'; 
        
               
    ?>

    <div class="container mt-5">
        <h2>Consulta</h2>
        <form class="form-inline mb-3">
            <input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Quantidade de etapas</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaTipos as $tipoChecklist){?>
                <tr>
                    <td><a href="/syscheck/etapaschecklist/finalizarcadastro/<?=$tipoChecklist->getIdTipoChecklist()?>"><?=$tipoChecklist->getDescricaoTipoChecklist()?></a></td>
                    <td><?=Util::status($tipoChecklist->getStatusTipoChecklist())?></td>
                    <td><?= (new RnEtapasChecklist($_SESSION['idUsuario']))->quantidadeEtapas($tipoChecklist->getIdTipoChecklist()) ?></td>
                    <td>
                        <a href="#" class="btn btn-warning">Editar</a>
                        <a href="#" class="btn btn-danger">Inativar</a>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>

    <?php include __DIR__ .'/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

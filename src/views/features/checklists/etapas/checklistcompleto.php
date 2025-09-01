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
        use Util\Sessao;
        
        include_once __DIR__ . '/../../../../../vendor/autoload.php';
        include __DIR__ . '/../../../public/components/navbar.php'; 

        Sessao::mostrarMensagem();    
    ?>

    <div class="container mt-5">
    <h3>Checklist</h3>    
    <h2><?=$tipoChecklist->getDescricaoTipoChecklist()?></h2>        

        <table class="table">
            <thead>
                <tr>
                    <th>Numero da Etapa</th>
                    <th>Título da etapa</th>
                    <th>Conteúdo da etapa</th>
                    <th>Foto obrigatória</th>
                    <th>Campo adicional</th>
                    <th>Status da etapa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaEtapas as $etapa){?>
                <tr>
                    <td><a href="/syscheck/etapaschecklist/gerenciaretapa/<?=$etapa->getIdEtapaChecklist()?>"><?=$etapa->getNumeroEtapa()?></a></td>
                    <td><?=$etapa->getTituloEtapa() ?></td>
                    <td><?=$etapa->getConteudoEtapa() ?></td>
                    <!-- <td><?=$etapa->getFotoObrigatoria() ?></td> -->
                    <td><?= ($etapa->getFotoObrigatoria() == 1) ? 'Foto obrigatória' : ''?></td>
                    <td><?= ($etapa->getCampoAdicional() == 1) ? 'Campo obrigatório' : '' ?></td>
                    <td><?= ($etapa->getStatusEtapa() == 1) ? 'Ativo' : 'Inativo' ?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

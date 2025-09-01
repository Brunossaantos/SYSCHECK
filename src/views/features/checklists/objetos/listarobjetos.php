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
    <?php 
        use Util\Util;
        include __DIR__ . '/../../../public/components/navbar.php'; 
    ?>

    <div class="container mt-5">
        <h2>Itens de checklist cadastrados</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Descrição do item de checklist</th>
                    <th>Status</th> 
                    <th>Ações</th>                   
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaObjetos as $objeto){?>
                <tr>
                    <!-- <td><?=$objeto->getFkTipoChecklist()?></td> -->
                     <td>
                        <?php 
                        foreach($listaTipos as $tipo){
                            if($objeto->getFkTipoChecklist() == $tipo->getIdTipoChecklist()){
                                echo $tipo->getDescricaoTipoChecklist();
                                break;
                            }
                        }
                        ?>
                     </td>
                    <td><a href="/syscheck/objeto/alterarobjeto/<?=$objeto->getIdObjeto()?>"><?=$objeto->getDescricaoObjeto()?></a></td>
                    <td><?=Util::status($objeto->getStatusObjeto())?></td>
                    <td>
                        <a class="btn btn-primary" href="#">Ações</a>
                    </td>
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

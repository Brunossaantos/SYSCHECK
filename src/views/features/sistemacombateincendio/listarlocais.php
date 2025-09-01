<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Locais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        include_once __DIR__ . '/../../public/components/navbar.php';
        include_once __DIR__ . '/../../../../vendor/autoload.php';

        use Util\Sessao;

        Sessao::mostrarMensagem();       
    ?>
    <div class="container mt-5">
        <h2>Listagem de Locais</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaLocais as $local){?>
                <tr>
                    
                    <td><a href=""><?=$local->getDescricaoLocal()?></a></td>
                    <td>
                        <a href="/syscheck/combateincendio/listarEquipamentosLocal/<?=$local->getIdLocal()?>" class="btn btn-primary">Equipamentos</a>
                        <a href="#" class="btn btn-danger">Excluir</a>                        
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</body>
</html>

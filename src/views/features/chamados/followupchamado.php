<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow-up do Chamado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">

    <style>
        .cabecalho {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .foto-miniatura {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 5px;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <?php include_once __DIR__ . '/../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Gerenciamento de Chamado</h2>

        <!-- Cabeçalho do Chamado -->
        <div class="cabecalho mb-4">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Número do Chamado:</strong> <?=$chamado->getIdChamado()?></p>
                    <p><strong>Abertura do Chamado:</strong> <?=$chamado->getDataAberturaChamado()?></p>
                    <p><strong>Equipamento:</strong> <?=$equipamento->getDescricaoObjeto()?></p>
                    <p><strong>Última Providência:</strong> Troca do cartucho</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong> Em andamento</p>
                    <p><strong>Usuário:</strong> <?=$usuario->getNome()?></p>
                    <p><strong>Fotos do Chamado:</strong></p>
                    <div>
                        <?php foreach($listaFotos as $foto){?>
                            <img src="/syscheck/src/views/<?=$foto['caminhoImagem']?>" class="foto-miniatura" alt="<?=$foto['caminhoImagem']?>">
                        <?php }?>                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Follow-up -->
        <h4>Follow-up do Chamado</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Data/Hora</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listaFollowUp as $followup){?>
                    <tr>
                        <td><?=$followup['dataHora']?></td>
                        <td><?=$followup['fkUsuario']->getNome()?></td>
                        <td><?=$followup['followUp']?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- <?php include __DIR__ . '/../../../public/components/footer.php'; ?> -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

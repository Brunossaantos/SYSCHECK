<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Cadastro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/../../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Cadastro de item para checklist</h2>
        <form action="/syscheck/objeto/cadastrarobjeto" method="POST">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" id="nome" name="descricao" placeholder="Digite a descrição do objeto" autocomplete="off" require>
            </div>

            <div class="form-group">
                <label for="fktipo">Tipo do checklist</label>
                <select class="form-control" name="fktipo" id="tipo">
                    <option value="" disabled selected>Selecione o tipo do item</option>
                    <?php foreach ($listaTipos as $tipoChecklist){?>
                        <option value="<?=$tipoChecklist->getIdTipoChecklist()?>"><?=$tipoChecklist->getDescricaoTipoChecklist()?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group">
                <label for="statusitem">Status do item</label>
                <select class="form-control" name="statusitem" id="">
                    <option value="--" selected disabled>Status do item</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <hr>

    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

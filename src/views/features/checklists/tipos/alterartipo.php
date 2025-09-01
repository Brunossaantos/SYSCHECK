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
    <?php include __DIR__ .'/../../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Cadastro de tipos de checklist</h2>
        <form action="/syscheck/tiposchecklist/salvaralteracao" method="POST">
            <input type="hidden" name="idtipochecklist" value="<?=$tipoChecklist->getIdTipoChecklist()?>">
            <div class="form-group">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" name="descricao" value="<?=$tipoChecklist->getDescricaoTipoChecklist()?>">
            </div>

            <div class="form-group">
                <label for="statustipochecklist">Status</label>
                <select class="form-control" name="statustipochecklist" id="">
                    <option value="--" selected disabled>Selecione o status do tipo</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-danger">Salvar alterações</button>
        </form>

    </div>

    <?php include __DIR__ .'/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

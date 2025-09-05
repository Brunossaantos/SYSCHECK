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
        <h2>Cadastro de etapas</h2>
        <form onsubmit="return verificarCampos()" action="/syscheck/etapaschecklist/cadastrarnovaetapa" method="POST">
            <input type="hidden" name="fktipo" value="<?= $fkTipo ?>">

            <div class="form-group">
                <label for="tipochecklist">Checklist</label>
                <input type="text" class="form-control" name="tipo" value="<?= $tipoChecklist->getDescricaoTipoChecklist() ?>" disabled>
            </div>

            <div class="form-group">
                <label for="titulo">Título da etapa</label>
                <input type="text" class="form-control" name="titulo" placeholder="Título da etapa" require>
            </div>

            <div class="form-group">
                <label for="conteudo">Conteúdo da etapa</label>
                <textarea class="form-control" name="conteudo" id="" rows="5" cols="5" require></textarea>
            </div>

            <div class="form-group">
                <label for="numero">Número da etapa</label>
                <input type="text" class="form-control" name="numero" value="<?= $quantidadeEtapas ?>" readonly>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="fotoobrigatoria" name="fotoobrigatoria">
                    <label for="fotoobrigatoria" class="form-check-label">Foto obrigatória</label>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="campoadicional" name="campoadicional">
                    <label for="campoadicional" class="form-check-label">Campo adicional</label>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status da etapa</label>
                <select name="status" id="status" class="form-control">
                    <option value="--" selected disabled>Status</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Salvar etapa</button>
            <a class="btn btn-danger" href="/syscheck/etapaschecklist/finalizarcadastro/<?= $fkTipo ?>">Finalizar cadastro de etapas</a>
        </form>

    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

<script>
    function verificarCampos() {
        var status = document.getElementById('status').value;

        if (status == '--') {
            alert('Selecione o status da etapa');
            return false;
        }

        return true;
    }
</script>

</html>
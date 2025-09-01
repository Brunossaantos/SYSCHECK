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
    <?php include __DIR__ .'/../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <h2>Cadastro de usuários</h2>
        <form action="/syscheck/usuario/salvaralteracao" method="POST">
            <input type="hidden" name="idusuario" value="<?=$usuario->getIdUsuario()?>">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?=$usuario->getNome()?>" readonly>
            </div>

            <div class="form-group">
                <label for="departamento">Departamento</label>
                <input type="text" class="form-control" name="departamento" value="<?=$usuario->getDepartamento()?>">
            </div>

            <div class="form-group">
                <label for="cargo">Cargo</label>
                <input type="text" class="form-control" name="cargo" value="<?=$usuario->getCargo()?>">
            </div>

            <div class="form-group">
                <label for="nomeusuario">Nome de usuário</label>
                <input type="text" class="form-control" name="nomeusuario" value="<?=$usuario->getNomeUsuario()?>" readonly>
            </div>

            <div class="form-group">
                <label for="statususuario">Status do usuário</label>
                <select class="form-control" name="statususuario" id="">
                    <!-- <option value="--" selected disabled>Status do usuários</option> -->
                    <option value="1" <?=($usuario->getStatusUsuario() == 1) ? "selected" : ""?>>Ativo</option>
                    <option value="0" <?=($usuario->getStatusUsuario() == 0) ? "selected" : ""?>>Inativo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-danger">Salvar cadastro</button>
        </form>

        <hr>

    </div>

    <?php include __DIR__ .'/../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

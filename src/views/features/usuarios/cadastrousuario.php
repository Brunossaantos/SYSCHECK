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
        <form action="/syscheck/usuario/cadastrarUsuario" method="POST">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do usuário" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="departamento">Departamento</label>                
                 <select name="departamento" class="form-control" id="">
                    <option value="--" selected disabled>Selecione um departamento</option>
                    <?php foreach($listaDepartamentos as $departamento){?>
                        <option value="<?=$departamento->getIdDepartamento()?>"><?=$departamento->getDescricaoDepartamento()?></option>
                    <?php } ?>
                 </select>
            </div>

            <div class="form-group">
                <label for="cargo">Cargo</label>                
                <select name="cargo" class="form-control">
                    <option value="--" selected disabled>Selecione o cargo do usuario</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nomeusuario">Nome de usuário</label>
                <input type="text" class="form-control" id="email" name="nomeusuario" placeholder="Login para acesso" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="statususuario">Status do usuário</label>
                <select class="form-control" name="statususuario" id="">
                    <option value="--" selected disabled>Status do usuários</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="checklistVeicular">Checklist veícular
                    <input type="checkbox" class="form-group" name="checklistveicular" value="1">
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <hr>

    </div>

    <?php include __DIR__ .'/../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

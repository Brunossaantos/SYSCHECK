<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">
</head>
<body>
    <?php include __DIR__ .'/../../public/components/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cadastrar usuários</h5>
                        <p class="card-text">Cadastro de usuários no sistema</p>
                        <a href="/syscheck/usuario/cadastrarUsuario" class="btn btn-primary">Cadastrar novos usuários</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Gerenciar usuários</h5>
                        <p class="card-text">Gerenciar usuários cadastrados no sistema</p>
                        <a href="/syscheck/usuario/gerenciarUsuarios" class="btn btn-primary">Gerenciar usuários</a>
                    </div>
                </div>
            </div>
            <!--
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Card 3</h5>
                        <p class="card-text">Conteúdo do Card 3</p>
                    </div>
                </div>
            </div>
            --> 
        </div>


    </div>

    <?php include __DIR__ .'/../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

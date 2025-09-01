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

        use rn\RnUsuario;
        use Util\Util;

        include __DIR__ .'/../../public/components/navbar.php'; 
    
    ?>

    <div class="container mt-5">
        <h2>Consulta</h2>
        <form class="form-inline mb-3">
            <input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Departamento</th>
                    <th>Cargo</th>
                    <th>Nome de usuário</th>                    
                    <th>Status</th>
                    <th>Ações</th>
                    <th>Senha cadastrada</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaUsuario as $usuario){
                        if($usuario->getStatusUsuario() > 0){                            
                ?>
                <tr>
                    <td><a href="/syscheck/usuario/alterarCadastroUsuario/<?=$usuario->getIdUsuario()?>"><?=$usuario->getNome()?></a></td>
                    <td><?=$usuario->getDepartamento()?></td>
                    <td><?=$usuario->getCargo()?></td>
                    <td><?=$usuario->getNomeUsuario()?></td>
                    <td><?=Util::status($usuario->getStatusUsuario())?></td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="/syscheck/usuario/alterarCadastroUsuario/<?=$usuario->getIdUsuario()?>">Alterar</a>
                        <a class="btn btn-danger btn-sm" href="/syscheck/usuario/excluirUsuario/<?=$usuario->getIdUsuario()?>">Excluir</a>
                    </td>
                    <td><?php echo (new RnUsuario(1))->verificarSenha($usuario->getIdUsuario()) ?></td>
                </tr>                
                <?php }}?>
            </tbody>
        </table>
    </div>

    <?php include __DIR__ .'/../../public/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

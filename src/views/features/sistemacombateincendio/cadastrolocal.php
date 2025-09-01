<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Local</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php 
        include_once __DIR__ . '/../../public/components/navbar.php';
    ?>
    <div class="container mt-5">
        <h2>Cadastro de Local</h2>
        <form action="/syscheck/combateincendio/cadastrarLocal" method="POST" id="formLocal">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição do Local</label>
                <input type="text" name="descricao" class="form-control" id="descricao" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status do local</label>
                <select class="form-control" name="status" id="">
                    <option value="--" selected disabled>Selecione o status do local</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
    
    <script>
        document.getElementById('formLocal').addEventListener('submit', function(event) {
            if (!document.getElementById('descricao').value) {
                event.preventDefault();
                alert('A descrição do local é obrigatória!');
            }
        });
    </script>
</body>
</html>
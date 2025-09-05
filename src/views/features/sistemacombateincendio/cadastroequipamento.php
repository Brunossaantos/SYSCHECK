<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Equipamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Cadastro de Equipamento</h2>
        <form id="formEquipamento">
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição do Equipamento</label>
                <input type="text" class="form-control" id="descricao" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo do Equipamento</label>
                <select class="form-control" id="tipo" required>
                    <option value="">Selecione um tipo</option>
                    <option value="1">Extintor</option>
                    <option value="2">Mangueira</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>

    <script>
        document.getElementById('formEquipamento').addEventListener('submit', function(event) {
            if (!document.getElementById('descricao').value || !document.getElementById('tipo').value) {
                event.preventDefault();
                alert('Todos os campos são obrigatórios!');
            }
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de bateria</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #007bff;
            color: #fff;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
            font-size: 1.2rem;
        }

        .content-cell {
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e9ecef;
            border-radius: 10px;
            text-align: center;
            width: 100%;
        }

        .btn-group-custom .btn {
            width: 120px;
            margin: 0 5px;
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <?php
    include_once __DIR__ . "/../../../public/components/navbar.php"
    ?>
    <div class="container mt-5">
        <div class="table-container">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>--</th>
                        <th>Verificação de bateria</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            <div class="content-cell">
                                <table style="border: 0px">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form action="/syscheck/checklist/salvarinfobateria" method="POST">
                                                    <input type="hidden" name="idchecklist" value="<?= $idChecklist ?>">
                                                    <div class="form-group">
                                                        <label for="bateria">Selecione a bateria que está instalada na máquina</label>
                                                        <select class="form-control" name="fkbateria" id="">
                                                            <option value="--" selected disabled>Selecione a bateria</option>
                                                            <?php foreach ($listaBaterias as $bateria) { ?>
                                                                <option value="<?= $bateria->getIdBateria() ?>"><?= $bateria->getNumeroBateria() . " | " . $bateria->getDescricaoBateria() ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="nivelbateria">Nivel da bateria</label>
                                                        <input type="number" class="form-control" name="nivelbateria" placeholder="Digite o nível de carga da bateria" autocomplete="off" require min="0" max="100" step=1>
                                                    </div>
                                                    <button class="btn btn-success">Iniciar checklist</button>
                                                    <a class="btn btn-warning" href="/syscheck/checklist/abrirchamado">Abrir chamado sobre carrinho ou berço</a>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para observações -->
    <div class="modal fade" id="modalObservacao" tabindex="-1" role="dialog" aria-labelledby="modalObservacaoLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalObservacaoLabel">Adicionar Observação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const nivelBateriaInput = document.querySelector("input[name='nivelbateria']");

            form.addEventListener("submit", function(event) {
                const nivelBateria = parseInt(nivelBateriaInput.value);

                if (nivelBateria <= 40) {
                    event.preventDefault(); // Impede o envio do formulário
                    alert("Nível da bateria baixo! Para prosseguir com o checklist e uso dessa empilhadeira é necessário trocar a bateria do equipamento.");
                    nivelBateriaInput.focus(); // Mantém o usuário na página e foca no campo
                }
                // Se for maior que 40, o envio ocorre normalmente
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
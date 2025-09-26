<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
    <title>Retirada / Devolução de veículo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 100%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .resultado {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .resultado p {
            margin: 10px 0;
            font-size: 18px;
            text-align: left;
        }

        .resultado td {
            padding: 8px;
            text-align: left;
        }

        .resultado tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-inline label {
            margin-right: 10px;
        }

        .form-inline input {
            margin-right: 10px;
        }

        /* Adicionando regras de mídia queries para dispositivos móveis */
        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .resultado {
                padding: 10px;
            }
        }
    </style>
</head>


<div class="d-flex justify-content-center mt-60">
    <a href="/syscheck/index2.php" class="btn btn-primary mb-4">
        Home
    </a>
</div>
<?php


use Util\Sessao;

require __DIR__ . '/../../../../vendor/autoload.php';

// mostrar mensagem (se existir)
Sessao::mostrarMensagem();

// --- Garantir variáveis padrão para evitar "undefined variable" na view ---
// Caso o controller não tenha fornecido, usamos valores vazios / arrays vazios
$nome = $nome ?? '';
$fkUsuario = $fkUsuario ?? '';
$listaCarros = $listaCarros ?? [];
$data = $data ?? date('d/m/Y H:i');
$listaChecklists = $listaChecklists ?? [];

?>

<div class="container">
    <h4>Retirada / Devolução de veículo</h4>
    <br>

    <div class="container">
        <div class="row">
            <!-- Campo Nome -->
            <div class="form-group col-md-4">
                <label for="nome">Nome</label>
                <input type="text" id="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" readonly>
                <input type="hidden" id="fkusuario" name="fkusuario" value="<?= htmlspecialchars($fkUsuario) ?>">
            </div>

            <!-- Campo Empresa -->
            <div class="form-group col-md-3">
                <label for="veiculo">Veículo</label>
                <select name="veiculo" id="veiculo" class="form-control">
                    <option value="" selected disabled>Selecione o veículo</option>
                    <?php foreach ($listaCarros as $objeto) { ?>
                        <option value="<?= htmlspecialchars($objeto->getIdObjeto()) ?>"><?= htmlspecialchars($objeto->getDescricaoObjeto()) ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Campo Data -->
            <div class="form-group col-md-2">
                <label for="data">Data</label>
                <input type="text" id="data" class="form-control" value="<?= htmlspecialchars($data) ?>" readonly>
            </div>

            <!-- Campo status -->
            <div class="form-group col-md-3">
                <label for="status">Status do veículo</label>
                <input type="text" name="status" id="status" class="form-control" value="0" readonly>
            </div>
        </div>

        <form action="#" method="get">
            <div class="row">
                <!-- Campo Leitura do Crachá -->
                <div class="form-group col-md-12" style="text-align: center;">
                    <div style="width:100%; margin: 0 auto;">
                        <input type="text" name="tagdec" maxlength="10" class="form-control" style="height:80px; font-size:30px;" placeholder="Efetue a Leitura do Crachá" autocomplete="off" required id="tagdec">
                    </div>
                </div>
            </div>
        </form>
        <hr>

        <button id="registrarMovimentacao" class="btn btn-primary" style="width:100%; margin: 0 auto;">Registrar movimentação</button>

    </div>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Número</th>
                <th>Veículo</th>
                <th>Usuário</th>
                <th>Data inicio</th>
                <th>Data de devolução</th>
                <th>Status Checklist</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifica se é um array e tem elementos antes de iterar
            if (!empty($listaChecklists) && is_array($listaChecklists)) {
                foreach ($listaChecklists as $checklist) { ?>
                    <tr>
                        <td><?= htmlspecialchars($checklist->getIdChecklist()) ?></td>
                        <td><?= htmlspecialchars($checklist->getFkObjeto()) ?></td>
                        <td><?= htmlspecialchars($checklist->getFkUsuario()) ?></td>
                        <td><?= htmlspecialchars($checklist->getDataInicio()) ?></td>
                        <td><?= htmlspecialchars($checklist->getDataFim()) ?></td>
                        <td>
                            <?php
                            // Exibir status com cor — ajusta conforme tipo retornado pelo getter
                            $status = $checklist->getStatusChecklist();
                            $isPendente = (is_string($status) && strtolower($status) === 'pendente') || ($status == 1 && false); // mantenha a lógica conforme seu domínio
                            if ($isPendente) {
                                echo '<span style="color: red">' . htmlspecialchars($checklist->getStatusChecklist()) . '</span>';
                            } else {
                                echo '<span style="color: green">' . htmlspecialchars($checklist->getStatusChecklist()) . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum checklist encontrado.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

<script>
    document.getElementById("tagdec").focus();
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function() {
        $("#registrarMovimentacao").click(function() {

            let fkUsuario = $("#fkusuario").val();
            let fkVeiculo = $("#veiculo").val();
            //let data = $("#data").val().replace(/ /g, '+');
            let status = $("#status").val();

            console.log("Botão clicado");

            // Verificar se todos os campos são válidos
            if (fkUsuario == 0 || !fkUsuario) {
                alert("Usuário inválido!");
                return;
            }

            if (fkVeiculo == 0 || !fkVeiculo) {
                alert("Selecione um veículo!");
                return;
            }

            if (status == "0" || !status || status == "Erro ao obter status") {
                alert("Status inválido!");
                return;
            }

            // Criar a URL correta
            let url = `/syscheck/lista/salvarMovimentacao/${fkUsuario}/${fkVeiculo}/${status}`;
            //let url = '/syscheck/lista/selvarMovimentacao/' + fkUsuario + '/' +fkVeiculo+ '/' +data+ '/' +status;
            console.log(url);

            // Redirecionar para a URL
            window.location.href = url;
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#veiculo").change(function() {
            let fkVeiculo = $(this).val(); // Obtém o ID do veículo selecionado
            let statusField = $("#status"); // Campo de status

            if (!fkVeiculo) {
                statusField.val(""); // Limpa o campo se não houver veículo selecionado
                return;
            }

            $.ajax({
                url: `/syscheck/lista/verificarStatusVeiculo/${fkVeiculo}`,
                type: "GET",
                success: function(response) {
                    // Converte resposta numérica para status
                    let statusText = (response == 1) ? "Disponível" :
                        (response == 2) ? "Ocupado" :
                        "Erro ao obter status";

                    statusField.val(statusText);
                    //console.log(response); // Mover o console.log aqui
                },
                error: function() {
                    statusField.val("Erro ao obter status");
                }
            });
        });
    });
</script>



</body>

</html>
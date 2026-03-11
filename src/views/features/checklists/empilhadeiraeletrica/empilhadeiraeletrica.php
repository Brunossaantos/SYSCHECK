<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verificação de bateria</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
        }

        .page-container {
            max-width: 700px;
            margin: auto;
            margin-top: 70px;
        }

        .card-custom {
            background: #1e293b;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .card-title {
            font-size: 1.7rem;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-control {
            height: 52px;
            font-size: 1.1rem;
            background: #0f172a;
            border: 1px solid #334155;
            color: #e2e8f0;
        }

        .form-control:focus {
            background: #0f172a;
            border-color: #3b82f6;
            box-shadow: none;
        }

        label {
            font-weight: 500;
        }

        .btn-lg {
            height: 52px;
            font-size: 1.05rem;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .btn-main {
            flex: 2;
        }

        .btn-secondary {
            flex: 1;
        }

        .warning-box {
            display: none;
            background: #7f1d1d;
            color: #fecaca;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            font-size: 0.95rem;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="page-container">

            <div class="card-custom">

                <div class="card-title">
                    🔋 Verificação de bateria
                </div>

                <form action="/syscheck/checklist/salvarinfobateria" method="POST">

                    <input type="hidden" name="idchecklist" value="<?= $idChecklist ?>">

                    <div class="form-group">

                        <label>Selecione a bateria instalada na máquina</label>

                        <select class="form-control" name="fkbateria" required>

                            <option value="" disabled selected>Selecione a bateria</option>

                            <?php foreach ($listaBaterias as $bateria) { ?>

                                <option value="<?= $bateria->getIdBateria() ?>">
                                    <?= $bateria->getNumeroBateria() . " | " . $bateria->getDescricaoBateria() ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Nível da bateria (%)</label>

                        <input
                            type="number"
                            class="form-control"
                            name="nivelbateria"
                            placeholder="Digite o nível de carga"
                            autocomplete="off"
                            required
                            min="0"
                            max="100"
                            step="1">

                    </div>

                    <div class="warning-box" id="alertBateria">
                        ⚠ Nível de bateria abaixo do recomendado.
                        Para utilizar a empilhadeira é necessário substituir a bateria.
                    </div>


                    <div class="actions">

                        <button class="btn btn-success btn-lg btn-main">
                            Iniciar checklist
                        </button>

                        <a class="btn btn-outline-warning btn-lg btn-secondary"
                            href="/syscheck/checklist/abrirchamado">

                            Abrir chamado carrinho / berço

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.querySelector("form");
            const nivelInput = document.querySelector("input[name='nivelbateria']");
            const alerta = document.getElementById("alertBateria");

            nivelInput.addEventListener("input", function() {

                const nivel = parseInt(this.value);

                if (nivel <= 40) {

                    alerta.style.display = "block";

                } else {

                    alerta.style.display = "none";

                }

            });


            form.addEventListener("submit", function(e) {

                const nivel = parseInt(nivelInput.value);

                if (nivel <= 40) {

                    e.preventDefault();

                    alerta.style.display = "block";

                    nivelInput.focus();

                }

            });

        });
    </script>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</body>

</html>
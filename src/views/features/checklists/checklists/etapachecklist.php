<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Etapa</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<?php

$observacao = isset($_POST['observacao']) ? $_POST['observacao'] : "";

$url = $_SERVER['REQUEST_URI'];
$partes = explode('/', $url);
$idChecklist = $partes[4];

?>

<body class="bg-slate-900">

    <div class="max-w-4xl mx-auto mt-10 px-4">

        <div class="bg-slate-800 rounded-xl shadow-lg p-6">

            <!-- Cabeçalho -->

            <div class="flex justify-between items-center border-b border-slate-700 pb-3 mb-6">

                <h2 class="text-xl font-semibold text-white">
                    Etapa <?= $numeroEtapa ?>
                </h2>

                <span class="text-slate-300 text-sm">
                    <?= $titulo ?>
                </span>

            </div>

            <!-- Conteúdo -->

            <div class="bg-slate-900 rounded-lg p-6 text-center text-slate-200 mb-6">

                <div class="text-lg">
                    <?= $conteudo ?>
                </div>

                <?php if (!empty($observacao)) { ?>

                    <div class="mt-6 text-yellow-400 text-sm">
                        <strong>Observação:</strong><br>
                        <?= htmlspecialchars($observacao) ?>
                    </div>

                <?php } ?>

            </div>

            <!-- Botões -->

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <a href="/syscheck/etaparealizada/confirmaretapa/<?= $idChecklist ?>/<?= $fkTipo ?>/<?= $numeroEtapa ?>/<?= urlencode($observacao) ?>"
                    class="btn-success bg-green-600 hover:bg-green-700 text-white font-semibold py-4 rounded-lg text-center transition">

                    ✔ Aprovado

                </a>

                <a href="/syscheck/etaparealizada/reprovarEtapa/<?= $idChecklist ?>/<?= $fkTipo ?>/<?= $numeroEtapa ?>/<?= urlencode($observacao) ?>"
                    class="btn-warning bg-red-600 hover:bg-red-700 text-white font-semibold py-4 rounded-lg text-center transition">

                    ✖ Reprovado

                </a>

                <button data-toggle="modal" data-target="#modalFoto"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 rounded-lg transition">

                    📷 Foto

                </button>

                <button data-toggle="modal" data-target="#modalObservacao"
                    class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-4 rounded-lg transition">

                    💬 Observação

                </button>

            </div>

        </div>

    </div>

    <!-- MODAL OBSERVAÇÃO -->

    <div class="modal fade" id="modalObservacao" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Adicionar Observação</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>

                </div>

                <form method="POST" action="">

                    <div class="modal-body">

                        <div class="form-group">

                            <label>Observação</label>

                            <textarea class="form-control" id="observacao" name="observacao" rows="4"><?= htmlspecialchars($observacao) ?></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Fechar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Salvar Observação
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <!-- MODAL FOTO -->

    <div class="modal fade" id="modalFoto" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">Enviar Foto</h5>

                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>

                </div>

                <form method="POST" action="/syscheck/foto/uploadimagem" enctype="multipart/form-data">

                    <input type="hidden" name="url" value="<?= $url ?>">
                    <input type="hidden" name="fkchecklist" value="<?= $idChecklist ?>">
                    <input type="hidden" name="numeroEtapa" value="<?= $numeroEtapa ?>">

                    <div class="modal-body">

                        <div class="form-group">

                            <label>Escolher uma foto</label>

                            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Fechar
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Enviar Foto
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


    <!-- SCRIPT DE VALIDAÇÃO -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const fotoObrigatoria = <?= json_encode($fotoObrigatoria); ?>;

            const idChecklist = "<?= $idChecklist ?>";
            const numeroEtapa = "<?= $numeroEtapa ?>";

            function verificarFoto() {

                const urlVerificacao = `/syscheck/checklist/verificarFotoPorEtapa/${idChecklist}/${numeroEtapa}`;

                return fetch(urlVerificacao)
                    .then(response => response.text())
                    .then(data => data.trim())
                    .catch(error => {

                        console.error("Erro ao verificar a foto:", error);
                        alert("Erro ao verificar a foto.");

                        return "";

                    });

            }


            document.querySelector(".btn-success").addEventListener("click", function(event) {

                if (fotoObrigatoria) {

                    event.preventDefault();

                    verificarFoto().then(foto => {

                        if (foto === "") {

                            alert("Essa etapa exige que uma foto seja enviada.");

                        } else {

                            window.location.href = `/syscheck/etaparealizada/confirmaretapa/${idChecklist}/<?= $fkTipo ?>/${numeroEtapa}`;

                        }

                    });

                }

            });


            document.querySelector(".btn-warning").addEventListener("click", function(event) {

                event.preventDefault();

                let observacao = document.getElementById("observacao").value.trim();

                if (!observacao) {

                    alert("Para reprovar é obrigatório adicionar uma observação.");

                    return;

                }

                verificarFoto().then(foto => {

                    if (foto === "") {

                        alert("Para reprovar é obrigatório enviar uma foto.");

                    } else {

                        window.location.href = `/syscheck/etaparealizada/reprovarEtapa/${idChecklist}/<?= $fkTipo ?>/${numeroEtapa}/${encodeURIComponent(observacao)}`;

                    }

                });

            });

        });
    </script>

</body>

</html>
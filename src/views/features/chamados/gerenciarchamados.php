<!DOCTYPE html>
<html lang="pt-BR">

<?php

use Util\Sessao;

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Chamados</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery + DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        /* MOBILE - COLETOR EA520 */
        @media(max-width: 650px) {

            /* Esconde o header da tabela */
            #chamadosTable thead {
                display: none;
            }

            /* Cada linha vira card */
            #chamadosTable tbody tr {
                display: block;
                background: #1f2937;
                margin-bottom: 15px;
                padding: 15px;
                border-radius: 14px;
                border: 1px solid #374151;
            }

            /* Cada célula vira linha com label */
            #chamadosTable tbody tr td {
                display: flex;
                justify-content: space-between;
                padding: 6px 0;
                border: none !important;
                font-size: 15px;
            }

            /* Nome da coluna (label) */
            #chamadosTable tbody tr td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #93c5fd;
                margin-right: 10px;
                text-align: left;
            }

            /* Select ocupa toda largura */
            .status-select {
                width: 100%;
                margin-top: 5px;
            }

           
        }
    </style>

</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <!-- MENU -->
    <div class="w-full flex justify-center items-center space-x-20 max-w-6xl mt-16 mx-auto top-links">
        <a href="/syscheck/index2.php"
            class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition">Home</a>

        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition">Logout</a>
    </div>

    <main class="flex-grow p-6 mt-12">

        <div class="bg-gray-800 rounded-2xl shadow-lg p-6 w-full max-w-6xl mx-auto">

            <h2 class="text-2xl font-bold text-center text-white mb-6">Gerenciamento de Chamados Abertos</h2>

            <table id="chamadosTable" class="display w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th>Número do chamado</th>
                        <th>Abertura</th>
                        <th>Equipamento</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Usuário</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    if (!is_array($listaChamados)) $listaChamados = [];

                    $permitidos = [25, 60];
                    $statusList = [
                        1 => "Aguardando manutenção",
                        2 => "Em manutenção",
                        3 => "Aguardando peça",
                        4 => "Finalizado"
                    ];

                    foreach ($listaChamados as $chamado): ?>
                        <tr>

                            <td data-label="Número do chamado">
                                <a href="/syscheck/chamado/selecionarChamado/<?= $chamado->getIdChamado() ?>"
                                    class="text-blue-400 hover:underline">
                                    <?= $chamado->getIdChamado() ?>
                                </a>
                            </td>

                            <td data-label="Abertura"><?= $chamado->getDataAberturaChamado() ?></td>

                            <td data-label="Equipamento"><?= $chamado->getNomeEquipamento() ?></td>

                            <td data-label="Descrição" class="italic text-gray-400">
                                <?php
                                $desc = $chamado->getDescricaoChamado();
                                echo strlen($desc) > 30 ? substr($desc, 0, 30) . '...' : $desc;
                                ?>
                            </td>

                            <td data-label="Status">
                                <?php if (in_array(Sessao::idusuario(), $permitidos)): ?>
                                    <form class="status-form">
                                        <input type="hidden" name="idChamado" value="<?= $chamado->getIdChamado() ?>">

                                        <select name="status"
                                            class="status-select bg-gray-700 text-gray-200 px-3 py-2 rounded-lg border border-gray-600">
                                            <?php foreach ($statusList as $num => $label): ?>
                                                <option value="<?= $num ?>" <?= $num == $chamado->getStatusChamado() ? "selected" : "" ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <?= $statusList[$chamado->getStatusChamado()] ?>
                                <?php endif; ?>
                            </td>

                            <td data-label="Usuário"><?= $chamado->getNomeUsuario() ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </main>

    <script>
        // DataTables
        $(document).ready(function() {
            $('#chamadosTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                pageLength: 25,
                lengthMenu: [25, 50, 75, 100],
                language: {
                    search: "Pesquisar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    paginate: {
                        next: "Próximo",
                        previous: "Anterior"
                    }
                }
            });
        });

        // AJAX 
        document.querySelectorAll(".status-select").forEach(select => {
            select.addEventListener("change", async (e) => {
                const form = e.target.closest("form");
                const data = new FormData(form);

                const res = await fetch("/syscheck/chamado/alterarStatus", {
                    method: "POST",
                    body: data
                });

                if (res.ok) {
                    const row = form.closest("tr");
                    row.style.background = "#1e3a8a";
                    setTimeout(() => row.style.background = "", 600);
                } else {
                    alert("Erro ao atualizar status!");
                }
            });
        });
    </script>

</body>

</html>
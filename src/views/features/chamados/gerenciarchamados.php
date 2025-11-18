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

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<style>
    /* Ajustes visuais do seletor de quantidade no DataTables */
    .dataTables_length select {
        background-color: #1f2937 !important;
        color: #e5e7eb !important;
        border: 1px solid #4b5563 !important;
        padding: 5px;
        border-radius: 6px;
    }

    .dataTables_length label {
        color: #e5e7eb !important;
    }
</style>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <!-- Navegação principal -->
    <div class="w-full flex justify-center items-center space-x-20 max-w-6xl mt-16 mx-auto">
        <a href="/syscheck/index2.php"
            class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>

        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>

    <main class="flex-grow p-6 mt-16">
        <div class="bg-gray-800 rounded-2xl shadow-lg p-6 w-full max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold text-center text-white mb-6">Gerenciamento de Chamados Abertos</h2>

            <div class="overflow-x-auto">
                <table id="chamadosTable" class="display w-full border border-gray-700 rounded-lg overflow-hidden">
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
                        $permitidos = [25, 64];
                        $statusList = [1 => "Aguardando manutenção", 2 => "Em manutenção", 3 => "Aguardando peça", 4 => "Finalizado"];

                        /** Loop principal da tabela */
                        foreach ($listaChamados as $chamado): ?>
                            <tr>
                                <td>
                                    <!-- Link para detalhes do chamado -->
                                    <a href="/syscheck/chamado/selecionarChamado/<?= $chamado->getIdChamado() ?>"
                                        class="text-blue-400 hover:underline">
                                        <?= $chamado->getIdChamado() ?>
                                    </a>
                                </td>

                                <td><?= $chamado->getDataAberturaChamado() ?></td>
                                <td><?= $chamado->getNomeEquipamento() ?></td>

                                <!-- Descrição encurtada para manter visual limpo -->
                                <td class="italic text-gray-400">
                                    <?php
                                    $desc = $chamado->getDescricaoChamado();
                                    echo strlen($desc) > 25 ? substr($desc, 0, 25) . '...' : $desc;
                                    ?>
                                </td>

                                <td>
                                    <?php if (in_array(Sessao::idusuario(), $permitidos)): ?>
                                        <!-- Formulário de alteração de status (AJAX) -->
                                        <form class="status-form">
                                            <input type="hidden" name="idChamado" value="<?= $chamado->getIdChamado() ?>">

                                            <select name="status"
                                                class="status-select bg-gray-700 text-gray-200 px-3 py-1 rounded-lg text-sm border border-gray-600">
                                                <?php foreach ($statusList as $num => $label): ?>
                                                    <option value="<?= $num ?>" <?= $num == $chamado->getStatusChamado() ? "selected" : "" ?>>
                                                        <?= $label ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>

                                    <?php else: ?>
                                        <!-- Usuário sem permissão apenas visualiza -->
                                        <?= $statusList[$chamado->getStatusChamado()] ?>
                                    <?php endif; ?>
                                </td>

                                <td><?= $chamado->getNomeUsuario() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        //DataTables
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
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        first: "Primeiro",
                        last: "Último",
                        next: "Próximo",
                        previous: "Anterior"
                    }
                }
            });
        });

        //Envio AJAX para alterar status do chamado

        document.querySelectorAll(".status-select").forEach(select => {
            select.addEventListener("change", async (e) => {
                const form = e.target.closest(".status-form");
                const formData = new FormData(form);

                try {
                    const res = await fetch("/syscheck/chamado/alterarStatus", {
                        method: "POST",
                        body: formData
                    });

                    if (res.ok) {
                        const row = form.closest("tr");
                        row.style.transition = "background 0.4s";
                        row.style.background = "#1e3a8a";
                        setTimeout(() => {
                            row.style.background = "";
                        }, 600);


                    } else {
                        alert("Erro ao atualizar status!");
                    }

                } catch (err) {
                    alert("Erro na requisição: " + err);
                }
            });
        });
    </script>

</body>

</html>
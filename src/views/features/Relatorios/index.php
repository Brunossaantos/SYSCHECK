<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center p-8 font-sans">

    <?php
    require_once __DIR__ . '/../../../controllers/RelatorioController.php';

    use Controller\RelatorioController;

    $controller = new RelatorioController();
    $listaEquipamentos = $controller->listarTodosEquipamentos();
    ?>

    <!-- Navegação -->
    <div class="w-full flex justify-center gap-4 mb-8">
        <a href="/syscheck/index2.php"
            class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>
        <a href="/syscheck/usuario/logout"
            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>

    <!-- Card Principal -->
    <div class="bg-white p-8 rounded-2xl shadow w-full max-w-4xl mb-8">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Relatórios</h1>

        <!-- Abas -->
        <div class="flex border-b border-gray-200 mb-6">
            <button id="tabAprovados"
                onclick="trocarAba('aprovados')"
                class="px-6 py-3 font-medium text-sm transition border-b-2 border-gray-700 text-gray-700">
                Itens Aprovados
            </button>
            <button id="tabReprovados"
                onclick="trocarAba('reprovados')"
                class="px-6 py-3 font-medium text-sm transition border-b-2 border-transparent text-gray-400 hover:text-gray-600">
                Itens Reprovados
            </button>
        </div>

        <!-- Formulário: Aprovados -->
        <div id="painelAprovados">
            <form id="formRelatorio" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Equipamento -->
                    <div>
                        <label for="fkequipamentoAprovados" class="block mb-2 font-medium text-gray-700">Equipamento</label>
                        <select name="id_equipamento" id="fkequipamentoAprovados"
                            class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-600 focus:outline-none">
                            <option value="">Todos</option>
                            <?php foreach ($listaEquipamentos as $equip) { ?>
                                <option value="<?= htmlspecialchars($equip['DESCRICAO_OBJETO']) ?>">
                                    <?= htmlspecialchars($equip['DESCRICAO_OBJETO']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Data -->
                    <div>
                        <label for="data_relatorio" class="block mb-2 font-medium text-gray-700">Data</label>
                        <input type="date" id="data_relatorio" name="data_relatorio"
                            class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-600 focus:outline-none"
                            value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                    Gerar Relatório
                </button>
            </form>
        </div>

        <!-- Formulário: Reprovados -->
        <div id="painelReprovados" class="hidden">
            <form id="formRelatorioReprovados" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Equipamento -->
                    <div>
                        <label for="fkequipamentoReprovados" class="block mb-2 font-medium text-gray-700">Equipamento</label>
                        <select name="id_equipamento" id="fkequipamentoReprovados"
                            class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-600 focus:outline-none">
                            <option value="">Todos</option>
                            <?php foreach ($listaEquipamentos as $equip) { ?>
                                <option value="<?= htmlspecialchars($equip['ID_OBJETO']) ?>">
                                    <?= htmlspecialchars($equip['DESCRICAO_OBJETO']) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Intervalo de datas -->
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <label for="data_inicio" class="block mb-2 font-medium text-gray-700">De</label>
                            <input type="date" id="data_inicio" name="data_inicio"
                                class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-600 focus:outline-none"
                                value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="flex-1">
                            <label for="data_fim" class="block mb-2 font-medium text-gray-700">Até</label>
                            <input type="date" id="data_fim" name="data_fim"
                                class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-600 focus:outline-none"
                                value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                    Gerar Relatório
                </button>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div id="tabelaResultados" class="w-full max-w-7xl"></div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        function trocarAba(aba) {
            var isAprovados = aba === 'aprovados';

            // Painéis
            document.getElementById('painelAprovados').classList.toggle('hidden', !isAprovados);
            document.getElementById('painelReprovados').classList.toggle('hidden', isAprovados);

            // Estilo das abas
            var tabAprovados = document.getElementById('tabAprovados');
            var tabReprovados = document.getElementById('tabReprovados');

            if (isAprovados) {
                tabAprovados.className = 'px-6 py-3 font-medium text-sm transition border-b-2 border-gray-700 text-gray-700';
                tabReprovados.className = 'px-6 py-3 font-medium text-sm transition border-b-2 border-transparent text-gray-400 hover:text-gray-600';
            } else {
                tabReprovados.className = 'px-6 py-3 font-medium text-sm transition border-b-2 border-gray-700 text-gray-700';
                tabAprovados.className = 'px-6 py-3 font-medium text-sm transition border-b-2 border-transparent text-gray-400 hover:text-gray-600';
            }

            // Limpar resultados ao trocar de aba
            $('#tabelaResultados').html('');
        }

        $(document).ready(function() {

            // Relatório de Aprovados
            $('#formRelatorio').submit(function(e) {
                e.preventDefault();

                var equipId = $('#fkequipamentoAprovados').val();
                var data = $('#data_relatorio').val();

                $.post('/syscheck/src/views/features/Relatorios/gerarRelatorioAjax.php', {
                    id_equipamento: equipId,
                    data_relatorio: data
                }, function(response) {
                    $('#tabelaResultados').html(response);
                });
            });

            // Relatório de Reprovados
            $('#formRelatorioReprovados').submit(function(e) {
                e.preventDefault();

                var equipId = $('#fkequipamentoReprovados').val();
                var dataInicio = $('#data_inicio').val();
                var dataFim = $('#data_fim').val();

                $.post('/syscheck/src/views/features/Relatorios/gerarItensReprovadosAjax.php', {
                    id_equipamento: equipId,
                    data_inicio: dataInicio,
                    data_fim: dataFim
                }, function(response) {
                    $('#tabelaResultados').html(response);
                });
            });

        });
    </script>

</body>

</html>
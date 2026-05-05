<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Uso</title>

    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Card de Filtro -->
    <div class="bg-white p-8 rounded-2xl shadow w-full max-w-4xl mb-8">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Relatório de Uso de Equipamentos</h1>

        <form id="formRelatorioUso" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Equipamento -->
                <div>
                    <label for="fkequipamento" class="block mb-2 font-medium text-gray-700">Equipamento</label>
                    <select name="id_equipamento" id="fkequipamento"
                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-600 focus:outline-none">
                        <option value="">Todos</option>
                        <?php foreach ($listaEquipamentos as $equip): ?>
                            <option value="<?= htmlspecialchars($equip['ID_OBJETO']) ?>">
                                <?= htmlspecialchars($equip['DESCRICAO_OBJETO']) ?>
                            </option>
                        <?php endforeach; ?>
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

    <!-- Resultados -->
    <div id="tabelaResultados" class="w-full max-w-7xl"></div>

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
        $(document).ready(function() {
            $('#formRelatorioUso').submit(function(e) {
                e.preventDefault();

                $('#tabelaResultados').html('<p class="text-center text-gray-500 py-8">Carregando...</p>');

                $.post('/syscheck/src/views/features/Relatorios/gerarUsoAjax.php',{
                    id_equipamento: $('#fkequipamento').val(),
                    data_inicio: $('#data_inicio').val(),
                    data_fim: $('#data_fim').val()
                }, function(response) {
                    $('#tabelaResultados').html(response);
                });
            });
        });
    </script>

</body>

</html>
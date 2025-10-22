<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens Reprovados</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col items-center p-8">

<?php
require_once __DIR__ . '/../../../controllers/RelatorioController.php';
use Controller\RelatorioController;

$controller = new RelatorioController();
$listaEquipamentos = $controller->listarTodosEquipamentos();
?>

<div class="w-full flex justify-center gap-4 mb-8">
    <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Home</a>
    <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Logout</a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow w-full max-w-4xl mb-8">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Itens Reprovados</h1>

    <form id="formReprovados" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Equipamento -->
            <div>
                <label for="fkequipamento" class="block mb-2 font-medium text-gray-700">Equipamento</label>
                <select name="id_equipamento" id="fkequipamento"
                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Todos</option>
                    <?php foreach ($listaEquipamentos as $equip) { ?>
                        <option value="<?= $equip['ID_OBJETO'] ?>"><?= $equip['DESCRICAO_OBJETO'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Data Início -->
            <div>
                <label for="data_inicio" class="block mb-2 font-medium text-gray-700">Data Início</label>
                <input type="date" id="data_inicio" name="data_inicio"
                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       value="<?= date('Y-m-d') ?>">
            </div>

            <!-- Data Fim -->
            <div>
                <label for="data_fim" class="block mb-2 font-medium text-gray-700">Data Fim</label>
                <input type="date" id="data_fim" name="data_fim"
                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Gerar Relatório
        </button>
    </form>
</div>

<div id="tabelaResultados" class="w-full max-w-8x1"></div>

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
    $('#formReprovados').submit(function(e) {
        e.preventDefault();

        var equipId = $('#fkequipamento').val();
        var dataInicio = $('#data_inicio').val();
        var dataFim = $('#data_fim').val();

        $.post('gerarItensReprovadosAjax.php', {
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

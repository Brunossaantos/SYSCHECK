<?php

require_once __DIR__ . '/../../../controllers/RelatorioController.php';

use Controller\RelatorioController;

$controller = new RelatorioController();

$idEquip    = $_POST['id_equipamento'] ?? null;
$dataInicio = $_POST['data_inicio']    ?? null;
$dataFim    = $_POST['data_fim']       ?? null;

$dados = $controller->gerarRelatorioUso($idEquip, $dataInicio, $dataFim);

$detalhado = $dados['detalhado'];
$resumo    = $dados['resumo'];
$porDia    = $dados['porDia'];

if (empty($detalhado)) {
    echo "<p class='text-center text-gray-600 py-8'>Nenhum registro encontrado para o período informado.</p>";
    exit;
}

?>

<!-- =====================================================
     SEÇÃO 1: RESUMO POR DIA
====================================================== -->
<div class="bg-white rounded-2xl shadow p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Resumo por Dia</h2>
    <table id="tabelaPorDia" class="min-w-full border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-gray-600 text-white">
            <tr>
                <th class="px-4 py-3 border-b text-left">Data</th>
                <th class="px-4 py-3 border-b text-center">Equipamentos Utilizados</th>
                <th class="px-4 py-3 border-b text-center">Total de Usos</th>
                <th class="px-4 py-3 border-b text-center">Total de Horas (Horímetro)</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($porDia as $linha): ?>
            <tr class="hover:bg-gray-100 transition">
                <td class="px-4 py-2 border-b"><?= date('d/m/Y', strtotime($linha->DATA)) ?></td>
                <td class="px-4 py-2 border-b text-center"><?= $linha->EQUIPAMENTOS_EM_USO ?></td>
                <td class="px-4 py-2 border-b text-center"><?= $linha->TOTAL_USOS ?></td>
                <td class="px-4 py-2 border-b text-center"><?= $linha->TOTAL_HORAS_DIA ?>h</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- =====================================================
     SEÇÃO 2: TOTAL DE HORAS POR EQUIPAMENTO
====================================================== -->
<div class="bg-white rounded-2xl shadow p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Total de Horas por Equipamento</h2>
    <table id="tabelaResumo" class="min-w-full border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-gray-600 text-white">
            <tr>
                <th class="px-4 py-3 border-b text-left">Equipamento</th>
                <th class="px-4 py-3 border-b text-center">Total de Usos</th>
                <th class="px-4 py-3 border-b text-center">Total de Horas (Horímetro)</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($resumo as $linha): ?>
            <tr class="hover:bg-gray-100 transition">
                <td class="px-4 py-2 border-b font-medium"><?= htmlspecialchars($linha->EQUIPAMENTO) ?></td>
                <td class="px-4 py-2 border-b text-center"><?= $linha->TOTAL_USOS ?></td>
                <td class="px-4 py-2 border-b text-center"><?= $linha->TOTAL_HORAS ?>h</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- =====================================================
     SEÇÃO 3: HISTÓRICO DETALHADO
====================================================== -->
<div class="bg-white rounded-2xl shadow p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-4">Histórico Detalhado de Uso</h2>
    <table id="tabelaDetalhado" class="min-w-full border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-gray-600 text-white">
            <tr>
                <th class="px-4 py-3 border-b text-left">Data</th>
                <th class="px-4 py-3 border-b text-left">Equipamento</th>
                <th class="px-4 py-3 border-b text-left">Operador</th>
                <th class="px-4 py-3 border-b text-center">Horímetro Inicial</th>
                <th class="px-4 py-3 border-b text-center">Horímetro Final</th>
                <th class="px-4 py-3 border-b text-center">Horas de Uso</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($detalhado as $linha): ?>
            <tr class="hover:bg-gray-100 transition">
                <td class="px-4 py-2 border-b"><?= htmlspecialchars($linha->DATA_INICIO) ?></td>
                <td class="px-4 py-2 border-b font-medium"><?= htmlspecialchars($linha->EQUIPAMENTO) ?></td>
                <td class="px-4 py-2 border-b"><?= htmlspecialchars($linha->OPERADOR) ?></td>
                <td class="px-4 py-2 border-b text-center"><?= $linha->HORIMETRO_INICIAL ?? '—' ?></td>
                <td class="px-4 py-2 border-b text-center">
                    <?php if ($linha->HORIMETRO_FINAL === null): ?>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">
                            Em uso
                        </span>
                    <?php else: ?>
                        <?= $linha->HORIMETRO_FINAL ?>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-2 border-b text-center">
                    <?php if ($linha->HORAS_USO === null): ?>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">
                            Em uso
                        </span>
                    <?php else: ?>
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded font-medium">
                            <?= $linha->HORAS_USO ?>h
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        var opcoesPadrao = {
            responsive: true,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                search: 'Buscar',
            },
            dom: '<"flex justify-between items-center mb-4"Bf>t<"flex justify-between items-center mt-4"lp>',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: 'Copiar',
                    className: 'bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    className: 'bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700'
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    className: 'bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    className: 'bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    className: 'bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700'
                }
            ]
        };

        $('#tabelaPorDia').DataTable(opcoesPadrao);
        $('#tabelaResumo').DataTable(opcoesPadrao);
        $('#tabelaDetalhado').DataTable(opcoesPadrao);
    });
</script>
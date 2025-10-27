<?php

require_once __DIR__ . '/../../../controllers/RelatorioController.php';

use Controller\RelatorioController;

$controller = new RelatorioController();

$idTipo  = $_POST['id_tipo_checklist'] ?? null;
$idEquip = $_POST['id_equipamento'] ?? null;
$data    = $_POST['data_relatorio'] ?? null;

$relatorios = $controller->gerarRelatorioVisaoGeral($idTipo, $idEquip, $data);

if (empty($relatorios)) {
    echo "<p class='text-center text-gray-600'>Nenhum relatório encontrado.</p>";
    exit;
}

?>

<div class="overflow-x-auto mt-6">
    <table id="tabelaRelatorios" class="min-w-full border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-gray-600 text-white">
            <tr>
                <th class="px-4 py-3 border-b text-left">Checklist</th>
                <th class="px-4 py-3 border-b text-left">Usuário</th>
                <th class="px-4 py-3 border-b text-left">Tipo</th>
                <th class="px-4 py-3 border-b text-left">Objeto</th>
                <th class="px-4 py-3 border-b text-left">Início</th>
                <th class="px-4 py-3 border-b text-left">Fim</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($relatorios as $r): ?>
                <tr class='hover:bg-gray-100 transition'>
                    <td class="px-4 py-2">
                        <a href="/syscheck/checklist/checklistFinalizado/<?= htmlspecialchars($r->NUMERO_CHECKLIST) ?>"
                            class="text-blue-500 hover:underline">
                            <?= htmlspecialchars($r->NUMERO_CHECKLIST) ?>
                        </a>
                    </td>
                    <td class='px-4 py-2 border-b'><?= $r->USUARIO ?></td>
                    <td class='px-4 py-2 border-b font-semibold'><?= $r->TIPO ?></td>
                    <td class='px-4 py-2 border-b'><?= $r->OBJETO ?></td>
                    <td class='px-4 py-2 border-b'><?= $r->DATA_INICIO_FORMAT ?></td>
                    <td class='px-4 py-2 border-b'><?= $r->DATA_FIM_FORMAT ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#tabelaRelatorios').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                search: 'Buscar',
            },
            dom: '<"flex justify-between items-center mb-4"Bf>t<"flex justify-between items-center mt-4"lp>',
            buttons: [{
                    extend: 'copyHtml5',
                    text: 'Copiar',
                    className: 'bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700'
                },
                {
                    extend: 'excelHtml5',
                    text: ' Excel',
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
        });
    });
</script>
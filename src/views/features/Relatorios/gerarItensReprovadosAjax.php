<?php
require_once __DIR__ . '/../../../controllers/RelatorioController.php';

use Controller\RelatorioController;

$controller = new RelatorioController();

// Recebe filtros do POST
$idEquip   = $_POST['id_equipamento'] ?? null;
$dataInicio = $_POST['data_inicio'] ?? null;
$dataFim    = $_POST['data_fim'] ?? null;

// Puxa os itens reprovados do DAO
$itens = $controller->listarItensReprovados($idEquip, $dataInicio, $dataFim);

if (empty($itens)) {
    echo "<p class='text-center text-gray-600 mt-4'>Nenhum item reprovado encontrado para o período selecionado.</p>";
    exit;
}
?>

<div class="overflow-x-auto mt-6">
    <table id="tabelaItensReprovados" class="min-w-full border border-gray-200 rounded-lg shadow-lg">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-3 border-b text-left">Checklist</th>
                <th class="px-4 py-3 border-b text-left">Equipamento</th>
                <th class="px-4 py-3 border-b text-left">Etapa</th>
                <th class="px-4 py-3 border-b text-left">Observação</th>
                <th class="px-4 py-3 border-b text-left">Data Início</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($itens as $checklist): ?>
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-4 py-2 border-b"><?= htmlspecialchars($checklist['FK_CHECKLIST']) ?></td>
                    <td class="px-4 py-2 border-b"><?= htmlspecialchars($checklist['DESCRICAO_OBJETO'] ?? '-') ?></td>
                    <td class="px-4 py-2 border-b"><?= htmlspecialchars($checklist['NUMERO_ETAPA']) ?></td>
                    <td class="px-4 py-2 border-b"><?= htmlspecialchars($checklist['OBSERVACAO'] ?? '-') ?></td>
                    <td class="px-4 py-2 border-b"><?= htmlspecialchars($checklist['DATA_INICIO'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#tabelaItensReprovados').DataTable({
        responsive: true,
        pageLength: 5,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },
        dom: '<"flex justify-between items-center mb-4"Bf>t<"flex justify-between items-center mt-4"lp>',
        buttons: [
            { extend: 'copyHtml5', text: '📋 Copiar', className: 'bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700' },
            { extend: 'excelHtml5', text: '📊 Excel', className: 'bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700' },
            { extend: 'csvHtml5', text: '📝 CSV', className: 'bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700' },
            { extend: 'pdfHtml5', text: '📄 PDF', className: 'bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700', orientation: 'landscape', pageSize: 'A4' },
            { extend: 'print', text: '🖨️ Imprimir', className: 'bg-purple-600 text-white px-3 py-2 rounded-lg hover:bg-purple-700' }
        ]
    });
});
</script>

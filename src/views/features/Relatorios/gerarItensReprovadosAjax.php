<?php

require_once __DIR__ . '/../../../controllers/RelatorioController.php';

use Controller\RelatorioController;

$controller = new RelatorioController();

$idEquip = $_POST['id_equipamento'] ?? null;
$dataInicio = $_POST['data_inicio'] ?? null;
$dataFim = $_POST['data_fim'] ?? null;

// Obtém os dados reprovados
$relatorios = $controller->gerarRelatorioItensReprovados($idEquip, $dataInicio, $dataFim);

// Função para converter status numérico em texto
function traduzirStatus($status)
{
    return match ((int)$status) {
        1 => 'Em andamento',
        3 => 'Finalizado',
        default => 'Desconhecido',
    };
}
?>

<div class="bg-white p-6 rounded-2xl shadow-lg">
    <?php if (empty($relatorios)) { ?>
        <p class="text-center text-gray-600 text-lg">Nenhum item reprovado encontrado.</p>
    <?php } else { ?>
        <h2 class="text-xl font-semibold mb-4 text-gray-800 text-center">
            Itens Reprovados <?= !empty($dataInicio) && !empty($dataFim) ? "de " . date('d/m/Y', strtotime($dataInicio)) . " até " . date('d/m/Y', strtotime($dataFim)) : "" ?>
        </h2>

        <div class="overflow-x-auto">
            <table id="tabelaReprovados" class="display nowrap w-full text-sm">
                <thead class="bg-gray-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Número</th>
                        <th class="px-4 py-3 text-left">Equipamento</th>
                        <th class="px-4 py-3 text-left">Etapa</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Data Início</th>
                        <th class="px-4 py-3 text-left">Data Fim</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relatorios as $r) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="/syscheck/checklist/checklistFinalizado/<?= htmlspecialchars($r->FK_CHECKLIST) ?>"
                                    class="text-blue-500 hover:underline">
                                    <?= htmlspecialchars($r->FK_CHECKLIST) ?>
                                </a>
                            </td>
                            <td class="px-4 py-3"><?= htmlspecialchars($r->EQUIPAMENTO) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($r->NUMERO_ETAPA) ?></td>
                            <td class="px-4 py-3"><?= traduzirStatus($r->STATUS_CHECKLIST) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($r->DATA_INICIO) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($r->DATA_FIM) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<script>
    $(document).ready(function() {
        $('#tabelaReprovados').DataTable({
            responsive: true,
            dom: '<"flex justify-between items-center mb-4"Bf>t<"flex justify-between items-center mt-4"lp>',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            pageLength: 10,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            }
        });
    });
</script>
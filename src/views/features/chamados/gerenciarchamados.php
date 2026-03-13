<!DOCTYPE html>
<html lang="pt-BR">

<?php



use Util\Sessao;
use rn\RnChamado;

// Usuário logado
$usuario = Sessao::retornarUsuarioLogado();
if (!$usuario) {
    header("Location: /syscheck/login.php");
    exit;
}

$idUsuario = $usuario->getIdUsuario();
$idPerfil  = $usuario->getFkPerfil();

// Instancia o RNChamado com o ID do usuário da sessão
$rnChamado = new RnChamado($idUsuario);

// Perfis admin que podem ver todos os chamados
$permitidos = [1, 7, 8];

if (in_array($idPerfil, $permitidos)) {

    // 1,7,8 → veem todos
    $listaChamados = $rnChamado->listarChamados();
} elseif ($idPerfil == 3) {

    // Perfil 3 → seus chamados + chamados do perfil 2
    $listaChamados = $rnChamado->listarChamadosPorPerfis([2, 3]);
} elseif ($idPerfil == 5) {

    // Perfil 5 → seus chamados + chamados do perfil 4
    $listaChamados = $rnChamado->listarChamadosPorPerfis([4, 5]);
} else {

    // Perfis 2,4 e futuros → apenas próprios chamados
    $listaChamados = $rnChamado->listarChamadosPorUsuario();
}

// Status do chamado
$statusList = [
    1 => "Aguardando manutenção",
    2 => "Em manutenção",
    3 => "Aguardando peça",
    4 => "Finalizado"
];
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
        /* =========================
       MOBILE - COLETOR EA520
    ========================== */
        @media(max-width: 650px) {

            #chamadosTable thead {
                display: none;
            }

            #chamadosTable tbody tr {
                display: block;
                background: #1f2937;
                margin-bottom: 15px;
                padding: 15px;
                border-radius: 14px;
                border: 1px solid #374151;
            }

            #chamadosTable tbody tr td {
                display: flex;
                justify-content: space-between;
                padding: 6px 0;
                border: none !important;
                font-size: 15px;
            }

            #chamadosTable tbody tr td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #93c5fd;
                margin-right: 10px;
                text-align: left;
            }

            .status-select {
                width: 100%;
                margin-top: 5px;
            }
        }


        /* =========================
       DESKTOP - CORPORATIVO
    ========================== */
        @media (min-width: 651px) {

            table.dataTable,
            table.dataTable.no-footer {
                border: none !important;
            }

            #chamadosTable {
                border-collapse: collapse !important;
            }

            /* Grade suave */
            #chamadosTable th,
            #chamadosTable td {
                border: 1px solid rgba(255, 255, 255, 0.06) !important;
                padding: 14px !important;
            }

            /* Header um pouco mais destacado */
            #chamadosTable thead th {
                background: #374151 !important;
                font-weight: 600;
            }

            /* Hover mais refinado */
            #chamadosTable tbody tr:hover {
                background: rgba(255, 255, 255, 0.05) !important;
                transition: 0.2s ease;
            }
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <!-- MENU -->
    <div class="w-full flex justify-center items-center space-x-20 max-w-6xl mt-4 mx-auto top-links">
        <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition">Home</a>
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition">Logout</a>
    </div>

    <main class="flex-grow p-2 mt-2">
        <div class="bg-gray-800 rounded-2xl shadow-lg p-6 w-full max-w-7xl 2xl:max-w-[1600px] mx-auto">
            <h2 class="text-2xl font-bold text-center text-white mb-6">Chamados Abertos</h2>

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
                    <?php foreach ($listaChamados as $chamado): ?>
                        <tr>
                            <td data-label="Número do chamado">
                                <a href="/syscheck/chamado/selecionarChamado/<?= $chamado->getIdChamado() ?>" class="text-blue-400 hover:underline">
                                    <?= $chamado->getIdChamado() ?>
                                </a>
                            </td>
                            <td data-label="Abertura"><?= $chamado->getDataAberturaChamado() ?></td>
                            <td data-label="Equipamento"><?= $chamado->getNomeEquipamento() ?></td>
                            <td data-label="Descrição" class="italic text-gray-400">
                                <?php $desc = $chamado->getDescricaoChamado();
                                echo strlen($desc) > 30 ? substr($desc, 0, 30) . '...' : $desc; ?>
                            </td>
                            <td data-label="Status">
                                <?php if (in_array($idPerfil, $permitidos)): ?>
                                    <form class="status-form">
                                        <input type="hidden" name="idChamado" value="<?= $chamado->getIdChamado() ?>">
                                        <select name="status" class="status-select bg-gray-700 text-gray-200 px-3 py-2 rounded-lg border border-gray-600">
                                            <?php foreach ($statusList as $num => $label): ?>
                                                <option value="<?= $num ?>" <?= $num == $chamado->getStatusChamado() ? "selected" : "" ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <?= isset($statusList[$chamado->getStatusChamado()])
                                        ? $statusList[$chamado->getStatusChamado()]
                                        : 'Status desconhecido' ?>
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
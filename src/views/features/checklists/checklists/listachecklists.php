<?php

namespace views\features\checklist;

use DAO\DaoChecklist;
use DAO\DaoTiposChecklist;
use DAO\DaoObjeto;
use DAO\DaoUsuario;
use Util\Sessao;

// =====================
// Conexão
$conexao = (new \database\Conexao())->conectar(); // mysqli

// =====================
// Usuário logado
$usuarioLogado = Sessao::retornarUsuarioLogado();
$idUsuarioSessao = $usuarioLogado->getIdUsuario();
$idEmpresaSessao = $usuarioLogado->getFkEmpresa() ?: 1;

// =====================
// Instancia DAOs
$daoChecklist = new DaoChecklist($conexao, $idUsuarioSessao, $idEmpresaSessao);
$daoTiposChecklist = new DaoTiposChecklist($conexao, $idUsuarioSessao);
$daoObjeto = new DaoObjeto($conexao, $idUsuarioSessao);
$daoUsuario = new DaoUsuario($conexao, $idUsuarioSessao);

// =====================
// Listas para filtros
$listaTipos    = $daoTiposChecklist->retornarListaTiposChecklist();
$listaObjetos  = $daoObjeto->listarObjetos($idEmpresaSessao);
$listaUsuarios = $daoUsuario->retornarListaUsuarios();

// =====================
// Filtragem segura dos inputs
$numero        = htmlspecialchars($_GET['numero'] ?? '', ENT_QUOTES, 'UTF-8');
$data_inicio   = htmlspecialchars($_GET['data_inicio'] ?? '', ENT_QUOTES, 'UTF-8');
$tipoFiltro    = htmlspecialchars($_GET['tipo'] ?? '', ENT_QUOTES, 'UTF-8');
$objetoFiltro  = htmlspecialchars($_GET['objeto'] ?? '', ENT_QUOTES, 'UTF-8');
$usuarioFiltro = htmlspecialchars($_GET['usuario'] ?? '', ENT_QUOTES, 'UTF-8');
$statusFiltro  = htmlspecialchars($_GET['status'] ?? '0', ENT_QUOTES, 'UTF-8');
$empresaFiltro = htmlspecialchars($_GET['empresa'] ?? '0', ENT_QUOTES, 'UTF-8');
// =====================
// Array de filtros para DAO
$filtros = [
    'numero'       => $numero,
    'tipo'         => $tipoFiltro,
    'objeto'       => $objetoFiltro,
    'usuario'      => $usuarioFiltro,
    'status'       => $statusFiltro,
    'data_inicio'  => $data_inicio,
    'empresa'      => $empresaFiltro
];

// =====================
// Lista os checklists aplicando filtros e permissões
$listaChecklists = $daoChecklist->filtrarChecklists($filtros);

// =====================
// Cria array de usuários indexado pelo ID para exibir nomes corretamente
$usuariosPorId = [];
foreach ($listaUsuarios as $usuario) {
    $usuariosPorId[$usuario->getIdUsuario()] = $usuario;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Checklists - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }

        /* DataTables em tema escuro */
        .dataTables_length select,
        .dataTables_filter input {
            background-color: #1f2937;
            /* bg-gray-800 escuro */
            color: #ffffff;
            /* texto branco */
            border: 1px solid #374151;
            /* borda cinza */
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            /* rounded */
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #374151;
            /* botões de paginação escuros */
            color: #ffffff !important;
            /* texto branco */
            border: none;
            border-radius: 0.375rem;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #4b5563;
            color: #ffffff !important;
        }

        .dataTables_wrapper .dataTables_info {
            color: #ffffff;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            color: #738ab1;
            ;
        }
    </style>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- jQuery e DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <!-- Topo -->
    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/checklist" class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center rounded-lg font-medium transition transform hover:scale-105">Voltar</a>
        <a href="/syscheck/" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Home</a>
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Logout</a>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-center">Consultar Checklists</h1>

    <form method="GET" class="w-full max-w-4xl mx-auto bg-gray-800/80 border border-gray-700 rounded-xl p-5 mb-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-center gap-4">

            <div class="w-full md:w-64">
                <label for="empresa" class="block text-sm font-medium text-gray-300 mb-1">
                    Empresa
                </label>

                <select
                    name="empresa"
                    id="empresa"
                    class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="0" <?= $empresaFiltro == 0 ? 'selected' : '' ?>>Todas</option>
                    <option value="1" <?= $empresaFiltro == 1 ? 'selected' : '' ?>>Matriz</option>
                    <option value="2" <?= $empresaFiltro == 2 ? 'selected' : '' ?>>Filial</option>
                </select>
            </div>

            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold">
                Filtrar
            </button>

            <a
                href="/syscheck/checklist/listarChecklists"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg font-semibold text-center">
                Limpar
            </a>

        </div>
    </form>
    <!-- Tabela -->
    <div class="w-full max-w-full animate-fadeIn">
        <table id="checklistTable" class="min-w-full table-auto bg-gray-800 rounded-2xl overflow-hidden">
            <thead class="bg-gray-700">
                <tr>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Número</th>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Início</th>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Tipo</th>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Objeto</th>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Finalização</th>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Usuário</th>
                    <th class="py-2 px-4 text-left border-r border-gray-600">Status checklist</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listaChecklists)): ?>
                    <?php foreach ($listaChecklists as $checklist):
                        $fkUsuario = $checklist->getFkUsuario();
                        $usuarioNome = isset($usuariosPorId[$fkUsuario]) ? $usuariosPorId[$fkUsuario]->getNome() : $fkUsuario;

                        // Pega o tipo correto
                        $tipoObj = $listaTipos[$checklist->getFkTipo()] ?? null;
                        $tipoNome = $tipoObj ? $tipoObj->getDescricaoTipoChecklist() : $checklist->getFkTipo();

                        // Pega o objeto correto
                        $objetoObj = $listaObjetos[$checklist->getFkObjeto()] ?? null;
                        $objetoNome = $objetoObj ? $objetoObj->getDescricaoObjeto() : $checklist->getFkObjeto();
                    ?>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700">
                                <a href="/syscheck/checklist/checklistFinalizado/<?= (int)$checklist->getIdChecklist() ?>" class="text-blue-400 hover:underline">
                                    <?= (int)$checklist->getIdChecklist() ?>
                                </a>
                            </td>
                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700"><?= htmlspecialchars($checklist->getDataInicio() ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700"><?= htmlspecialchars($tipoNome ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700"><?= htmlspecialchars($objetoNome ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700"><?= htmlspecialchars($checklist->getDataFim() ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700"><?= htmlspecialchars($usuarioNome ?? '', ENT_QUOTES, 'UTF-8') ?></td>

                            <td class="py-2 px-4 border-b border-gray-700 border-r border-gray-700">
                                <?php if ((int)$checklist->getStatusChecklist() === 1): ?>
                                    <a href="/syscheck/etapaschecklist/continuarChecklist/<?= (int)$checklist->getIdChecklist() ?>" class="bg-yellow-500 hover:bg-yellow-600 w-40 h-14 flex items-center justify-center rounded-lg font-medium transition transform hover:scale-105 text-black">Em andamento</a>
                                <?php else: ?>
                                    <a href="/syscheck/checklist/checklistFinalizado/<?= (int)$checklist->getIdChecklist() ?>" class="bg-green-500 hover:bg-green-600 w-40 h-14 flex items-center justify-center rounded-lg font-medium transition transform hover:scale-105">Finalizado</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>



    <script>
        $(document).ready(function() {
            $('#checklistTable').DataTable({
                responsive: false, // desativa rolagem responsiva
                pageLength: 50, // mostrar mais linhas
                lengthMenu: [10, 25, 50, 100],
                order: [
                    [0, 'desc']
                ], // ordenar pelo número do checklist do maior para o menor
                scrollX: false, // desativa rolagem horizontal
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                },
                columnDefs: [{
                        orderable: false,
                        targets: 6
                    } // status não ordenável
                ]
            });

            // Ajuste de largura da tabela para ocupar toda a tela
            $('#checklistTable').css('width', '70%');
        });
    </script>
</body>

</html>
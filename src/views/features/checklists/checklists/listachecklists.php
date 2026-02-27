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
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <?php
    // Filtragem segura dos inputs
    $numero        = htmlspecialchars($_GET['numero'] ?? '', ENT_QUOTES, 'UTF-8');
    $data_inicio   = htmlspecialchars($_GET['data_inicio'] ?? '', ENT_QUOTES, 'UTF-8');
    $tipoFiltro    = htmlspecialchars($_GET['tipo'] ?? '', ENT_QUOTES, 'UTF-8');
    $objetoFiltro  = htmlspecialchars($_GET['objeto'] ?? '', ENT_QUOTES, 'UTF-8');
    $usuarioFiltro = htmlspecialchars($_GET['usuario'] ?? '', ENT_QUOTES, 'UTF-8');
    $statusFiltro  = htmlspecialchars($_GET['status'] ?? '0', ENT_QUOTES, 'UTF-8');
    ?>

    <!-- Topo -->
    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/checklist"
            class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center rounded-lg font-medium transition transform hover:scale-105">
            Voltar
        </a>
        <a href="/syscheck/"
            class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>
        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-center">Consultar Checklists</h1>

    <!-- Filtros -->
    <form id="formFiltro" method="GET" action="/syscheck/checklist/listarChecklists"
        class="w-full max-w-6xl bg-gray-800 p-6 rounded-2xl shadow-2xl mb-8 animate-fadeIn">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">

            <input type="text" name="numero" placeholder="Número" value="<?= $numero ?>"
                class="p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">

            <input type="date" name="data_inicio" value="<?= $data_inicio ?>"
                class="p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">

            <!-- Tipo -->
            <select name="tipo"
                class="filtro-auto p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
                <option value="">Tipo checklist</option>
                <?php foreach ($listaTipos as $tipo) :
                    $descricao = htmlspecialchars($tipo->getDescricaoTipoChecklist(), ENT_QUOTES, 'UTF-8'); ?>
                    <option value="<?= $descricao ?>" <?= ($tipoFiltro === $descricao) ? 'selected' : '' ?>>
                        <?= $descricao ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Objeto -->
            <select name="objeto"
                class="filtro-auto p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
                <option value="">Item checado</option>
                <?php foreach ($listaObjetos as $objeto) :
                    $descricao = htmlspecialchars($objeto->getDescricaoObjeto(), ENT_QUOTES, 'UTF-8'); ?>
                    <option value="<?= $descricao ?>" <?= ($objetoFiltro === $descricao) ? 'selected' : '' ?>>
                        <?= $descricao ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Usuário -->
            <select name="usuario"
                class="filtro-auto p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
                <option value="">Usuário</option>
                <?php foreach ($listaUsuarios as $usuario) :
                    $nome = htmlspecialchars($usuario->getNome(), ENT_QUOTES, 'UTF-8'); ?>
                    <option value="<?= $nome ?>" <?= ($usuarioFiltro === $nome) ? 'selected' : '' ?>>
                        <?= $nome ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Status -->
            <select name="status"
                class="filtro-auto p-2 rounded-lg bg-gray-700 border border-gray-600 focus:ring-2 focus:ring-blue-500">
                <option value="0" <?= ($statusFiltro === '0') ? 'selected' : '' ?>>Todos</option>
                <option value="1" <?= ($statusFiltro === '1') ? 'selected' : '' ?>>Em andamento</option>
                <option value="3" <?= ($statusFiltro === '3') ? 'selected' : '' ?>>Finalizado</option>
            </select>

        </div>

        <div class="flex gap-2 mt-4">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
                Filtrar
            </button>
            <a href="/syscheck/checklist/listarChecklists"
                class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
                Limpar filtro
            </a>
        </div>
    </form>

    <!-- Tabela -->
    <div class="w-full max-w-6xl overflow-x-auto animate-fadeIn">
        <table class="min-w-full bg-gray-800 rounded-2xl overflow-hidden">
            <thead class="bg-gray-700">
                <tr>
                    <th class="py-2 px-4 text-left">Número</th>
                    <th class="py-2 px-4 text-left">Início</th>
                    <th class="py-2 px-4 text-left">Tipo</th>
                    <th class="py-2 px-4 text-left">Objeto</th>
                    <th class="py-2 px-4 text-left">Finalização</th>
                    <th class="py-2 px-4 text-left">Usuário</th>
                    <th class="py-2 px-4 text-left">Status checklist</th>
                </tr>
            </thead>
            <tbody>

                <?php if (empty($listaChecklists)) : ?>
                    <tr>
                        <td colspan="7" class="text-center text-black-400 py-6">
                            Nenhum checklist encontrado.
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($listaChecklists as $checklist) : ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700">

                            <td class="py-2 px-4">
                                <a href="/syscheck/checklist/checklistFinalizado/<?= (int)$checklist->getIdChecklist() ?>"
                                    class="text-blue-400 hover:underline">
                                    <?= (int)$checklist->getIdChecklist() ?>
                                </a>
                            </td>

                            <td class="py-2 px-4"><?= htmlspecialchars($checklist->getDataInicio(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($checklist->getFkTipo(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($checklist->getFkObjeto(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($checklist->getDataFim(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($checklist->getFkUsuario(), ENT_QUOTES, 'UTF-8') ?></td>

                            <td class="py-2 px-4">
                                <?php if ((int)$checklist->getStatusChecklist() === 1) : ?>
                                    <a href="/syscheck/etapaschecklist/continuarChecklist/<?= (int)$checklist->getIdChecklist() ?>"
                                        class="bg-yellow-500 hover:bg-yellow-600 w-40 h-14 flex items-center justify-center rounded-lg font-medium transition transform hover:scale-105">
                                        Em andamento
                                    </a>
                                <?php else : ?>
                                    <a href="/syscheck/checklist/checklistFinalizado/<?= (int)$checklist->getIdChecklist() ?>"
                                        class="bg-green-500 hover:bg-green-600 w-40 h-14 flex items-center justify-center rounded-lg font-medium transition transform hover:scale-105">
                                        Finalizado
                                    </a>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('formFiltro');

            function debounce(func, delay) {
                let timeout;
                return function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func();
                    }, delay);
                };
            }

            // SELECTS → envia imediatamente ao mudar
            const selects = form.querySelectorAll('select');
            selects.forEach(select => {
                select.addEventListener('change', function() {
                    form.submit();
                });
            });

            // INPUTS text e date → envia com debounce
            const inputs = form.querySelectorAll('input[type="text"], input[type="date"]');

            const submitComDelay = debounce(function() {
                form.submit();
            }, 500);

            inputs.forEach(input => {
                input.addEventListener('keyup', submitComDelay);
                input.addEventListener('change', submitComDelay);
            });

        });
    </script>

</body>

</html>
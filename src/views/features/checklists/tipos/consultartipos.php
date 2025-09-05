<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Checklists</title>
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

    use Util\Util;
    use Util\Sessao;
    use rn\RnEtapasChecklist;
    ?>

    <!-- Botões de navegação -->
    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/checklist"
            class="bg-gray-500 hover:bg-gray-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Voltar
        </a>

        <a href="/syscheck/index2.php"
            class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>

        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-center">Consulta de Checklists</h1>

    <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl w-full max-w-6xl animate-fadeIn">

        <!-- Formulário de pesquisa -->
        <form class="flex gap-2 mb-6">
            <input type="search" name="pesquisa" placeholder="Pesquisar"
                class="flex-1 p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                Pesquisar
            </button>
        </form>

        <!-- Tabela de checklists -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Quantidade de etapas</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaTipos as $tipoChecklist) { ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-2">
                                <a href="/syscheck/etapaschecklist/finalizarcadastro/<?= $tipoChecklist->getIdTipoChecklist() ?>"
                                    class="text-blue-400 hover:underline">
                                    <?= $tipoChecklist->getDescricaoTipoChecklist() ?>
                                </a>
                            </td>
                            <td class="px-4 py-2"><?= Util::status($tipoChecklist->getStatusTipoChecklist()) ?></td>
                            <td class="px-4 py-2"><?= (new RnEtapasChecklist($_SESSION['idUsuario']))->quantidadeEtapas($tipoChecklist->getIdTipoChecklist()) ?></td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="#"
                                    class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-white text-sm">Editar</a>
                                <a href="#"
                                    class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-sm">Inativar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>

</body>

</html>
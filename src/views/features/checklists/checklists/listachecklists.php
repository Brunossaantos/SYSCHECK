<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Checklists - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">


    <!-- Topo fixo com Home e Logout -->
<div class="w-full flex justify-between items-center  mb-8 max-w-6xl mx-auto">

    <a href="/syscheck/checklist" 
   class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center text-center rounded-lg text-white font-medium transition transform hover:scale-105 mt-2">
   Voltar
</a>

    <!-- Home -->
    <a href="/syscheck/" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
        Home
    </a>

    <!-- Logout -->
    <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
        Logout
    </a>

</div>

    <h1 class="text-3xl font-bold mb-8 text-center">Relatório de Checklists</h1>

    <!-- Formulário de filtro -->
    <form method="GET" action="/syscheck/checklist/listarChecklists" class="w-full max-w-6xl bg-gray-800 p-6 rounded-2xl shadow-2xl mb-8 animate-fadeIn">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <input type="text" name="numero" placeholder="Número" value="<?= $_GET['numero'] ?? '' ?>" class="p-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="data_inicio" value="<?= $_GET['data_inicio'] ?? '' ?>" class="p-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="tipo" class="p-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" selected disabled>Tipo checklist</option>
                <?php foreach($listaTipos as $tipo){ ?>
                    <option value="<?=$tipo->getDescricaoTipoChecklist()?>"><?=$tipo->getDescricaoTipoChecklist()?></option>
                <?php } ?>
            </select>
            <select name="objeto" class="p-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Item checado</option>
                <?php foreach($listaObjetos as $objeto){ ?>
                    <option value="<?=$objeto->getDescricaoObjeto()?>"><?=$objeto->getDescricaoObjeto()?></option>
                <?php } ?>
            </select>
            <select name="usuario" class="p-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Usuário</option>
                <?php foreach($listaUsuarios as $usuario){ ?>
                    <option value="<?=$usuario->getNome()?>"><?=$usuario->getNome()?></option>
                <?php } ?>
            </select>
            <select name="status" class="p-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="0">Todos</option>
                <option value="1" <?= isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : '' ?>>Em andamento</option>
                <option value="3" <?= isset($_GET['status']) && $_GET['status'] == '3' ? 'selected' : '' ?>>Finalizado</option>
            </select>
        </div>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Filtrar</button>
            <a href="/syscheck/checklist/listarChecklists" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Limpar filtro</a>
        </div>
    </form>

    <!-- Tabela de checklists -->
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
                <?php foreach($listaChecklists as $checklist){ ?>
                <tr class="border-b border-gray-700 hover:bg-gray-700">
                    <td class="py-2 px-4"><a href="/syscheck/checklist/checklistFinalizado/<?=$checklist->getIdChecklist()?>" class="text-blue-400 hover:underline"><?=$checklist->getIdChecklist()?></a></td>
                    <td class="py-2 px-4"><?=$checklist->getDataInicio()?></td>
                    <td class="py-2 px-4"><?=$checklist->getFkTipo()?></td>
                    <td class="py-2 px-4"><?=$checklist->getFkObjeto()?></td>
                    <td class="py-2 px-4"><?=$checklist->getDataFim()?></td>
                    <td class="py-2 px-4"><?=$checklist->getFkUsuario()?></td>
                    <td class="py-2 px-4">
                        <?php if($checklist->getStatusChecklist() == 1){ ?>
                            <a href="/syscheck/etapaschecklist/continuarChecklist/<?=$checklist->getIdChecklist()?>"
   class="bg-yellow-500 hover:bg-yellow-600 w-32 h-16 flex items-center justify-center text-center rounded-lg text-white font-medium transition transform hover:scale-105">
   Continuar checklist
</a>
                                <?php } else { ?>
                            <a href="/syscheck/checklist/checklistFinalizado/<?=$checklist->getIdChecklist()?>"
   class="bg-green-500 hover:bg-green-600 w-27 h-16 flex items-center justify-center text-center rounded-lg text-white font-medium transition transform hover:scale-105">
   Checklist finalizado
</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php // include __DIR__ . '/../../../public/components/footer.php'; ?>

</body>
</html>

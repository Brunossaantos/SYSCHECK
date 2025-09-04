<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Chamados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
   
    <!-- Barra superior -->
      <div class="w-full flex justify-center items-center space-x-20 max-w-6xl mt-16 mx-auto">
        <a href="/syscheck/index2.php"
            class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>

        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>  


    <main class="flex-grow p-6 mt-16">
        <div class="bg-gray-800 rounded-2xl shadow-lg p-6 w-full max-w-6xl mx-auto">
            <h2 class="text-2xl font-bold text-center text-white mb-6">Gerenciamento de Chamados Abertos</h2>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Número do chamado</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Abertura</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Equipamento</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Última providência</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Usuário</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach($listaChamados as $chamado){?>  
                        <tr class="hover:bg-gray-700 transition">
                            <td class="px-4 py-3">
                                <a href="/syscheck/chamado/selecionarChamado/<?=$chamado->getIdChamado()?>"
                                   class="text-blue-400 hover:underline">
                                    <?=$chamado->getIdChamado()?>
                                </a>
                            </td>
                            <td class="px-4 py-3"><?=$chamado->getDataAberturaChamado()?></td>
                            <td class="px-4 py-3"><?=$chamado->getFkItemChamado()?></td>
                            <td class="px-4 py-3 italic text-gray-400">*iterar sobre a lista do follow up*</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    <?= $chamado->getStatusChamado() === 'Aberto' ? 'bg-red-600 text-white' : 
                                        ($chamado->getStatusChamado() === 'Em andamento' ? 'bg-yellow-500 text-black' : 
                                        'bg-green-600 text-white') ?>">
                                    <?=$chamado->getStatusChamado()?>
                                </span>
                            </td>
                            <td class="px-4 py-3"><?=$chamado->getFkUsuario()?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>

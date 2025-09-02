<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Completo</title>
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

    <?php 
        use Util\Sessao;
        include_once __DIR__ . '/../../../../../vendor/autoload.php';
        include __DIR__ . '/../../../public/components/navbar.php'; 
        Sessao::mostrarMensagem();    
    ?>

    <!-- Botões de navegação -->
    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/etapaschecklist/consultarChecklists"
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

    <div class="w-full max-w-5xl animate-fadeIn">

        <h1 class="text-3xl font-bold mb-6">Checklist</h1>
        <h2 class="text-xl mb-6"><?=$tipoChecklist->getDescricaoTipoChecklist()?></h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 rounded-lg overflow-hidden">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="text-left p-3">Número da Etapa</th>
                        <th class="text-left p-3">Título da Etapa</th>
                        <th class="text-left p-3">Conteúdo</th>
                        <th class="text-left p-3">Foto obrigatória</th>
                        <th class="text-left p-3">Campo adicional</th>
                        <th class="text-left p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listaEtapas as $etapa){ ?>
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="p-3">
                                <a href="/syscheck/etapaschecklist/gerenciaretapa/<?=$etapa->getIdEtapaChecklist()?>"
                                   class="text-blue-400 hover:underline">
                                    <?=$etapa->getNumeroEtapa()?>
                                </a>
                            </td>
                            <td class="p-3"><?=$etapa->getTituloEtapa()?></td>
                            <td class="p-3"><?=$etapa->getConteudoEtapa()?></td>
                            <td class="p-3"><?= ($etapa->getFotoObrigatoria() == 1) ? 'Foto obrigatória' : ''?></td>
                            <td class="p-3"><?= ($etapa->getCampoAdicional() == 1) ? 'Campo obrigatório' : '' ?></td>
                            <td class="p-3"><?= ($etapa->getStatusEtapa() == 1) ? 'Ativo' : 'Inativo' ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>

</body>
</html>

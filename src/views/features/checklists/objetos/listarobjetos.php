<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Itens de Checklist - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">
    <?php 
        use Util\Util;
        include_once __DIR__ . '/../../../public/components/navbar.php'; 
    ?>

    <!-- Barra superior -->
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
    <!-- Card de listagem -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-6xl">
        <h1 class="text-2xl font-bold mb-6 text-center">Itens de checklist cadastrados</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Descrição do item</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    <?php foreach($listaObjetos as $objeto){ ?>
                        <tr class="hover:bg-gray-700 transition">
                            <!-- Tipo do checklist -->
                            <td class="px-4 py-3">
                                <?php 
                                foreach($listaTipos as $tipo){
                                    if($objeto->getFkTipoChecklist() == $tipo->getIdTipoChecklist()){
                                        echo $tipo->getDescricaoTipoChecklist();
                                        break;
                                    }
                                }
                                ?>
                            </td>

                            <!-- Descrição -->
                            <td class="px-4 py-3">
                                <a href="/syscheck/objeto/alterarobjeto/<?=$objeto->getIdObjeto()?>"
                                   class="text-blue-400 hover:underline">
                                    <?=$objeto->getDescricaoObjeto()?>
                                </a>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <?= Util::status($objeto->getStatusObjeto()) ?>
                            </td>

                            <!-- Ações -->
                            <td class="px-4 py-3">
                                <a href="/syscheck/objeto/alterarobjeto/<?=$objeto->getIdObjeto()?>"
                                   class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                                   Editar
                                </a>
                                <a href="/syscheck/objeto/excluir/<?=$objeto->getIdObjeto()?>"
                                   class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium transition ml-2">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../../public/components/footer.php'; ?>
</body>
</html>

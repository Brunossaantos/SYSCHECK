<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Item de Checklist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">


    <!-- Barra superior -->
    <div class="w-full flex justify-center space-x-5 gap-6 p-6">
        <a href="/syscheck/objeto/listarobjetos"
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

    <!-- Card de edição -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-4xl">
        <h1 class="text-2xl font-bold mb-6 text-center">Alterar item de checklist</h1>

        <form action="/syscheck/objeto/salvarAlteracaoObjeto" method="POST" class="space-y-6">
            <input type="hidden" name="idobjeto" value="<?= $objeto->getIdObjeto() ?>">

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block mb-2 font-medium">Descrição</label>
                <input type="text" id="descricao" name="descricao"
                    value="<?= $objeto->getDescricaoObjeto() ?>"
                    placeholder="Digite a descrição do objeto"
                    class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Tipo do checklist -->
            <div>
                <label for="fktipo" class="block mb-2 font-medium">Tipo do checklist</label>
                <select name="fktipo" id="fktipo"
                    class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="" disabled>Selecione o tipo do item</option>
                    <?php foreach ($listaTipos as $tipoObjeto) { ?>
                        <option value="<?= $tipoObjeto->getIdTipoChecklist() ?>"
                            <?= ($objeto->getFkTipoChecklist() == $tipoObjeto->getIdTipoChecklist()) ? "selected" : "" ?>>
                            <?= $tipoObjeto->getDescricaoTipoChecklist() ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="statusitem" class="block mb-2 font-medium">Status do item</label>
                <select name="statusitem" id="statusitem"
                    class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="1" <?= ($objeto->getStatusObjeto() == 1) ? "selected" : "" ?>>Ativo</option>
                    <option value="0" <?= ($objeto->getStatusObjeto() == 0) ? "selected" : "" ?>>Inativo</option>
                </select>
            </div>

            <!-- Botão -->
            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                Salvar Alterações
            </button>
        </form>
    </div>

    <?php include_once __DIR__ . '/../../../public/components/footer.php'; ?>
</body>

</html>
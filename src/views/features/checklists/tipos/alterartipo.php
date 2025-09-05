<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tipos de Checklist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">


    <!-- Botões superiores -->
    <div class="w-full flex justify-center space-x-3 gap-6 p-6">
        <a href="/syscheck/tiposchecklist/gerenciarTipos"
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

    <!-- Conteúdo principal -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-2xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Cadastro de Tipos de Checklist</h2>

        <form action="/syscheck/tiposchecklist/salvaralteracao" method="POST" class="space-y-6">
            <input type="hidden" name="idtipochecklist" value="<?= $tipoChecklist->getIdTipoChecklist() ?>">

            <!-- Descrição -->
            <div>
                <label for="descricao" class="block mb-2 font-medium">Descrição</label>
                <input type="text" name="descricao" value="<?= $tipoChecklist->getDescricaoTipoChecklist() ?>"
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Status -->
            <div>
                <label for="statustipochecklist" class="block mb-2 font-medium">Status</label>
                <select name="statustipochecklist"
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Selecione o status do tipo</option>
                    <option value="1" <?= ($tipoChecklist->getStatusTipoChecklist() == 1 ? "selected" : "") ?>>Ativo</option>
                    <option value="0" <?= ($tipoChecklist->getStatusTipoChecklist() == 0 ? "selected" : "") ?>>Inativo</option>
                </select>
            </div>

            <!-- Botão de salvar -->
            <div class="flex justify-center">
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 px-8 py-3 rounded-lg font-semibold transition transform hover:scale-105">
                    Salvar alterações
                </button>
            </div>
        </form>
    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>
</body>

</html>
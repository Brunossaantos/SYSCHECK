<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tipos de Checklist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col">

    <!-- Botões de navegação -->
    <div class="w-full flex justify-center space-x-5 gap-6 p-6">
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

    <!-- Conteúdo principal -->
    <main class="flex-grow flex justify-center items-start p-6">
        <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-8 text-center">Cadastro de Tipos de Checklist</h2>

            <form action="/syscheck/tiposchecklist/cadastrarnovotipo" method="POST" class="space-y-6">

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block mb-2 font-medium">Descrição</label>
                    <input type="text" name="descricao" placeholder="Digite a descrição do tipo"
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 
                               text-white placeholder-gray-400 focus:outline-none focus:ring-2 
                               focus:ring-blue-500">
                </div>

                <!-- Status -->
                <div>
                    <label for="statustipochecklist" class="block mb-2 font-medium">Status</label>
                    <select name="statustipochecklist"
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 
                               text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="--" selected disabled>Selecione o status do tipo</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>

                <!-- Responsável -->
                <div>
                    <label for="responsavel" class="block mb-2 font-medium">Responsável</label>
                    <select name="responsavel"
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 
                               text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="--" selected disabled>Selecione o responsável</option>
                        <?php foreach ($listaResposaveis as $responsavel) { ?>
                            <option value="<?= $responsavel->getIdResponsavel() ?>"><?= $responsavel->getNomeResponsavel() ?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Botão -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 px-8 py-3 rounded-lg font-semibold 
                               transition transform hover:scale-105">
                        Cadastrar Tipo de Checklist
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>

</body>

</html>
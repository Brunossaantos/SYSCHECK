<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastrar Objeto - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

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

    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-3xl">
        <h1 class="text-2xl font-bold mb-6 text-center">Cadastro de item para checklist</h1>

        <form action="/syscheck/objeto/cadastrarobjeto" method="POST" class="space-y-6">
            <div>
                <label for="descricao" class="block mb-2 font-medium">Descrição</label>
                <input
                    type="text"
                    id="descricao"
                    name="descricao"
                    placeholder="Digite a descrição do objeto"
                    autocomplete="off"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="tipo" class="block mb-2 font-medium">Tipo do checklist</label>
                <select
                    id="tipo"
                    name="fktipo"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Selecione o tipo do item</option>
                    <?php foreach ($listaTipos as $tipoChecklist) { ?>
                        <option value="<?= $tipoChecklist->getIdTipoChecklist() ?>">
                            <?= $tipoChecklist->getDescricaoTipoChecklist() ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label for="fk_empresa" class="block mb-2 font-medium">Unidade</label>
                <select
                    id="fk_empresa"
                    name="fk_empresa"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Selecione a unidade</option>

                    <?php foreach ($listaUnidades as $unidade) { ?>
                        <option value="<?= $unidade['id_empresa'] ?>">
                            <?= $unidade['nome'] ?>
                        </option>
                    <?php } ?>

                </select>
            </div>
            <div>
                <label for="statusitem" class="block mb-2 font-medium">Status do item</label>
                <select
                    id="statusitem"
                    name="statusitem"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="" disabled selected>Status do item</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105">
                    Cadastrar
                </button>
            </div>
        </form>
    </div>

    <?php include_once __DIR__ . '/../../../public/components/footer.php'; ?>
</body>
<script>
    // Pega os parâmetros da URL
    const params = new URLSearchParams(window.location.search);

    if (params.has('sucesso')) {
        alert("Objeto cadastrado com sucesso!");
        // Limpa a URL para não repetir o alert se der F5
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    if (params.has('erro')) {
        alert("Erro ao cadastrar o objeto.");
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

</html>
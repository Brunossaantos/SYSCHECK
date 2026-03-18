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

            <div>
                <label class="block mb-2 font-medium">Descrição</label>
                <input type="text" name="descricao" value="<?= $objeto->getDescricaoObjeto() ?>" required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
            </div>

            <div>
                <label class="block mb-2 font-medium">Tipo do checklist</label>
                <select name="fktipo" required class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
                    <?php foreach ($listaTipos as $tipo) { ?>
                        <option value="<?= $tipo->getIdTipoChecklist() ?>" <?= $tipo->getIdTipoChecklist() == $objeto->getFkTipoChecklist() ? 'selected' : '' ?>>
                            <?= $tipo->getDescricaoTipoChecklist() ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium">Unidade</label>
                <select name="fk_empresa" required class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
                    <?php foreach ($listaUnidades as $unidade) { ?>
                        <option value="<?= $unidade['id_empresa'] ?>" <?= $unidade['id_empresa'] == $objeto->getFkEmpresa() ? 'selected' : '' ?>>
                            <?= $unidade['nome'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium">Status</label>
                <select name="statusitem" required class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white">
                    <option value="1" <?= $objeto->getStatusObjeto() == 1 ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= $objeto->getStatusObjeto() == 0 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-semibold w-full">
                Salvar Alterações
            </button>
        </form>

        <script>
            const params = new URLSearchParams(window.location.search);
            if (params.has('sucesso')) {
                alert("Alterações salvas com sucesso!");
                window.history.replaceState({}, document.title, window.location.pathname);
            }
            if (params.has('erro')) {
                alert("Erro ao atualizar o objeto.");
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        </script>
    </div>

    <?php include_once __DIR__ . '/../../../public/components/footer.php'; ?>
</body>

</html>
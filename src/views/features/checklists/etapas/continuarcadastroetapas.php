<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Etapas</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS próprio -->
    <link rel="stylesheet" href="/syscheck/src/views/public/css/styles.css">
</head>


<!-- Botões superiores -->
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

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">



    <!-- Conteúdo principal -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-4xl mt-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Cadastro de etapas</h2>

        <form onsubmit="return verificarCampos()"
            action="/syscheck/etapaschecklist/cadastrarnovaetapa"
            method="POST"
            class="space-y-6">

            <input type="hidden" name="fktipo" value="<?= $fkTipo ?>">

            <!-- Checklist -->
            <div>
                <label class="block mb-2 font-medium">Checklist</label>
                <input type="text"
                    value="<?= $tipoChecklist->getDescricaoTipoChecklist() ?>"
                    disabled
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none">
            </div>

            <!-- Título -->
            <div>
                <label class="block mb-2 font-medium">Título da etapa</label>
                <input type="text"
                    name="titulo"
                    placeholder="Título da etapa"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Conteúdo -->
            <div>
                <label class="block mb-2 font-medium">Conteúdo da etapa</label>
                <textarea name="conteudo"
                    rows="5"
                    required
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Número -->
            <div>
                <label class="block mb-2 font-medium">Número da etapa</label>
                <input type="text"
                    name="numero"
                    value="<?= $quantidadeEtapas ?>"
                    readonly
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none">
            </div>

            <!-- Checkboxes -->
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 font-medium">
                    <input type="checkbox" name="fotoobrigatoria" class="h-5 w-5 text-blue-500">
                    Foto obrigatória
                </label>

                <label class="flex items-center gap-2 font-medium">
                    <input type="checkbox" name="campoadicional" class="h-5 w-5 text-blue-500">
                    Campo adicional
                </label>
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-2 font-medium">Status da etapa</label>
                <select name="status"
                    id="status"
                    class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Status</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex gap-4 pt-4">
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                    Salvar etapa
                </button>

                <a href="/syscheck/etapaschecklist/finalizarcadastro/<?= $fkTipo ?>"
                    class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                    Finalizar cadastro de etapas
                </a>
            </div>

        </form>
    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>

    <script>
        function verificarCampos() {
            const status = document.getElementById('status').value;

            if (status === '--') {
                alert('Selecione o status da etapa');
                return false;
            }
            return true;
        }
    </script>

</body>

</html>
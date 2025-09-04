<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Etapas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

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

    <!-- Conteúdo principal -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-4xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Cadastro de Etapas</h2>

        <form onsubmit="return verificarCampos()" action="/syscheck/etapaschecklist/cadastrarnovaetapa" method="POST" class="space-y-6">
            
            <!-- Tipo de checklist -->
            <div>
                <label for="fktipo" class="block mb-2 font-medium">Checklist</label>
                <select id="fktipo" name="fktipo"
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Selecione o tipo do checklist</option>
                    <?php foreach ($listaTipos as $tipo) { ?>
                        <option value="<?= $tipo->getIdTipoChecklist() ?>"><?= $tipo->getDescricaoTipoChecklist() ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Título da etapa -->
            <div>
                <label for="titulo" class="block mb-2 font-medium">Título da etapa</label>
                <input type="text" name="titulo" placeholder="Título da etapa" required
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Conteúdo da etapa -->
            <div>
                <label for="conteudo" class="block mb-2 font-medium">Conteúdo da etapa</label>
                <textarea name="conteudo" rows="5" required
                          class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Número da etapa -->
            <div>
                <label for="numero" class="block mb-2 font-medium">Número da etapa</label>
                <input type="text" name="numero" value="1" readonly
                       class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none">
            </div>

            <!-- Foto obrigatória -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="fotoobrigatoria" name="fotoobrigatoria" class="form-checkbox h-5 w-5 text-blue-500">
                <label for="fotoobrigatoria" class="font-medium">Foto obrigatória</label>
            </div>

            <!-- Campo adicional -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="campoadicional" name="campoadicional" class="form-checkbox h-5 w-5 text-blue-500">
                <label for="campoadicional" class="font-medium">Campo adicional</label>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block mb-2 font-medium">Status da etapa</label>
                <select id="status" name="status"
                        class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="--" selected disabled>Status</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex gap-4">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                    Salvar etapa
                </button>
                <a href="/syscheck/etapaschecklist/finalizarcadastro"
                   class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                    Finalizar cadastro
                </a>
            </div>

        </form>
    </div>

    <?php include __DIR__ . '/../../../public/components/footer.php'; ?>

</body>

<script>
function verificarCampos() {
    var fktipo = document.getElementById('fktipo').value;
    var status = document.getElementById('status').value;

    if (fktipo == '--') {
        alert('Selecione o tipo do checklist.');
        return false;
    }

    if (status == '--') {
        alert('Selecione o status da etapa');
        return false;
    }

    return true;
}
</script>
</html>

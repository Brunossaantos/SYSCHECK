<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Checklist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">
    <?php

    use Util\Sessao;

    $idUsuario = Sessao::idusuario();
    $data = (new DateTime())->format('d/m/y H:i:s');
    ?>

    <!-- Barra superior -->
    <div class="w-full flex justify-center space-x-3 gap-6 p-6">
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
    <!-- Card iniciar checklist -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-4xl">
        <h1 class="text-2xl font-bold mb-6 text-center">Iniciar Checklist</h1>

        <form action="/syscheck/checklist/salvarInicioChecklist" method="POST" class="space-y-6">
            <input type="hidden" name="fkusuario" value="<?= $idUsuario ?>">

            <!-- Tipo do checklist -->
            <div>
                <label for="fktipo" class="block mb-2 font-medium">Tipo do checklist</label>
                <select name="fktipo" id="fktipo"
                    class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="" disabled selected>Selecione o tipo do checklist</option>
                    <?php foreach ($listaTipos as $tipoChecklist) { ?>
                        <option value="<?= $tipoChecklist->getIdTipoChecklist() ?>"><?= $tipoChecklist->getDescricaoTipoChecklist() ?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Objeto/item checado -->
            <div>
                <label for="fkobjeto" class="block mb-2 font-medium">Item checado</label>
                <select name="fkobjeto" id="fkobjeto"
                    class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Selecione o item/local do checklist</option>
                </select>
            </div>

            <!-- Data e hora -->
            <div>
                <label for="datainicio" class="block mb-2 font-medium">Data e hora de início</label>
                <input type="text" id="datainicio" name="datainicio" value="<?= $data ?>" readonly
                    class="w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none">
            </div>

            <!-- Botão -->
            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
                Iniciar Checklist
            </button>
        </form>
    </div>

    <?php include_once __DIR__ . '/../../../public/components/footer.php'; ?>

    <!-- Script AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fktipo').change(function() {
                var selectedValue = $(this).val();

                $.ajax({
                    url: '/syscheck/checklist/listarItens/' + selectedValue,
                    type: 'GET',
                    success: function(response) {
                        $('#fkobjeto').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição AJAX: ' + status + ' - ' + error);
                    }
                });
            });
        });
    </script>
</body>

</html>
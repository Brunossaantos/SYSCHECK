<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abertura de Chamados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-900 text-white min-h-screen flex flex-col">

   
        <!-- Barra superior -->
      <div class="w-full flex justify-center items-center space-x-16 mb-8 max-w-6xl mt-20 mx-auto">
        <a href="/syscheck/index2.php"
            class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>

        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>

    <main class="flex-grow flex items-center justify-center p-6">
        <div class="bg-gray-800 rounded-2xl shadow-lg p-8 w-full max-w-2xl">

            <h2 class="text-2xl font-bold text-center mb-6">📋 Abertura de Chamados</h2>

            <form action="/syscheck/chamado/abrirchamado" method="POST" enctype="multipart/form-data" class="space-y-4">

                <!-- Usuário -->
                <div>
                    <label for="nome" class="block mb-1 text-gray-300">Usuário</label>
                    <input type="text" id="nome" name="nome" value="<?= $usuario->getNome() ?>" readonly
                        class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white" />
                    <input type="hidden" name="fkusuario" value="<?= $usuario->getIdUsuario() ?>">
                </div>

                <!-- Tipo do chamado -->
                <div>
                    <label for="tipo" class="block mb-1 text-gray-300">Tipo do Chamado</label>
                    <select name="fktipo" id="tipo"
                        class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white">
                        <option value="--" disabled selected>Selecione o tipo do chamado</option>
                        <?php foreach($listaTipos as $tipo){?>
                            <option value="<?=$tipo->getIdTipoChecklist()?>"><?=$tipo->getDescricaoTipoChecklist()?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Equipamento -->
                <div>
                    <label for="equipamento" class="block mb-1 text-gray-300">Equipamento</label>
                    <select name="fkequipamento" id="equipamento"
                        class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white">
                        <option value="--" disabled selected>Selecione o equipamento</option>
                        <?php foreach($listaEquipamentos as $equipamento){?>
                            <option value="<?=$equipamento->getIdObjeto()?>"><?=$equipamento->getDescricaoObjeto()?></option>
                        <?php }?>
                    </select>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="deschamado" class="block mb-1 text-gray-300">Descrição do Chamado</label>
                    <textarea name="deschamado" id="deschamado" rows="3"
                        class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white"></textarea>
                </div>

                <!-- Upload de Fotos -->
                <div>
                    <label for="foto" class="block mb-1 text-gray-300">Fotos</label>
                    <div class="flex items-center space-x-2">
                        <label for="foto"
                            class="cursor-pointer bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-white">
                            Selecionar
                        </label>
                        <input type="file" id="foto" name="fotos[]" multiple class="hidden">
                        <input type="text" id="file-name" placeholder="Nenhum arquivo selecionado" readonly
                            class="flex-1 px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 w-20 h-10text-white">
                    </div>
                </div>

                <!-- Data e hora -->
                <div>
                    <label class="block mb-1 text-gray-300">Data e Hora de Abertura</label>
                    <input type="text" name="datahora" value="<?= $dataHora ?>" readonly
                        class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white" />
                </div>

                <!-- Botão -->
                <div class="text-center">
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded-lg font-semibold">
                        Salvar Cadastro
                    </button>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../../public/components/footer.php'; ?>

    <script>
        document.getElementById("foto").addEventListener("change", function () {
            const files = this.files;
            const fileNameInput = document.getElementById("file-name");
            const validExtensions = ["jpg", "jpeg", "png", "gif"];
            let fileNames = [];

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (validExtensions.includes(fileExtension)) {
                    fileNames.push(file.name);
                } else {
                    alert("Apenas fotos podem ser enviadas.");
                    this.value = "";
                    fileNameInput.value = "";
                    return;
                }
            }

            fileNameInput.value = fileNames.length > 0 ? fileNames.join(", ") : "Nenhum arquivo selecionado";
        });
    </script>

</body>
</html>

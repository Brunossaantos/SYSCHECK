<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Checklist</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        body.loaded {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center">

    <div class="w-[95%] md:w-[85%] lg:w-[70%] 
            bg-gray-800 text-gray-100 
            shadow-2xl rounded-xl 
            p-4 md:p-6 lg:p-8">

        <!-- BOTÃO FINALIZAR (LOGOUT) -->
        <div class="mb-6 text-center">
            <form method="POST" action="/syscheck/usuario/logout">
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 
                       text-white font-bold 
                       text-lg md:text-xl lg:text-2xl
                       py-3 md:py-4 lg:py-3 
                       rounded-xl shadow-lg 
                       transition duration-300 ease-in-out 
                       hover:scale-[1.02]">
                    FINALIZAR CHECKLIST
                </button>
            </form>
        </div>

        <!-- TÍTULO -->
        <h1 class="text-2xl md:text-3xl font-bold text-center mb-8">
            Consulta de Checklist
        </h1>

        <?php
        $exibirHorimetro = false; // flag geral

        if (!empty($listaHorimetros)) {
            $horimetroInicial = $listaHorimetros[0]['horimetro'] ?? '';
            $ultimoIndex = count($listaHorimetros) - 1;
            $ultimoHorimetro = $listaHorimetros[$ultimoIndex]['horimetro'] ?? null;

            $horimetroFinal = '';
            if ($ultimoHorimetro !== null && $ultimoHorimetro != $horimetroInicial) {
                $horimetroFinal = $ultimoHorimetro;
            }

            // Se a lista de horímetros tiver algum valor, mostramos os campos
            if ($horimetroInicial !== '' || $horimetroFinal !== '') {
                $exibirHorimetro = true;
            }
        }
        ?>
        <!-- GRID RESPONSIVO -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">

            <div>
                <label class="block text-sm mb-2">Tipo do Checklist</label>
                <input type="text" value="<?= $tipo->getDescricaoTipoChecklist() ?>" readonly
                    class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
            transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Item Checado</label>
                <input type="text" value="<?= $item->getDescricaoObjeto() ?>" readonly
                    class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
            transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Data Início</label>
                <input type="text" value="<?= $checklist->getDataInicio() ?>" readonly
                    class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
            transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Data Fim</label>
                <input type="text" value="<?= $checklist->getDataFim() ?>" readonly
                    class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
            transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Responsável</label>
                <input type="text" value="<?= $usuario->getNome() ?>" readonly
                    class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
            transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <?php if ($exibirHorimetro): ?>

                <div>
                    <label class="block text-sm mb-2">Horímetro Inicial</label>
                    <input type="number" value="<?= $horimetroInicial ?>" readonly
                        class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
                transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <?php if ($horimetroFinal !== ''): ?>
                    <div>
                        <label class="block text-sm mb-2">Horímetro Final</label>
                        <input type="number" value="<?= $horimetroFinal ?>" readonly
                            class="w-full rounded-lg border border-gray-600 bg-gray-700 p-2 md:p-3
                transition focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>

        <!-- CAMPO PRINCIPAL AJUSTADO -->
        <div class="flex justify-center mb-10">
            <input type="text"
                value="<?= $listaEtapas[0]['NUMERO'] ?? '' ?>"
                readonly
                class="w-full md:w-[80%] lg:w-[60%]
                   text-center 
                   text-xl md:text-2xl lg:text-3xl
                   tracking-widest 
                   border-2 border-dashed border-gray-500 
                   bg-gray-700 rounded-xl 
                   p-3 md:p-4 lg:p-5 
                   transition focus:ring-2 
                   focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- TABELA AJUSTADA -->
        <?php if (!empty($listaEtapas)) { ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700 text-sm md:text-base">

                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-3 py-2 md:px-4 md:py-3 text-left">Título</th>
                            <th class="px-3 py-2 md:px-4 md:py-3 text-left">Conteúdo</th>
                            <th class="px-3 py-2 md:px-4 md:py-3 text-left">Etapa</th>
                            <th class="px-3 py-2 md:px-4 md:py-3 text-left">Status</th>
                            <th class="px-3 py-2 md:px-4 md:py-3 text-left">Observação</th>
                            <th class="px-3 py-2 md:px-4 md:py-3 text-left">Foto</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-700">

                        <?php foreach ($listaEtapas as $etapa) { ?>
                            <tr class="hover:bg-gray-700 transition duration-200">

                                <td class="px-3 py-2 md:px-4 md:py-3"><?= $etapa['TITULO'] ?></td>
                                <td class="px-3 py-2 md:px-4 md:py-3"><?= $etapa['CONTEUDO'] ?></td>
                                <td class="px-3 py-2 md:px-4 md:py-3"><?= $etapa['NUMERO_ETAPA'] ?></td>

                                <td class="px-3 py-2 md:px-4 md:py-3">
                                    <?php if ($etapa['ACAO'] == 1) { ?>
                                        <span class="text-green-400 font-semibold">Aprovado</span>
                                    <?php } else { ?>
                                        <span class="text-red-400 font-semibold">Reprovado</span>
                                    <?php } ?>
                                </td>

                                <td class="px-3 py-2 md:px-4 md:py-3"><?= $etapa['OBSERVACAO'] ?></td>

                                <td class="px-3 py-2 md:px-4 md:py-3">
                                    <?php
                                    $fotoEncontrada = false;
                                    foreach ($listaFotos as $foto) {
                                        if ($foto->getNumeroEtapa() == $etapa['NUMERO_ETAPA']) {
                                            $caminho = "/syscheck/src/views/" . $foto->getCaminhoFoto();
                                            echo "<a href='$caminho' target='_blank' class='text-blue-400 hover:underline transition'>Ver Foto</a>";
                                            $fotoEncontrada = true;
                                            break;
                                        }
                                    }
                                    if (!$fotoEncontrada) {
                                        echo "<span class='text-gray-500'>Sem foto</span>";
                                    }
                                    ?>
                                </td>

                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>

        <?php } else { ?>

            <div class="text-center text-gray-400 py-8">
                Não existem etapas para esse checklist.
            </div>

        <?php } ?>

    </div>

    <script>
        window.addEventListener("load", function() {
            document.body.classList.add("loaded");
        });
    </script>

</body>

</html>
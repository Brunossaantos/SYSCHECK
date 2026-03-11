<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horímetro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-slate-200 min-h-screen">

    <div class="flex items-center justify-center mt-20 px-4">

        <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl shadow-xl p-8">

            <h2 class="text-2xl font-semibold text-center mb-6">
                Registro de Horímetro
            </h2>

            <form action="/syscheck/checklist/salvarHorimetro" method="POST" id="formHorimetro">

                <input type="hidden" name="idchecklist" value="<?= $checklist->getIdChecklist() ?>">
                <input type="hidden" name="fkempilhadeira" value="<?= $checklist->getFkObjeto() ?>">

                <!-- Input horímetro -->
                <div class="mb-5">
                    <label class="block mb-2 text-sm text-slate-300">
                        Digite o valor exibido no visor
                    </label>

                    <input
                        type="number"
                        name="horimetro"
                        id="horimetro"
                        placeholder="Ex: 4521"
                        autocomplete="off"
                        required
                        class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-3 text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- erro -->
                <div
                    id="erroHorimetro"
                    class="hidden bg-red-900 text-red-200 p-3 rounded-lg text-sm mb-4">
                    Erro
                </div>

                <!-- botão -->
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 transition rounded-lg py-3 font-semibold">
                    Salvar horímetro
                </button>

            </form>

        </div>

    </div>

    <script>
        const input = document.getElementById("horimetro");
        const erro = document.getElementById("erroHorimetro");
        const form = document.getElementById("formHorimetro");

        /* aceitar apenas números positivos inteiros */
        input.addEventListener("input", () => {
            // remove qualquer ponto ou vírgula
            input.value = input.value.replace(/[.,]/g, "");

            // se for negativo, limpa
            if (input.value < 0) input.value = "";
        });

        form.addEventListener("submit", function(e) {
            const valor = parseInt(input.value);

            if (isNaN(valor) || valor <= 0) {
                e.preventDefault();
                erro.innerText = "Digite um valor válido.";
                erro.classList.remove("hidden");
                return;
            }
        });
    </script>

</body>

</html>
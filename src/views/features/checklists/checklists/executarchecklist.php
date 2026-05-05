<!DOCTYPE html>
<html lang="pt-br">

<?php
/** @var int $idChecklist */
/** @var int $fkTipo */
/** @var array $etapas */
?>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .etapa-card {
            transition: all .35s ease;
        }

        .aprovado {
            border: 3px solid #22c55e;
        }

        .reprovado {
            border: 3px solid #ef4444;
        }

        .desabilitado {
            background: #475569 !important;
            opacity: .5;
            cursor: not-allowed;
        }

        .foto-enviada {
            color: #22c55e;
            font-size: 12px;
        }
    </style>

</head>

<body class="bg-slate-900 pt-28 p-4">

    <!-- BARRA FIXA -->

    <div class="fixed top-0 left-0 w-full bg-slate-900 border-b border-slate-700 z-50 shadow">

        <div class="max-w-3xl mx-auto p-4">

            <h1 class="text-lg font-bold text-white mb-2">
                Checklist
            </h1>

            <div class="flex justify-between text-xs text-slate-300 mb-1">
                <span>Progresso</span>
                <span id="contador">0 / <?= count($etapas) ?></span>
            </div>

            <div class="w-full bg-slate-700 rounded-full h-3">

                <div id="barra"
                    class="bg-indigo-500 h-3 rounded-full transition-all duration-500"
                    style="width:0%"></div>

            </div>

        </div>

    </div>

    <div class="max-w-3xl mx-auto">

        <?php foreach ($etapas as $etapa): ?>

            <div id="etapa_<?= $etapa->getNumeroEtapa() ?>"
                class="etapa-card bg-slate-800 rounded-xl shadow-lg p-5 mb-6">

                <div class="flex justify-between border-b border-slate-700 pb-2 mb-3">

                    <h2 class="text-base font-semibold text-white">
                        Etapa <?= $etapa->getNumeroEtapa() ?>
                    </h2>

                    <span
                        id="foto_status_<?= $etapa->getNumeroEtapa() ?>"
                        class="foto-enviada hidden">
                        📷 Foto anexada
                    </span>

                </div>

                <div class="text-slate-400 text-xs mb-3">
                    <?= $etapa->getTituloEtapa() ?>
                </div>

                <div class="bg-slate-900 p-4 rounded-lg text-slate-200 mb-4 text-sm">
                    <?= $etapa->getConteudoEtapa() ?>
                </div>

                <div class="grid grid-cols-2 gap-3">

                    <button
                        id="aprovar_<?= $etapa->getNumeroEtapa() ?>"
                        onclick="aprovar(<?= $etapa->getNumeroEtapa() ?>)"
                        class="btn-etapa bg-green-600 active:scale-95 text-white py-3 rounded-lg">

                        ✔ Aprovar

                    </button>

                    <button
                        id="reprovar_<?= $etapa->getNumeroEtapa() ?>"
                        onclick="reprovar(<?= $etapa->getNumeroEtapa() ?>)"
                        class="btn-etapa bg-red-600 active:scale-95 text-white py-3 rounded-lg">

                        ✖ Reprovar

                    </button>

                    <button
                        id="foto_<?= $etapa->getNumeroEtapa() ?>"
                        onclick="abrirFoto(<?= $etapa->getNumeroEtapa() ?>)"
                        class="btn-etapa bg-blue-600 active:scale-95 text-white py-3 rounded-lg">

                        📷 Foto

                    </button>

                    <button
                        onclick="abrirObservacao(<?= $etapa->getNumeroEtapa() ?>)"
                        class="btn-etapa bg-yellow-500 active:scale-95 text-black py-3 rounded-lg">

                        💬 Observação

                    </button>

                </div>

                <div id="obs_<?= $etapa->getNumeroEtapa() ?>"
                    class="text-yellow-400 text-xs mt-3 hidden"></div>

            </div>

        <?php endforeach ?>

        <div class="text-center mt-8">

            <button
                onclick="finalizarChecklist(this)"
                class="bg-indigo-600 active:scale-95 text-white font-bold py-4 px-8 rounded-lg">

                FINALIZAR CHECKLIST

            </button>

        </div>

    </div>

    <!-- MODAL FOTO -->

    <div id="modalFoto"
        class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center">

        <div class="bg-white rounded-lg p-6 w-full max-w-sm mx-4">

            <h2 class="text-lg font-bold mb-4">Enviar Foto</h2>

            <form
                method="POST"
                action="/syscheck/foto/uploadimagem"
                enctype="multipart/form-data">

                <input type="hidden" name="fkchecklist" value="<?= $idChecklist ?>">
                <input type="hidden" name="numeroEtapa" id="numeroEtapa">
                <input
                    type="hidden"
                    name="url"
                    id="urlRetorno"
                    value="<?= $_SERVER['REQUEST_URI'] ?>">

                <input
                    type="file"
                    name="foto"
                    id="inputFoto"
                    accept="image/*"
                    capture="environment"
                    required
                    class="mb-4">

                <div class="flex justify-end gap-2">

                    <button
                        type="button"
                        onclick="fecharFoto()"
                        class="bg-gray-400 px-4 py-2 rounded">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded">

                        Enviar

                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>
        const idChecklist = <?= $idChecklist ?>;
        const fkTipo = <?= $fkTipo ?>;
        const totalEtapas = <?= count($etapas) ?>;

        let respostas = JSON.parse(localStorage.getItem("checklist_" + idChecklist)) || {};
        let fotos = JSON.parse(localStorage.getItem("checklist_fotos_" + idChecklist)) || {};
        let estadoVisual = JSON.parse(localStorage.getItem("checklist_visual_" + idChecklist)) || {};
        let observacoes = {};
        let etapaFoto = null;

        // ✅ CORRIGIDO — único addEventListener para o inputFoto
        // Une a lógica de compressão + salvamento no localStorage em um só listener
        document.getElementById("inputFoto").addEventListener("change", function() {

            let file = this.files[0];
            if (!file || !etapaFoto) return;

            let img = new Image();
            let reader = new FileReader();

            reader.onload = function(e) {

                img.src = e.target.result;

                img.onload = function() {

                    let canvas = document.createElement("canvas");
                    let ctx = canvas.getContext("2d");

                    let width = img.width;
                    let height = img.height;

                    /*let totalPixels = width * height;
                    let maxPixels = 3000000;

                    if (totalPixels > maxPixels) {
                        let ratio = Math.sqrt(maxPixels / totalPixels);
                        width = Math.round(width * ratio);
                        height = Math.round(height * ratio);
                    }*/

                    canvas.width = width;
                    canvas.height = height;

                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function(blob) {

                        let newFile = new File([blob], file.name, {
                            type: "image/jpeg"
                        });
                        let container = new DataTransfer();
                        container.items.add(newFile);

                        document.getElementById("inputFoto").files = container.files;

                        let previewURL = URL.createObjectURL(blob);

                        // Salva URL da foto no localStorage
                        fotos[etapaFoto] = previewURL;
                        salvarProgresso();

                        // Atualiza UI da etapa
                        let status = document.getElementById("foto_status_" + etapaFoto);
                        if (status) status.classList.remove("hidden");

                        let btn = document.getElementById("foto_" + etapaFoto);
                        if (btn) {
                            btn.classList.remove("bg-blue-600");
                            btn.classList.add("bg-green-600");
                            btn.innerText = "📷 Foto enviada";
                        }

                    }, "image/jpeg", 0.85);

                };

            };

            reader.readAsDataURL(file);

        });

        function salvarProgresso() {
            localStorage.setItem("checklist_" + idChecklist, JSON.stringify(respostas));
            localStorage.setItem("checklist_fotos_" + idChecklist, JSON.stringify(fotos));
            localStorage.setItem("checklist_visual_" + idChecklist, JSON.stringify(estadoVisual));
        }

        function atualizarProgresso() {
            let respondidas = Object.keys(respostas).length;
            document.getElementById("contador").innerText = respondidas + " / " + totalEtapas;
            let porcentagem = (respondidas / totalEtapas) * 100;
            document.getElementById("barra").style.width = porcentagem + "%";
        }

        function bloquearEtapa(numero, btnSelecionado, cor) {

            estadoVisual[numero] = {
                corBorda: cor,
                btnClicado: btnSelecionado.id
            };

            document.querySelectorAll("#etapa_" + numero + " .btn-etapa").forEach(btn => {
                if (btn.id !== btnSelecionado.id) {
                    btn.classList.add("desabilitado");
                    btn.disabled = true;
                }
            });

            salvarProgresso();
        }

        function aprovar(numero) {

            if (respostas[numero]) return;

            let btn = document.getElementById("aprovar_" + numero);

            respostas[numero] = "aprovado";

            bloquearEtapa(numero, btn, "aprovado");

            document.getElementById("etapa_" + numero).classList.add("aprovado");

            fetch(`/syscheck/etaparealizada/confirmaretapa/${idChecklist}/${fkTipo}/${numero}`);

            atualizarProgresso();
            scrollProximaEtapa(numero);
        }

        function reprovar(numero) {

            if (respostas[numero]) return;

            let obs = prompt("Observação obrigatória");
            if (!obs) {
                alert("Informe observação");
                return;
            }

            let btn = document.getElementById("reprovar_" + numero);

            observacoes[numero] = obs;

            document.getElementById("obs_" + numero).innerText = "Observação: " + obs;
            document.getElementById("obs_" + numero).classList.remove("hidden");

            respostas[numero] = "reprovado";

            bloquearEtapa(numero, btn, "reprovado");

            fetch(`/syscheck/etaparealizada/reprovarEtapa`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        idChecklist: idChecklist,
                        fkTipo: fkTipo,
                        numeroEtapa: numero,
                        observacao: obs
                    })
                })
                .then(res => res.text())
                .then(data => console.log("RESPOSTA:", data))
                .catch(err => console.error("ERRO:", err));

            document.getElementById("etapa_" + numero).classList.add("reprovado");

            atualizarProgresso();
            scrollProximaEtapa(numero);
        }

        function abrirObservacao(numero) {
            let obs = prompt("Digite a observação");
            if (!obs) return;
            document.getElementById("obs_" + numero).innerText = "Observação: " + obs;
            document.getElementById("obs_" + numero).classList.remove("hidden");
        }

        function abrirFoto(numero) {
            etapaFoto = numero;
            document.getElementById("numeroEtapa").value = numero;
            document.getElementById("urlRetorno").value = window.location.pathname + "#etapa_" + numero;
            document.getElementById("modalFoto").classList.remove("hidden");
        }

        function fecharFoto() {
            document.getElementById("modalFoto").classList.add("hidden");
            document.getElementById("inputFoto").value = "";

            // ✅ CORRIGIDO — verificação de null antes de acessar elemento comentado
            let preview = document.getElementById("preview");
            if (preview) preview.classList.add("hidden");
        }

        function restaurarFotos() {
            for (let etapa in fotos) {

                let status = document.getElementById("foto_status_" + etapa);
                if (status) status.classList.remove("hidden");

                let btn = document.getElementById("foto_" + etapa);
                if (btn) {
                    btn.classList.remove("bg-blue-600");
                    btn.classList.add("bg-green-600");
                    btn.innerText = "📷 Foto enviada";
                }

                // ✅ CORRIGIDO — fotos[etapa] é string, não array
                let mini = document.getElementById("miniatura_" + etapa);
                if (mini) {
                    mini.src = fotos[etapa];
                    mini.classList.remove("hidden");
                }

            }
        }

        function restaurarVisual() {
            for (let etapa in estadoVisual) {

                let card = document.getElementById("etapa_" + etapa);
                let btn = document.getElementById(estadoVisual[etapa].btnClicado);

                card.classList.add(estadoVisual[etapa].corBorda);

                document.querySelectorAll("#etapa_" + etapa + " .btn-etapa").forEach(b => {
                    if (b.id !== btn.id) {
                        b.classList.add("desabilitado");
                        b.disabled = true;
                    }
                });

            }
        }

        function finalizarChecklist(btn) {

            for (let i = 1; i <= totalEtapas; i++) {
                if (!respostas[i]) {
                    document.getElementById("etapa_" + i).scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                    alert("Existem etapas pendentes!");
                    return;
                }
            }

            btn.disabled = true;
            btn.innerText = "⏳ Finalizando...";

            fetch(`/syscheck/checklist/finalizarChecklist/${idChecklist}`, {
                    method: "POST"
                })
                .then(res => {

                    if (!res.ok) throw new Error("Falha no servidor");

                    localStorage.removeItem("checklist_" + idChecklist);
                    localStorage.removeItem("checklist_fotos_" + idChecklist);
                    localStorage.removeItem("checklist_visual_" + idChecklist);

                    btn.innerText = "✔ Checklist finalizado";

                    setTimeout(() => {
                        window.location.href = `/syscheck/checklist/checklistFinalizado/${idChecklist}`;
                    }, 1000);

                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerText = "Erro ao finalizar";
                });
        }

        function scrollProximaEtapa(numero) {
            let proxima = document.getElementById("etapa_" + (numero + 1));
            if (proxima) proxima.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }

        // ✅ CORRIGIDO — único window.onload com toda a lógica unificada
        window.onload = function() {

            console.log("LOCALSTORAGE FOTOS:", localStorage.getItem("checklist_fotos_" + idChecklist));

            atualizarProgresso();
            restaurarFotos();
            restaurarVisual();

            for (let numero in respostas) {

                let estado = estadoVisual[numero];
                if (!estado) continue;

                let card = document.getElementById("etapa_" + numero);

                if (estado.corBorda === "aprovado") card.classList.add("aprovado");
                if (estado.corBorda === "reprovado") card.classList.add("reprovado");

                document.querySelectorAll("#etapa_" + numero + " .btn-etapa").forEach(btn => {
                    if (btn.id !== estado.btnClicado) {
                        btn.classList.add("desabilitado");
                        btn.disabled = true;
                    }
                });

            }

            for (let numero in fotos) {
                let status = document.getElementById("foto_status_" + numero);
                if (status) status.classList.remove("hidden");
            }

        };
    </script>

</body>

</html>
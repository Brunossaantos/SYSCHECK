<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Itens de Checklist - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">
    <?php

    use Util\Util;

    /** @var TipoChecklist[] $listaTipos */
    /** @var Objeto[] $listaObjetos */
    /** @var array $listaEmpresas */
    ?>
    <!-- Barra superior -->
    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
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

    <!-- Card de listagem -->
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl w-full max-w-6xl">
        <h1 class="text-2xl font-bold mb-6 text-center">Itens de checklist cadastrados</h1>

        <!-- Filtro de pesquisa -->
        <div class="mb-4">
            <input
                type="text"
                id="campoPesquisa"
                placeholder="Pesquisar por empresa, tipo, descrição ou status..."
                class="w-full px-4 py-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 border border-gray-600 focus:outline-none focus:border-blue-400"
                onkeyup="filtrarTabela()" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse" id="tabelaObjetos">
                <thead>
                    <tr class="bg-gray-700 text-gray-200">
                        <th class="px-4 py-3 cursor-pointer select-none hover:bg-gray-600 transition" onclick="ordenarTabela(0)">
                            Empresa <span class="text-gray-400 text-xs" id="icon-0">↕</span>
                        </th>
                        <th class="px-4 py-3 cursor-pointer select-none hover:bg-gray-600 transition" onclick="ordenarTabela(1)">
                            Tipo <span class="text-gray-400 text-xs" id="icon-1">↕</span>
                        </th>
                        <th class="px-4 py-3 cursor-pointer select-none hover:bg-gray-600 transition" onclick="ordenarTabela(2)">
                            Descrição do item <span class="text-gray-400 text-xs" id="icon-2">↕</span>
                        </th>
                        <th class="px-4 py-3 cursor-pointer select-none hover:bg-gray-600 transition" onclick="ordenarTabela(3)">
                            Status <span class="text-gray-400 text-xs" id="icon-3">↕</span>
                        </th>
                        <th class="px-4 py-3">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600" id="corpoTabela">
                    <?php foreach ($listaObjetos as $objeto): ?>
                        <tr class="hover:bg-gray-700 transition">
                            <!-- Empresa -->
                            <td class="px-4 py-3">
                                <?php
                                foreach ($listaEmpresas as $empresa) {
                                    if ($objeto->getFkEmpresa() == $empresa['id_empresa']) {
                                        echo htmlspecialchars($empresa['nome']);
                                        break;
                                    }
                                }
                                ?>
                            </td>

                            <!-- Tipo -->
                            <td class="px-4 py-3">
                                <?php
                                foreach ($listaTipos as $tipo) {
                                    if ($objeto->getFkTipoChecklist() == $tipo->getIdTipoChecklist()) {
                                        echo htmlspecialchars($tipo->getDescricaoTipoChecklist());
                                        break;
                                    }
                                }
                                ?>
                            </td>

                            <!-- Descrição -->
                            <td class="px-4 py-3">
                                <a href="/syscheck/objeto/alterarobjeto/<?= $objeto->getIdObjeto() ?>"
                                    class="text-blue-400 hover:underline">
                                    <?= htmlspecialchars($objeto->getDescricaoObjeto()) ?>
                                </a>
                            </td>

                            <!-- Status -->
                            <td class="px-4 py-3">
                                <?= Util::status($objeto->getStatusObjeto()) ?>
                            </td>

                            <!-- Ações -->
                            <td class="px-4 py-3">
                                <a href="/syscheck/objeto/alterarobjeto/<?= $objeto->getIdObjeto() ?>"
                                    class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Editar
                                </a>
                                <a href="/syscheck/objeto/excluir/<?= $objeto->getIdObjeto() ?>"
                                    class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium transition ml-2">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Mensagem sem resultados -->
            <div id="semResultados" class="hidden text-center text-gray-400 py-8">
                Nenhum item encontrado para a pesquisa realizada.
            </div>
        </div>
    </div>

    <script>
        // ── Filtro de pesquisa ──────────────────────────────────────
        function filtrarTabela() {
            const termo = document.getElementById('campoPesquisa').value.toLowerCase();
            const linhas = document.querySelectorAll('#corpoTabela tr');
            let visiveis = 0;

            linhas.forEach(linha => {
                const texto = linha.textContent.toLowerCase();
                const mostrar = texto.includes(termo);
                linha.style.display = mostrar ? '' : 'none';
                if (mostrar) visiveis++;
            });

            document.getElementById('semResultados').classList.toggle('hidden', visiveis > 0);
        }

        // ── Ordenação de colunas ────────────────────────────────────
        let direcaoAtual = {};

        function ordenarTabela(coluna) {
            const tbody = document.getElementById('corpoTabela');
            const linhas = Array.from(tbody.querySelectorAll('tr'));
            const ascendente = !direcaoAtual[coluna];
            direcaoAtual = {};
            direcaoAtual[coluna] = ascendente;

            // Resetar ícones
            document.querySelectorAll('[id^="icon-"]').forEach(el => el.textContent = '↕');
            document.getElementById('icon-' + coluna).textContent = ascendente ? '↑' : '↓';

            linhas.sort((a, b) => {
                const textoA = a.cells[coluna]?.textContent.trim().toLowerCase() ?? '';
                const textoB = b.cells[coluna]?.textContent.trim().toLowerCase() ?? '';
                return ascendente ?
                    textoA.localeCompare(textoB, 'pt-BR') :
                    textoB.localeCompare(textoA, 'pt-BR');
            });

            linhas.forEach(linha => tbody.appendChild(linha));
        }
    </script>

    <?php include_once __DIR__ . '/../../../public/components/footer.php'; ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <?php

    use Util\Sessao;

    include __DIR__ . '/../../public/components/navbar.php';
    $usuarioSessao = Sessao::retornarUsuarioLogado();
    ?>

    <!-- Topo fixo com Home e Logout -->
    <div class="w-full flex justify-between items-center  mb-8 max-w-6xl mx-auto">

        <a href="/syscheck/index2.php"
            class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center text-center rounded-lg text-white font-medium transition transform hover:scale-105 mt-2">
            Voltar
        </a>

        <!-- Home -->
        <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Home
        </a>

        <!-- Logout -->
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>

    </div>

    <h1 class="text-3xl font-bold mb-12 text-center">Gerenciamento De Checklist</h1>

    <div class="flex flex-col items-center w-full max-w-6xl space-y-8">

        <!-- Primeira faixa de cards (Tipos, Etapas e Itens) -->
        <?php if ($usuarioSessao->getCargo() != 2 && $usuario->getUserTipoChecklist() == 0) { ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full">

                <!-- Tipos de checklist -->
                <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col justify-between animate-fadeIn">
                    <h2 class="text-xl font-semibold mb-2">Tipos de checklist</h2>
                    <p class="text-gray-300 mb-4">Gerenciar tipos de checklist</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="/syscheck/tiposchecklist/cadastrarnovotipo" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Cadastrar</a>
                        <a href="/syscheck/tiposchecklist/gerenciarTipos" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Consultar</a>
                    </div>
                </div>

                <!-- Etapas de checklist -->
                <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col justify-between animate-fadeIn">
                    <h2 class="text-xl font-semibold mb-2">Etapas de checklists</h2>
                    <p class="text-gray-300 mb-4">Gerenciar etapas de checklist</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="/syscheck/etapaschecklist/cadastrarnovaetapa" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Cadastrar</a>
                        <a href="/syscheck/etapaschecklist/consultarChecklists" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Consultar</a>
                    </div>
                </div>

                <!-- Itens de checklist -->
                <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col justify-between animate-fadeIn">
                    <h2 class="text-xl font-semibold mb-2">Itens de checklist</h2>
                    <p class="text-gray-300 mb-4">Gerenciar itens de checklist</p>
                    <div class="flex gap-2 flex-wrap">
                        <a href="/syscheck/objeto/cadastrarobjeto" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Cadastrar</a>
                        <a href="/syscheck/objeto/listarobjetos" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Consultar</a>
                    </div>
                </div>

            </div>
        <?php } ?>

        <!-- Segunda faixa de cards (Checklists) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
            <div class="bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col justify-between animate-fadeIn">
                <h2 class="text-xl font-semibold mb-2">Checklists</h2>
                <p class="text-gray-300 mb-4">Gerenciar checklists</p>
                <div class="flex gap-2 flex-wrap">
                    <?php if ($liberarNovoChecklist) { ?>
                        <a href="/syscheck/checklist/iniciarChecklist" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Iniciar checklist</a>
                    <?php } ?>
                    <a href="/syscheck/checklist/listarChecklists" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">Consultar</a>
                </div>
            </div>
        </div>

    </div>

    <?php include __DIR__ . '/../../public/components/footer.php'; ?>

</body>

</html>
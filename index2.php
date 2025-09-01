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
    include_once __DIR__ . '/src/views/public/components/navbar.php';
    use rn\RnObjeto;
    use rn\RnusuarioEmpilhadeira;
    use Util\Sessao;
    use Util\Util;

    require_once __DIR__ . '/vendor/autoload.php';

    $rnUsuarioEmpilhadeira = new RnusuarioEmpilhadeira(Sessao::idusuario());
    $status = $rnUsuarioEmpilhadeira->verificarChecklistAberto(Sessao::idusuario());

    Sessao::mostrarMensagem();

    if (!empty($status)) {
        $empilhadeira = (new RnObjeto(Sessao::idusuario()))->selecionarObjeto($status['FK_EMPILHADEIRA']);
        $dataFormatada = Util::formatarDataHora($status['DATA_INICIO']);
    }

    $usuario = Sessao::retornarUsuarioLogado();
    ?>

    <h1 class="text-3xl font-bold mb-12 text-center">Página Inicial - SYSCHECK</h1>
    <!-- Topo fixo com Home e Logout -->
    <div class="w-full flex justify-end  items-center  mb-8 max-w-6xl mx-auto">

        <!-- Logout -->
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>



    <div class="flex flex-col items-center w-full gap-6">

        <!-- Finalizar uso da empilhadeira -->
        <?php if (isset($checklist)) { ?>
            <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition animate-fadeIn text-center max-w-md w-full">
                <h2 class="text-xl font-semibold mb-3">Finalizar o uso da empilhadeira</h2>
                <p class="text-gray-300 mb-4">
                    <strong><?= $empilhadeira->getDescricaoObjeto() ?></strong>.<br>
                    <span class="bg-red-500 text-white px-1 rounded"> <!-- fundo vermelho -->
                        <strong>Não é possível iniciar um novo checklist antes de registrar o horímetro final que está em aberto.</strong>
                    </span>
                </p>

                </p>
                <a href="/syscheck/checklist/horimetro/<?= $checklist->getIdChecklist() ?>" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Registrar horímetro final</a>
            </div>
        <?php } ?>

        <!-- Usuários -->
        <?php if ($usuario->getCargo() != 2) { ?>
            <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition animate-fadeIn text-center max-w-md w-full">
                <h2 class="text-xl font-semibold mb-3">Usuários</h2>
                <p class="text-gray-300 mb-4">Gerenciamento de usuários</p>
                <a href="/syscheck/usuario" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Gerenciar usuários</a>
            </div>
        <?php } ?>

        <!-- Checklists -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition text-center max-w-md w-full">
            <h2 class="text-xl font-semibold mb-3">Checklists</h2>
            <p class="text-gray-300 mb-4">Checklists</p>
            <a href="/syscheck/checklist" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Checklists</a>
        </div>

        <!-- Chamados -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition text-center max-w-md w-full">
            <h2 class="text-xl font-semibold mb-3">Chamados</h2>
            <p class="text-gray-300 mb-4">Verificação de chamados abertos</p>
            <div class="flex flex-col gap-2">
                <a href="/syscheck/chamado/abrirchamado" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium">Abrir chamado</a>
                <a href="/syscheck/chamado/gerenciarChamados" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium">Verificar chamados</a>
            </div>
        </div>

        <!-- Relatórios -->
        <?php if ($usuario->getCargo() != 2) { ?>
            <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition animate-fadeIn text-center max-w-md w-full">
                <h2 class="text-xl font-semibold mb-3">Relatórios</h2>
                <p class="text-gray-300 mb-4">Relatórios de checklists</p>
                <a href="#" class="bg-blue-500 hover:bg-blue-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Relatórios</a>
            </div>
        <?php } ?>

        <!-- Status da empilhadeira -->
        <?php if (!empty($status)) { ?>
            <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition animate-fadeIn text-center max-w-md w-full">
                <h2 class="text-xl font-semibold mb-3">Utilização da empilhadeira</h2>
                <p class="text-gray-300 mb-4">
                    Você iniciou a utilização da empilhadeira <strong><?= $empilhadeira->getDescricaoObjeto() ?></strong> no dia <strong><?= $dataFormatada ?></strong>.<br>
                    O que deseja fazer?
                </p>
                <div class="flex flex-col gap-4">
                    <a href="/syscheck/checklist/iniciarChecklistBateriaLitio/<?= $status['FK_CHECKLIST'] ?>" class="bg-yellow-500 hover:bg-yellow-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Trocar bateria</a>
                    <a href="/syscheck/checklist/encerrarusoempilhadeiraeletrica/<?= $status['FK_CHECKLIST'] ?>" class="bg-red-500 hover:bg-red-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Encerrar o uso</a>
                </div>
            </div>
        <?php } ?>

        <!-- Checklist veicular pendente -->
        <?php if (isset($existeChecklist) && $existeChecklist) { ?>
            <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl shadow-2xl hover:scale-105 transform transition animate-fadeIn text-center max-w-md w-full">
                <h2 class="text-xl font-semibold mb-3">Checklist Anterior</h2>
                <p class="text-gray-300 mb-4">Iniciar o checklist do <?= $objeto->getDescricaoObjeto() ?></p>
                <a href="/syscheck/etapaschecklist/etapa/<?= $checklistPendente->getIdChecklist() ?>/<?= $checklistPendente->getFkTipo() ?>/1" class="bg-yellow-500 hover:bg-yellow-600 px-6 py-3 rounded-lg font-medium transition transform hover:scale-105">Iniciar o checklist</a>
            </div>
        <?php } ?>

    </div>

    </div>

    <?php include_once __DIR__ . '/src/views/public/components/footer.php'; ?>
</body>

</html>
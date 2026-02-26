<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Checklist - SYSCHECK</title>
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
require_once __DIR__ . '/../../../../functions/log.php';
require_once __DIR__ . '/../../../../proteger.php';

use Util\Sessao;

require_once __DIR__ . '/../../../../vendor/autoload.php';

// =======================
// Usuário logado
// =======================
$usuario = Sessao::retornarUsuarioLogado();

if (!$usuario) {
    header("Location: /syscheck/login.php");
    exit;
}

$idPerfil = $usuario->getFkPerfil();

// =======================
// Função para gerar cards
// =======================
function gerarCard($titulo, $descricao, $links = [])
{
    echo '<div class="bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col justify-between animate-fadeIn">';
    echo "<h2 class='text-xl font-semibold mb-2'>{$titulo}</h2>";
    echo "<p class='text-gray-300 mb-4'>{$descricao}</p>";

    if (!empty($links)) {
        echo '<div class="flex gap-2 flex-wrap">';
        foreach ($links as $link) {

            echo "<a href='{$link['url']}' 
                class='bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105'>
                {$link['texto']}
              </a>";
        }
        echo '</div>';
    }

    echo '</div>';
}

// =======================
// Links reutilizáveis
// =======================
$linkChecklists = [
    ['texto' => 'Iniciar', 'url' => '/syscheck/checklist/iniciarChecklist'],
    ['texto' => 'Consultar', 'url' => '/syscheck/checklist/listarChecklists']
];

$linkTiposChecklist = [
    ['texto' => 'Cadastrar', 'url' => '/syscheck/tiposchecklist/cadastrarnovotipo'],
    ['texto' => 'Consultar', 'url' => '/syscheck/tiposchecklist/gerenciarTipos']
];

$linkEtapasChecklist = [
    ['texto' => 'Cadastrar', 'url' => '/syscheck/etapaschecklist/cadastrarnovaetapa'],
    ['texto' => 'Consultar', 'url' => '/syscheck/etapaschecklist/consultarChecklists']
];

$linkItensChecklist = [
    ['texto' => 'Cadastrar', 'url' => '/syscheck/objeto/cadastrarobjeto'],
    ['texto' => 'Consultar', 'url' => '/syscheck/objeto/listarobjetos']
];

// =======================
// Cards por perfil
// =======================
$cardsPerfis = [
    1 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linkChecklists],
        ['titulo' => 'Tipos de checklist', 'descricao' => 'Gerenciar tipos de checklist', 'links' => $linkTiposChecklist],
        ['titulo' => 'Etapas de checklist', 'descricao' => 'Gerenciar etapas de checklist', 'links' => $linkEtapasChecklist],
        ['titulo' => 'Itens de checklist', 'descricao' => 'Gerenciar itens de checklist', 'links' => $linkItensChecklist]
    ],
    2 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => [['texto' => 'Iniciar', 'url' => '/syscheck/checklist/iniciarChecklist']]]
    ],
    3 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linkChecklists]
    ],
    4 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => [['texto' => 'Iniciar', 'url' => '/syscheck/checklist/iniciarChecklist']]]
    ],
    5 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linkChecklists]
    ],
    7 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linkChecklists],
        ['titulo' => 'Tipos de checklist', 'descricao' => 'Gerenciar tipos de checklist', 'links' => $linkTiposChecklist],
        ['titulo' => 'Etapas de checklist', 'descricao' => 'Gerenciar etapas de checklist', 'links' => $linkEtapasChecklist],
        ['titulo' => 'Itens de checklist', 'descricao' => 'Gerenciar itens de checklist', 'links' => $linkItensChecklist]
    ],
    8 => [
        ['titulo' => 'Checklists', 'descricao' => 'Gerenciar checklists', 'links' => $linkChecklists],
        ['titulo' => 'Tipos de checklist', 'descricao' => 'Gerenciar tipos de checklist', 'links' => $linkTiposChecklist],
        ['titulo' => 'Etapas de checklist', 'descricao' => 'Gerenciar etapas de checklist', 'links' => $linkEtapasChecklist],
        ['titulo' => 'Itens de checklist', 'descricao' => 'Gerenciar itens de checklist', 'links' => $linkItensChecklist]
    ]
];
?>

<!-- Topo -->
<div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
    <a href="/syscheck/index2.php"
        class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center text-center rounded-lg text-white font-medium transition transform hover:scale-105 mt-2">
        Voltar
    </a>

    <a href="/syscheck/index2.php"
        class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
        Home
    </a>

    <a href="/syscheck/usuario/logout"
        class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
        Logout
    </a>
</div>

<div class="flex flex-col items-center w-full max-w-6xl space-y-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full">

        <?php
        if (isset($cardsPerfis[$idPerfil]) && !empty($cardsPerfis[$idPerfil])) {
            foreach ($cardsPerfis[$idPerfil] as $card) {
                gerarCard($card['titulo'], $card['descricao'], $card['links']);
            }
        } else {
            echo '<p class="text-gray-400 text-center col-span-full">
                Você não tem acesso a nenhum card nesta página.
              </p>';
        }
        ?>

    </div>
</div>

</body>
</html>
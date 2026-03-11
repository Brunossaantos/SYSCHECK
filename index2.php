<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/functions/log.php';
require_once __DIR__ . '/proteger.php';
require_once __DIR__ . '/vendor/autoload.php';

use Util\Sessao;
use service\HomeService;
use database\Conexao;

// =======================
// Usuário logado
// =======================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = Sessao::retornarUsuarioLogado();
if (!$usuario) {
    header("Location: /syscheck/login.php");
    exit;
}

$idUsuario = $usuario->getIdUsuario();
$idPerfil  = $usuario->getFkPerfil();
$idEmpresa = $usuario->getFkEmpresa();

// =======================
// Conexão e HomeService
// =======================
$conexaoObj = new Conexao();
$conexao = $conexaoObj->conectar();

$homeService = new HomeService($conexao, $idUsuario, $idPerfil, $idEmpresa);
$cards = $homeService->getCards();
$existeBloqueio = $homeService->existeBloqueio();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
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

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-4">

    <!-- Topo com Logout -->
    <div class="w-full flex justify-end items-center mb-4 max-w-6xl mx-auto">
        <a href="/syscheck/usuario/logout"
            class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105">
            Logout
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-2 text-center"></h1>

    <div class="flex flex-col items-center w-full gap-6">

            <?php foreach ($cards as $card): ?>
                <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-6 rounded-2xl max-w-md w-full text-center shadow-lg animate-fadeIn">
                    <h2 class="text-xl font-bold mb-3"><?= htmlspecialchars($card['titulo']) ?></h2>
                    <p class="text-gray-300 mb-4"><?= $card['descricao'] ?></p>

                    <?php if (!empty($card['links'])): ?>
                        <div class="flex flex-col gap-2">
                            <?php foreach ($card['links'] as $link): ?>
                                <a href="<?= htmlspecialchars($link['url']) ?>"
                                    class="px-6 py-2 rounded-lg font-medium text-white 
                                          bg-<?= htmlspecialchars($link['cor'] ?? 'blue') ?>-500 
                                          hover:bg-<?= htmlspecialchars($link['cor'] ?? 'blue') ?>-600 
                                          transition transform hover:scale-105">
                                    <?= htmlspecialchars($link['texto']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php include_once __DIR__ . '/work/components/footer.php'; ?>
    </div>

</body>

</html>
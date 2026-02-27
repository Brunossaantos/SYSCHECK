<?php
ini_set('display_errors', 0);      // não mostra no navegador
ini_set('log_errors', 1);          // registra em arquivo
ini_set('error_log', __DIR__ . '/../../../../logs/error.log'); // caminho do log
error_reporting(E_ALL);            // captura todos os erros

require_once __DIR__ . '/../../../../functions/log.php';
require_once __DIR__ . '/../../../../proteger.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

use Util\Sessao;
use database\Conexao;
use DAO\DaoChecklist;
use rn\RnChecklist;
use service\PermissaoService;

// =======================
// Usuário logado
// =======================
$usuario = Sessao::retornarUsuarioLogado();
if (!$usuario) {
    header("Location: /syscheck/login.php");
    exit;
}

$idUsuario = $usuario->getIdUsuario();
$idPerfil  = $usuario->getFkPerfil();
$idEmpresa = $usuario->getFkEmpresa();

// =======================
// Conexão e instâncias
// =======================
$conexaoObj = new Conexao();
$conexao = $conexaoObj->conectar();

$daoChecklist = new DaoChecklist($conexao, $idUsuario, $idEmpresa);
$rnChecklist = new RnChecklist($daoChecklist, $idUsuario, $idPerfil, $idEmpresa);

// =======================
// Serviço de permissões
// =======================
$permissaoService = new PermissaoService($conexao, $idUsuario, $idEmpresa);

// Obtemos os cards visíveis para o perfil do usuário
$cards = $permissaoService->getCardsVisiveis();

// =======================
// Função auxiliar para gerar cards
// =======================
function gerarCard(string $titulo, string $descricao, array $links = []): void
{
    echo '<div class="bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col justify-between">';
    echo "<h2 class='text-xl font-semibold mb-2'>" . htmlspecialchars($titulo) . "</h2>";
    echo "<p class='text-gray-300 mb-4'>" . htmlspecialchars($descricao) . "</p>";

    if (!empty($links)) {
        echo '<div class="flex gap-2 flex-wrap">';
        foreach ($links as $link) {
            echo "<a href='" . htmlspecialchars($link['url']) . "' 
                    class='bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium transition transform hover:scale-105'>
                    " . htmlspecialchars($link['texto']) . "
                  </a>";
        }
        echo '</div>';
    }

    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Checklist - SYSCHECK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center p-8">

    <div class="w-full flex justify-between items-center mb-8 max-w-6xl mx-auto">
        <a href="/syscheck/index2.php" class="bg-gray-500 hover:bg-gray-600 w-20 h-12 flex items-center justify-center rounded-lg font-medium">Voltar</a>
        <a href="/syscheck/index2.php" class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-lg font-medium">Home</a>
        <a href="/syscheck/usuario/logout" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg font-medium">Logout</a>
    </div>

    <div class="flex flex-col items-center w-full max-w-6xl space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
            <?php
            if (!empty($cards) && is_array($cards)) {
                foreach ($cards as $card) {
                    $links = isset($card['links']) && is_array($card['links']) ? $card['links'] : [];
                    gerarCard($card['titulo'], $card['descricao'], $links);
                }
            } else {
                echo '<p class="text-gray-400 text-center col-span-full">Você não tem acesso a nenhum card nesta página.</p>';
            }
            ?>
        </div>
    </div>

</body>

</html>
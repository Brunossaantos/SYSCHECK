<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['idUsuario'] !=2) {
    http_response_code(403);
    exit('Acesso negado');
}



$logFile = __DIR__ . '/../logs/system.log';


$contagem = [
    "ERROR" => 0,
];


// =======================
// Entrada do usuário
// =======================
$nivel = $_GET['nivel'] ?? '';
$buscar = $_GET['buscar'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$porPagina = 50;


// =======================
// Carregar linhas do log
// =======================
$linhas = file_exists($logFile) ? file($logFile) : [];


// =======================
// Filtragem
// =======================
$linhasFiltradas = array_filter($linhas, function ($linha) use ($nivel, $buscar) {

    if ($nivel && strpos($linha, "[$nivel]") === false) return false;
    if ($buscar && stripos($linha, $buscar) === false) return false;
    return true;
});


// =======================
// Contagem por nível
// =======================
foreach ($linhas as $l) {
    foreach ($contagem as $k => $_) {
        if (strpos($l, "[$k]") !== false) $contagem[$k]++;
    }
}


// =======================
// Paginação
// =======================
$total = count($linhasFiltradas);
$paginas = max(1, ceil($total / $porPagina));
$offset = ($page - 1) * $porPagina;
$linhasPagina = array_slice($linhasFiltradas, $offset, $porPagina);


// =======================
// Download dos logs filtrados
// =======================
if (isset($_GET['downloadFiltro'])) {
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="logs_filtrados.log"');
    foreach ($linhasFiltradas as $l) echo $l;
    exit;
}


// =======================
// Limpar log
// =======================
if (isset($_GET['limpar'])) {
    file_put_contents($logFile, "");
    header("Location: logs.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Logs do Sistema - Dev</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

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

<body class="bg-gray-900 text-gray-200 p-6">

    <div class="max-w-6xl mx-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-red-400">Logs</h1>
        </div>

        <!-- DASHBOARD -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            <?php foreach ($contagem as $k => $qtd): ?>
                <div class="p-4 rounded-xl shadow bg-gray-800 border border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold"><?= $k ?></span>
                        <span class="px-3 py-1 rounded-lg text-white text-sm <?= $cores[$k] ?>">
                            <?= $qtd ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- FILTROS -->
        <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 border border-gray-700">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="text-sm text-gray-400">Nível</label>
                    <select name="nivel" class="w-full p-2 bg-gray-700 text-gray-200 rounded-lg">
                        <option value="">Todos</option>
                        <option value="ERROR" <?= $nivel == "ERROR" ? "selected" : "" ?>>ERROR</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm text-gray-400">Buscar</label>
                    <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>"
                        class="w-full p-2 bg-gray-700 text-gray-200 rounded-lg"
                        placeholder="Mensagem do erro, arquivo, linha...">
                </div>

                <div class="flex items-end">
                    <button class="w-full bg-red-600 hover:bg-red-500 text-white p-2 rounded-lg font-semibold">
                        Filtrar
                    </button>
                </div>

            </form>
        </div>

        <!-- AÇÕES -->
        <div class="flex flex-wrap gap-4 mb-6">
            <a href="?downloadFiltro=1&nivel=<?= $nivel ?>&buscar=<?= $buscar ?>"
                class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg font-semibold text-white">
                Baixar log filtrado
            </a>

            <a href="?limpar=1"
                onclick="return confirm('Tem certeza que deseja limpar o log?')"
                class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg font-semibold text-white">
                Limpar Log
            </a>
        </div>

        <!-- LISTA DE LOGS -->
        <div class="bg-black p-4 rounded-xl shadow-inner border border-gray-700 max-h-[700px] overflow-auto">

            <?php
            $dataAtual = "";

            if (!empty($linhasPagina)):
                foreach ($linhasPagina as $linha):

                    preg_match('/\[(.*?)\] \[(.*?)\] (.*)/', $linha, $p);

                    $data = substr($p[1] ?? '', 0, 10);
                    $hora = substr($p[1] ?? '', 11);
                    $nivelLinha = $p[2] ?? '';
                    $msg = $p[3] ?? '';

                    $cor = $cores[$nivelLinha] ?? "bg-gray-600";

                    // Cabeçalho de data
                    if ($data !== $dataAtual):
                        $dataAtual = $data;
                        echo "<h2 class='text-lg text-red-400 font-bold mt-4 mb-2 border-b border-gray-700 pb-1'>$data</h2>";
                    endif;
            ?>

                    <div class="flex gap-3 border-b border-gray-800 py-2">
                        <span class="text-gray-400 min-w-[70px]"><?= $hora ?></span>
                        <span class="tag px-2 py-1 rounded-lg text-white text-xs <?= $cor ?>"><?= $nivelLinha ?></span>
                        <span class="text-gray-300"><?= htmlspecialchars($msg) ?></span>
                    </div>

            <?php
                endforeach;
            else:
                echo "<p class='text-gray-500 text-center py-10'>Nenhum log encontrado </p>";
            endif;
            ?>

        </div>

        <!-- PAGINAÇÃO -->
        <div class="flex justify-center mt-6 gap-2">
            <?php for ($i = 1; $i <= $paginas; $i++): ?>
                <a href="?page=<?= $i ?>&nivel=<?= $nivel ?>&buscar=<?= $buscar ?>"
                    class="px-4 py-2 rounded-lg 
                   <?= $i == $page ? 'bg-gray-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

    </div>

</body>

</html>
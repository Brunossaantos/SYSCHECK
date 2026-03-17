<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['idUsuario'] != 2) {
    http_response_code(403);
    exit('Acesso negado');
}

/* =========================
CONFIGURAÇÃO
========================= */

$tipoLog = $_GET['tipo'] ?? 'system';

$arquivos = [
    "system" => "system.log",
    "access" => "access.log",
    "debug" => "debug.log"
];

$logFile = __DIR__ . '/../logs/' . ($arquivos[$tipoLog] ?? "system.log");

$cores = [
    "ERROR" => "bg-red-600",
    "ACCESS" => "bg-blue-600",
    "DEBUG" => "bg-yellow-600"
];

$contagem = [
    "ERROR" => 0,
    "ACCESS" => 0,
    "DEBUG" => 0
];


/* =========================
ENTRADA
========================= */

$nivel = $_GET['nivel'] ?? '';
$buscar = $_GET['buscar'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$porPagina = 50;


/* =========================
CARREGAR LOG
========================= */

$linhas = file_exists($logFile)
? file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
: [];


/* =========================
CONTAGEM
========================= */

foreach ($linhas as $l) {

    foreach ($contagem as $k => $_) {

        if (strpos($l, "[$k]") !== false) {
            $contagem[$k]++;
        }

    }

}


/* =========================
FILTRO
========================= */

$linhasFiltradas = array_filter($linhas, function ($linha) use ($nivel, $buscar) {

    if ($nivel && strpos($linha, "[$nivel]") === false) return false;
    if ($buscar && stripos($linha, $buscar) === false) return false;

    return true;

});


/* =========================
PAGINAÇÃO
========================= */

$total = count($linhasFiltradas);
$paginas = max(1, ceil($total / $porPagina));
$offset = ($page - 1) * $porPagina;

$linhasPagina = array_slice($linhasFiltradas, $offset, $porPagina);


/* =========================
DOWNLOAD
========================= */

if (isset($_GET['downloadFiltro'])) {

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="logs_filtrados.log"');

    foreach ($linhasFiltradas as $l) {
        echo $l . PHP_EOL;
    }

    exit;

}


/* =========================
LIMPAR LOG
========================= */

if (isset($_GET['limpar'])) {

    file_put_contents($logFile, "", LOCK_EX);

    header("Location: logs.php");

    exit;

}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<title>Logs do Sistema</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-200 p-6">

<div class="max-w-6xl mx-auto">

<!-- HEADER -->

<div class="flex justify-between items-center mb-8">

<h1 class="text-3xl font-bold text-red-400">Logs do Sistema</h1>

<div class="flex gap-4">

<a href="/syscheck/index2.php"
class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg">
Home
</a>

<a href="/syscheck/usuario/logout"
class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg">
Logout
</a>

</div>

</div>


<!-- DASHBOARD -->

<div class="grid grid-cols-3 gap-4 mb-6">

<?php foreach ($contagem as $k => $qtd): ?>

<div class="bg-gray-800 p-4 rounded-xl border border-gray-700">

<div class="flex justify-between">

<span><?= $k ?></span>

<span class="px-3 py-1 text-xs text-white rounded-lg <?= $cores[$k] ?>">
<?= $qtd ?>
</span>

</div>

</div>

<?php endforeach; ?>

</div>


<!-- FILTROS -->

<div class="bg-gray-800 p-6 rounded-xl border border-gray-700 mb-6">

<form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

<div>

<label class="text-sm text-gray-400">Arquivo</label>

<select name="tipo" class="w-full p-2 bg-gray-700 rounded">

<option value="system" <?= $tipoLog=="system"?'selected':'' ?>>system.log</option>
<option value="access" <?= $tipoLog=="access"?'selected':'' ?>>access.log</option>
<option value="debug" <?= $tipoLog=="debug"?'selected':'' ?>>debug.log</option>

</select>

</div>


<div>

<label class="text-sm text-gray-400">Nível</label>

<select name="nivel" class="w-full p-2 bg-gray-700 rounded">

<option value="">Todos</option>
<option value="ERROR">ERROR</option>
<option value="ACCESS">ACCESS</option>
<option value="DEBUG">DEBUG</option>

</select>

</div>


<div class="md:col-span-2">

<label class="text-sm text-gray-400">Buscar</label>

<input type="text"
name="buscar"
value="<?= htmlspecialchars($buscar) ?>"
class="w-full p-2 bg-gray-700 rounded">

</div>


<div class="flex items-end">

<button class="w-full bg-red-600 hover:bg-red-500 p-2 rounded-lg">
Filtrar
</button>

</div>

</form>

</div>


<!-- AÇÕES -->

<div class="flex gap-4 mb-6">

<a href="?downloadFiltro=1"
class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg">
Baixar Log
</a>

<a href="?limpar=1"
onclick="return confirm('Limpar log?')"
class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg">
Limpar Log
</a>

</div>


<!-- LOGS -->

<div class="bg-black p-4 rounded-xl border border-gray-700 max-h-[700px] overflow-auto">

<?php

$dataAtual = "";

foreach ($linhasPagina as $linha) {

preg_match('/\[(.*?)\] \[(.*?)\] (.*)/', $linha, $p);

$data = substr($p[1] ?? '',0,10);
$hora = substr($p[1] ?? '',11);
$nivelLinha = $p[2] ?? '';
$msg = $p[3] ?? '';

$cor = $cores[$nivelLinha] ?? "bg-gray-600";

if ($data != $dataAtual) {

$dataAtual = $data;

echo "<h2 class='text-red-400 font-bold mt-4 mb-2 border-b border-gray-700'>$data</h2>";

}

?>

<div class="flex gap-3 border-b border-gray-800 py-2">

<span class="text-gray-400 min-w-[70px]"><?= $hora ?></span>

<span class="px-2 py-1 text-xs text-white rounded <?= $cor ?>">
<?= $nivelLinha ?>
</span>

<span><?= htmlspecialchars($msg) ?></span>

</div>

<?php } ?>

</div>


<!-- PAGINAÇÃO -->

<div class="flex justify-center mt-6 gap-2">

<?php for ($i=1;$i<=$paginas;$i++): ?>

<a href="?page=<?= $i ?>&tipo=<?= $tipoLog ?>&nivel=<?= $nivel ?>&buscar=<?= $buscar ?>"
class="px-4 py-2 rounded
<?= $i==$page ? 'bg-gray-600' : 'bg-gray-700 hover:bg-gray-600' ?>">
<?= $i ?>
</a>

<?php endfor; ?>

</div>

</div>

</body>
</html>
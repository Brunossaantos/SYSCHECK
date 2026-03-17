<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['idUsuario'] != 3) {
    http_response_code(403);
    exit('Acesso negado');
}

// Ocultar dados sensíveis
$server = $_SERVER;
unset($server['HTTP_COOKIE']);
unset($server['PHP_AUTH_PW']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<title>Debug do Sistema</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-gray-200 p-6">

<div class="max-w-6xl mx-auto">

<div class="flex justify-between mb-6">

<a href="/syscheck/index2.php"
class="bg-gray-600 hover:bg-gray-500 px-4 py-2 rounded-lg">
Voltar
</a>

<a href="/syscheck/usuario/logout"
class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded-lg">
Logout
</a>

</div>

<h1 class="text-3xl font-bold text-yellow-400 mb-6">Debug do Sistema</h1>

<!-- Sessão -->
<div class="bg-gray-800 p-6 rounded-xl mb-6 border border-gray-700">
<h2 class="text-xl font-semibold mb-2">Sessão Atual</h2>
<pre class="bg-black p-4 rounded text-green-400 overflow-auto"><?php print_r($_SESSION); ?></pre>
</div>

<!-- Server -->
<div class="bg-gray-800 p-6 rounded-xl mb-6 border border-gray-700">
<h2 class="text-xl font-semibold mb-2">Variáveis do Servidor</h2>
<pre class="bg-black p-4 rounded text-blue-400 overflow-auto"><?php print_r($server); ?></pre>
</div>

<!-- Constantes -->
<div class="bg-gray-800 p-6 rounded-xl mb-6 border border-gray-700">
<h2 class="text-xl font-semibold mb-2">Constantes Definidas</h2>
<pre class="bg-black p-4 rounded text-purple-400 overflow-auto"><?php print_r(get_defined_constants(true)['user'] ?? []); ?></pre>
</div>

<!-- ENV -->
<div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
<h2 class="text-xl font-semibold mb-2">Variáveis de Ambiente</h2>
<pre class="bg-black p-4 rounded text-orange-400 overflow-auto"><?php print_r($_ENV); ?></pre>
</div>

</div>

</body>
</html>
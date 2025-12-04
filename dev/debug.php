<?php
session_start();

if (!isset($_SESSION['idUsuario']) || $_SESSION['idUsuario'] !=3) {
    http_response_code(403);
    exit('Acesso negado');
}
?>

<h2>Debug do Sistema</h2>

<h3>Sessão Atual</h3>
<pre><?php print_r($_SESSION); ?></pre>

<h3>Variáveis do Servidor</h3>
<pre><?php print_r($_SERVER); ?></pre>

<h3>Constantes Definidas</h3>
<pre><?php print_r(get_defined_constants(true)['user']); ?></pre>

<h3>Ambiente (ENV)</h3>
<pre><?php print_r($_ENV); ?></pre>
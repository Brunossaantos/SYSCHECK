<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tempoInatividade = 10800; // 30 minutos

// No Router, usamos REQUEST_URI para saber o que o usuário digitou na URL
$urlAcessada = $_SERVER['REQUEST_URI'];

// Verificamos se a URL contém a palavra "login"
$ehPaginaLogin = (strpos($urlAcessada, 'login.php') !== false || strpos($urlAcessada, '/login') !== false);

// 1. Se for página de login, não faz nada e sai do script
if ($ehPaginaLogin) {
    return;
}

// 2. Se NÃO estiver logado, manda para o login com aviso de "precisa logar"
if (!isset($_SESSION['idUsuario'])) {
    header("Location: /syscheck/login.php?msg=login");
    exit;
}

// 3. SE estiver logado, aí sim verificamos a inatividade
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $tempoPassado = time() - $_SESSION['LAST_ACTIVITY'];

    if ($tempoPassado > $tempoInatividade) {
        // Sessão expirou de fato
        session_unset();
        session_destroy();
        header("Location: /syscheck/login.php?msg=inatividade");
        exit;
    }
}

// 4. Atualiza o tempo de atividade apenas se o usuário estiver logado e ativo
$_SESSION['LAST_ACTIVITY'] = time();

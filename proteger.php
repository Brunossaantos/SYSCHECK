<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tempoInatividade = 10800; // 3 horas
$urlAcessada = $_SERVER['REQUEST_URI'];

// --- 1. EXCEÇÕES (Páginas Livres) ---
$ehPaginaLogin = (strpos($urlAcessada, 'login.php') !== false || strpos($urlAcessada, '/login') !== false);
$ehPaginaPrimeiroAcesso = (strpos($urlAcessada, 'cadastrarSenha') !== false);

if ($ehPaginaLogin || $ehPaginaPrimeiroAcesso) {
    return;
}

// --- 2. VERIFICAÇÃO DE AUTENTICAÇÃO ---
if (!isset($_SESSION['idUsuario'])) {
    header("Location: /syscheck/login.php?msg=login");
    exit;
}

// --- 3. BLOQUEIO DE PERFIL (ÁREA DA LISTA) ---
/*if (strpos($urlAcessada, '/lista') !== false && strpos($urlAcessada, 'checklist') === false) {

    $perfilUsuario = $_SESSION['idPerfil'] ?? null;

    if (intval($perfilUsuario) !== 6) {
        // Echo do script com encerramento imediato do PHP
        echo "
        <script>
            alert('Acesso Negado! Esta área é exclusiva para o Perfil Veicular.');
            window.location.href = '/syscheck/index2.php';
        </script>";

        // O exit é vital aqui para o PHP não enviar mais nada ao navegador
        exit;
    }
}
*/
// --- 4. VERIFICAÇÃO DE INATIVIDADE ---
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $tempoPassado = time() - $_SESSION['LAST_ACTIVITY'];

    if ($tempoPassado > $tempoInatividade) {
        session_unset();
        session_destroy();
        header("Location: /syscheck/login.php?msg=inatividade");
        exit;
    }
}

// 5. Atualiza o timestamp de atividade
$_SESSION['LAST_ACTIVITY'] = time();